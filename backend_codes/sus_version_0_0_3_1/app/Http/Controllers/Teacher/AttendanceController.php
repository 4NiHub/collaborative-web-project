<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceController extends \App\Http\Controllers\Controller
{
    /**
     * Get groups for a module
     */
    public function groups($moduleId)
    {
        $subjectId = (int) substr($moduleId, 1);

        $groups = DB::table('subjects_groups_bridge_table')
            ->join('groups', 'subjects_groups_bridge_table.group_id', '=', 'groups.group_id')
            ->where('subjects_groups_bridge_table.subject_id', $subjectId)
            ->select('groups.group_id', 'groups.group_name')
            ->get();

        return response()->json(['data' => $groups]);
    }

    /**
     * Get attendance history for a module
     */
    public function history($moduleId)
    {
        $subjectId = (int) substr($moduleId, 1);

        $records = DB::table('attendance')
            ->join('students', 'attendance.student_id', '=', 'students.student_id')
            ->join('timetable', 'attendance.session_id', '=', 'timetable.session_id')
            ->join('subjects_groups_bridge_table', 'timetable.subject_group_id', '=', 'subjects_groups_bridge_table.subject_group_id')
            ->where('subjects_groups_bridge_table.subject_id', $subjectId)
            ->select([
                'attendance.session_date',
                'attendance.session_id',
                'students.student_id',
                'students.name',
                'students.surname',
                'attendance.is_present'
            ])
            ->orderByDesc('attendance.session_date')
            ->get();

        return response()->json(['data' => $records]);
    }

    /**
     * Save (or update) attendance for a session
     */
    public function save(Request $request, $moduleId)
    {
        Log::info('Attendance SAVE called', [
            'moduleId'   => $moduleId,
            'payload'    => $request->all(),
            'raw_input'  => $request->getContent()
        ]);

        $validated = $request->validate([
            'groupId'   => 'required|string|exists:groups,group_name',
            'date'      => 'required|date',
            'time'      => 'required|string',
            'students'  => 'required|array',
            'students.*.id'     => 'required|integer|exists:students,student_id',
            'students.*.status' => 'required|in:present,absent',
        ]);


        Log::info('Validation PASSED - groupId received', [
            'groupId'      => $validated['groupId'],
            'groupId_type' => gettype($validated['groupId'])
        ]);

        $subjectId = (int) substr($moduleId, 1);

        $group = DB::table('groups')
            ->where('group_name', $validated['groupId'])
            ->first();

        if (!$group) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        $groupId = $group->group_id;
        $studentIds = collect($validated['students'])
            ->pluck('id')
            ->unique()
            ->values();

        $groupStudentIds = DB::table('students')
            ->where('group_id', $groupId)
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $invalidStudentIds = $studentIds
            ->reject(fn ($id) => in_array((int) $id, $groupStudentIds, true))
            ->values()
            ->all();

        if (!empty($invalidStudentIds)) {
            Log::warning('Attendance save rejected due to cross-group students', [
                'moduleId' => $moduleId,
                'groupId' => $validated['groupId'],
                'invalid_student_ids' => $invalidStudentIds,
            ]);

            return response()->json([
                'error' => 'Some students do not belong to the selected group.',
                'invalid_student_ids' => $invalidStudentIds,
            ], 422);
        }

        $timeMap = ['09:00' => 1, '11:00' => 2, '14:00' => 3];
        $timeSlot = $timeMap[$validated['time']] ?? null;

        if (!$timeSlot) {
            return response()->json(['error' => 'Invalid time slot'], 422);
        }

        // Find existing session
        $session = DB::table('timetable')
            ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
            ->where('bridge.subject_id', $subjectId)
            ->where('bridge.group_id', $groupId)
            ->where('timetable.day_slot', date('N', strtotime($validated['date'])))
            ->where('timetable.time_slot', $timeSlot)
            ->select('timetable.session_id')
            ->first();

        if ($session) {
            $sessionId = $session->session_id;
            Log::info('Found existing session', ['session_id' => $sessionId]);
        } else {
            // Create new session
            $bridge = DB::table('subjects_groups_bridge_table')
                ->where('subject_id', $subjectId)
                ->where('group_id', $groupId)
                ->first();

            if (!$bridge) {
                return response()->json(['error' => 'No bridge between subject and group'], 400);
            }

            try {
                $sessionId = DB::table('timetable')->insertGetId(
                    [
                        'subject_group_id' => $bridge->subject_group_id,
                        'time_slot'        => $timeSlot,
                        'day_slot'         => date('N', strtotime($validated['date'])),
                        'room_number'      => 301,
                        'session_type'     => 'Lecture',
                        'building'         => 'Block A',
                    ],
                    'session_id'
                );

                Log::info('Created new session', ['session_id' => $sessionId]);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() === '23505') {
                    Log::warning('Race condition on session insert - retrying lookup');
                    $session = DB::table('timetable')
                        ->where('subject_group_id', $bridge->subject_group_id)
                        ->where('time_slot', $timeSlot)
                        ->where('day_slot', date('N', strtotime($validated['date'])))
                        ->select('session_id')
                        ->first();

                    if ($session) {
                        $sessionId = $session->session_id;
                    } else {
                        throw $e;
                    }
                } else {
                    throw $e;
                }
            }
        }

        // Update or insert attendance records
        foreach ($validated['students'] as $st) {
            DB::table('attendance')->updateOrInsert(
                [
                    'session_id'  => $sessionId,
                    'student_id'  => $st['id'],
                ],
                [
                    'is_present'   => $st['status'] === 'present',
                    'session_date' => $validated['date'],
                ]
            );
        }

        // Clean response - no extra output
        $payload = [
            'success'          => true,
            'message'          => 'Attendance saved',
            'session_id'       => $sessionId,
            'updated_students' => count($validated['students'] ?? []),
            'timestamp'        => now()->toDateTimeString(),
            'debug_group'      => $validated['groupId']
        ];

        Log::info('RETURNING CLEAN JSON', $payload);

        return response()->json($payload)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
