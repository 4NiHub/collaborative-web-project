<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $mentor = DB::table('mentors')->where('user_id', $user->user_id)->first();

        if (!$mentor) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        $subjects = DB::table('subjects')
            ->where('mentor_id', $mentor->mentor_id)
            ->pluck('name')
            ->toArray();

        return response()->json([
            'data' => [
                'id'          => $mentor->mentor_id,
                'title'       => 'Dr.',
                'firstName'   => $mentor->name,
                'lastName'    => $mentor->surname,
                'email'       => $user->email,
                'department'  => $mentor->department ?? 'Computer Science',
                'phone'       => $mentor->phone_number ?? null,
                'office'      => $mentor->office_location ?? 'Block A, Room 205',
                'officeHours' => $mentor->office_hours ?? 'Mon 14:00-16:00, Wed 10:00-12:00',
                'subjects'    => $subjects,
                'bio'         => $mentor->bio ?? 'Expert with 12+ years experience.',
            ]
        ]);
    }

public function dashboardStats()
{
    $mentorId = DB::table('mentors')
        ->where('user_id', Auth::id())
        ->value('mentor_id');

    if (!$mentorId) {
        return response()->json(['error' => 'Teacher not found'], 404);
    }

    $todayDow = now()->dayOfWeekIso; // 1 = Monday, 7 = Sunday

    $stats = [
        'upcomingClasses' => DB::table('timetable')
            ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
            ->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
            ->where('subjects.mentor_id', $mentorId)
            ->where('timetable.day_slot', $todayDow)
            ->count(),

        'coursesAssigned' => DB::table('subjects')
            ->where('mentor_id', $mentorId)
            ->count(),

        'totalStudents'   => DB::table('subjects_groups_bridge_table')
            ->join('subjects', 'subjects_groups_bridge_table.subject_id', '=', 'subjects.subject_id')
            ->join('groups', 'subjects_groups_bridge_table.group_id', '=', 'groups.group_id')
            ->where('subjects.mentor_id', $mentorId)
            ->sum(DB::raw('(SELECT COUNT(*) FROM students WHERE group_id = groups.group_id)')),
    ];

    return response()->json(['data' => $stats]);
}

public function todayClasses()
{
    $mentorId = DB::table('mentors')->where('user_id', Auth::id())->value('mentor_id');

    $todayDow = now()->dayOfWeekIso;

    $classes = DB::table('timetable')
        ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
        ->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
        ->join('groups', 'bridge.group_id', '=', 'groups.group_id')
        ->where('subjects.mentor_id', $mentorId)
        ->where('timetable.day_slot', $todayDow)
        ->select([
            'subjects.name as title',
            DB::raw("CONCAT('CS', subjects.subject_id) as code"),
            'timetable.session_type as type',
            DB::raw("CASE timetable.time_slot 
                WHEN 1 THEN '09:00–10:30' 
                WHEN 2 THEN '11:00–12:30' 
                WHEN 3 THEN '14:00–15:30' 
                END as time"),
            DB::raw("CONCAT('Room ', timetable.room_number) as room"),
            'groups.group_name as group',
        ])
        ->orderBy('timetable.time_slot')
        ->get();

    return response()->json(['data' => $classes]);
}

    public function recentActivity()
    {
        return response()->json(['data' => []]);
    }
}
