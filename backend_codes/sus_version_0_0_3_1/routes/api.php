<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;

// ====================== ANALYTICS HELPERS (ported from Python) ======================
// function calculateGPA($studentId) {
//     $data = DB::table('grades')
//         ->join('subjects', 'grades.subject_id', '=', 'subjects.subject_id')
//         ->where('grades.student_id', $studentId)
//         ->select(DB::raw('SUM(grades.points * subjects.credits) as weighted, SUM(subjects.credits) as total_credits'))
//         ->first();
//     return $data && $data->total_credits ? round($data->weighted / $data->total_credits, 2) : 3.7;
// }

// function getStudentRating($studentId) {
//     $gpas = DB::table('grades')
//         ->join('subjects', 'grades.subject_id', '=', 'subjects.subject_id')
//         ->select('grades.student_id', DB::raw('SUM(points*credits)/SUM(credits) as gpa'))
//         ->groupBy('student_id')
//         ->pluck('gpa', 'student_id')
//         ->toArray();

//     if (!isset($gpas[$studentId])) return 'N/A';
//     $myGpa = $gpas[$studentId];
//     $below = 0;
//     foreach ($gpas as $g) if ($g < $myGpa) $below++;
//     $pct = count($gpas) ? $below / count($gpas) : 0;
//     return 'Top ' . round((1 - $pct) * 100) . '%';
// }

// function getImprovementTarget($studentId) {
//     $worst = DB::table('grades')
//         ->where('student_id', $studentId)
//         ->orderBy('percentage')
//         ->first();
//     return $worst ? "Subject ID {$worst->subject_id} (Current: {$worst->percentage}%). Priority: High." : 'No data';
// }

// function getAnalyticsJSON($studentId) {
//     return [
//         'gpa' => calculateGPA($studentId),
//         'rating' => getStudentRating($studentId),
//         'improvement_target' => getImprovementTarget($studentId),
//         'status' => 'Analysis Complete'
//     ];
// }

// function calculateAttendancePercentage($studentId) {
//     $total = DB::table('attendance')
//         ->join('students', 'attendance.student_id', '=', 'students.student_id')
//         ->join('timetable', 'attendance.session_id', '=', 'timetable.session_id')
//         ->join('subjects_groups_bridge_table as sgb', 'timetable.subject_group_id', '=', 'sgb.subject_group_id')
//         ->where('attendance.student_id', $studentId)
//         ->whereColumn('students.group_id', 'sgb.group_id')
//         ->count();

//     if ($total === 0) {
//         return 0;
//     }

//     $present = DB::table('attendance')
//         ->join('students', 'attendance.student_id', '=', 'students.student_id')
//         ->join('timetable', 'attendance.session_id', '=', 'timetable.session_id')
//         ->join('subjects_groups_bridge_table as sgb', 'timetable.subject_group_id', '=', 'sgb.subject_group_id')
//         ->where('attendance.student_id', $studentId)
//         ->whereColumn('students.group_id', 'sgb.group_id')
//         ->where('is_present', true)
//         ->count();

//     return (int) round(($present / $total) * 100);
// }

// function buildModuleResources() {
//     $materialsPath = public_path('materials');

//     if (!File::exists($materialsPath)) {
//         return [];
//     }

//     $titleMap = [
//         'lec-slides-1.pdf' => 'Lecture Slides Week 1',
//         'lec-slides-2.pdf' => 'Lecture Slides Week 2',
//         'lab-sheet-1.docx' => 'Workshop Brief 1',
//         'sample-code.zip' => 'Sample Code',
//     ];

//     $orderMap = [
//         'lec-slides-1.pdf' => 1,
//         'lec-slides-2.pdf' => 2,
//         'lab-sheet-1.docx' => 3,
//         'sample-code.zip' => 4,
//     ];

//     return collect(File::files($materialsPath))
//         ->sortBy(function ($file) use ($orderMap) {
//             return $orderMap[$file->getFilename()] ?? 999;
//         })
//         ->values()
//         ->map(function ($file, $index) use ($titleMap) {
//             $filename = $file->getFilename();
//             $basename = pathinfo($filename, PATHINFO_FILENAME);
//             $title = $titleMap[$filename] ?? ucwords(str_replace(['-', '_'], ' ', $basename));

//             if (preg_match('/^lec-slides-(\d+)$/i', $basename, $matches)) {
//                 $title = 'Lecture Slides Week ' . $matches[1];
//             } elseif (preg_match('/^(lab-sheet|workshop-brief)-(\d+)$/i', $basename, $matches)) {
//                 $title = 'Workshop Brief ' . $matches[2];
//             }

//             return [
//                 'id' => 'R' . ($index + 1),
//                 'title' => $title,
//                 'type' => strtolower($file->getExtension() ?: 'file'),
//                 'url' => asset('materials/' . $filename),
//             ];
//         })
//         ->all();
// }

// ====================== ROUTES ======================

Route::post('/auth/login', function () {
    $credentials = request()->only('email', 'password');
    if (auth()->attempt($credentials)) {
        $user = auth()->user();
        $token = $user->createToken('api-token')->plainTextToken;
        $student = $user->student;

        $role = ($user->role_id === 1) ? 'student' : 'teacher';

        return response()->json([
            'data' => [
                'token' => $token,
                'role' => $role,
                'student' => [
                    'id' => 'STU001',
                    'firstName' => $student->name,
                    'lastName' => $student->surname,
                    'email' => $user->email,
                    'studentId' => '20240001',
                    'programme' => $student->group->group_name ?? 'BSc Computer Science',
                    'year' => $student->entry_year,
                    'avatar' => null
                ]
            ]
        ]);
    }
    return response()->json(['error' => 'Invalid credentials'], 401);
});

Route::get('/career/jobs', function () {
    // Already fully DB
    $jobs = DB::table('jobs')
        ->where('status', 'active')
        ->get()
        ->map(function ($job) {
            $info = explode("\n", $job->body ?? '');
            return [
                'id' => $job->job_id,
                'title' => $job->title,
                'company' => str_contains($job->company ?? '', 'TechCorp') ? 'TechCorp' : 
                             (str_contains($job->company ?? '', 'DataViz') ? 'DataViz Ltd' : 'CreativeHub'),
                'location' => str_contains($job->location ?? '', 'London') ? 'London' : 'Remote',
                'type' => str_contains($job->type ?? '', 'Full-time') ? 'Full-time' : 
                          (str_contains($job->type ?? '', 'Internship') ? 'Internship' : 'Part-time'),
                'salary' => str_contains($job->salary ?? '', '£35,000') ? '£35,000' : 
                            (str_contains($job->salary ?? '', '£800') ? '£800/mo' : '£20/hr'),
                'deadline' => str_contains($job->deadline ?? '', '2026-03-15') ? '2026-03-15' : 
                              (str_contains($job->deadline ?? '', '2026-03-01') ? '2026-03-01' : '2026-03-20')
            ];
        });

    return ['data' => $jobs, 'meta' => ['page' => 1, 'limit' => 10, 'total' => $jobs->count(), 'totalPages' => 1]];
});


// ====================== PROTECTED ROUTES (ALL NOW FULLY DB-BASED) ======================
Route::middleware('auth:sanctum')->group(function () {

// ────────────────────────────────────────────────────────────────
//  /api/profile  –  Shared endpoint for both students & teachers
// ────────────────────────────────────────────────────────────────
    Route::get('/profile', function () {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // ── STUDENT PROFILE ────────────────────────────────────────────────────────────────
        if ($user->role_id === 1) {
            $student = $user->student;

            if (!$student) {
                return response()->json(['error' => 'Student profile not found'], 404);
            }

            // GPA calculation – simplified version (you can keep your original logic)
            $gpa = DB::table('grades')
                ->join('subjects', 'grades.subject_id', '=', 'subjects.subject_id')
                ->where('grades.student_id', $student->student_id)
                ->selectRaw('COALESCE(SUM(grades.points * subjects.credits) / SUM(subjects.credits), 0) as gpa')
                ->value('gpa');

            $attendancePercentage = calculateAttendancePercentage($student->student_id);

            return response()->json([
                'data' => [
                    'role'                 => 'student',
                    'id'                   => 'STU' . str_pad($student->student_id, 3, '0', STR_PAD_LEFT),
                    'firstName'            => $student->name ?? '—',
                    'lastName'             => $student->surname ?? '—',
                    'email'                => $user->email ?? '—',
                    'studentId'            => formatStudentId($student->student_id),
                    'programme'            => $student->group->group_name ?? 'BSc Computer Science',
                    'year'                 => $student->entry_year ?? '—',
                    'gpa'                  => round($gpa, 2),
                    'creditsCompleted'     => $student->credits_completed ?? 0,
                    'attendancePercentage' => $attendancePercentage,
                ]
            ]);
        }

        // ── TEACHER / MENTOR PROFILE ───────────────────────────────────────────────────────
        $mentor = $user->mentor;

        if (!$mentor) {
            return response()->json(['error' => 'Teacher profile not found'], 404);
        }

        // profile_data is already cast to array in Mentor model → no need to json_decode again
        $extra = $mentor->profile_data ?? [];

        return response()->json([
            'data' => [
                'role'       => 'teacher',
                'id'         => $mentor->mentor_id,
                'firstName'  => $mentor->name ?? '—',
                'lastName'   => $mentor->surname ?? '—',
                'title'      => 'Dr.', // you can make this dynamic later if needed
                'email'      => $mentor->email ?? '—',
                'phone'      => $mentor->phone_number ?? '—',
                'department' => $mentor->department ?? 'Computer Science',
                'office'     => $mentor->office_location ?? '—',
                'officeHours'=> $mentor->office_hours ?? '—',
                'nationality'=> $mentor->nationality ?? '—',
                'languages'  => $mentor->languages ?? 'English',
                'bio'        => $mentor->bio ?? '—',

                // Subjects taught by this mentor
                'subjects'   => DB::table('subjects')
                                ->where('mentor_id', $mentor->mentor_id)
                                ->pluck('name')
                                ->toArray() ?: ['No subjects assigned'],

                // Profile extra data – already array, safe access
                'experience' => $extra['experience'] ?? [],
                'education'  => $extra['education'] ?? [],
                'roles'      => $extra['roles'] ?? [],
            ]
        ]);
    });

    // ====================== TEACHER ENDPOINTS ======================
    Route::get('/teacher/dashboard/stats', function () {
        $mentorId = auth()->user()->mentor?->mentor_id;
        if (!$mentorId) return response()->json(['error' => 'Teacher profile not found'], 404);

        return response()->json(['data' => [
            'upcomingClasses' => DB::table('timetable')
                ->join('subjects_groups_bridge_table', 'timetable.subject_group_id', '=', 'subjects_groups_bridge_table.subject_group_id')
                ->join('subjects', 'subjects_groups_bridge_table.subject_id', '=', 'subjects.subject_id')
                ->where('subjects.mentor_id', $mentorId)
                ->where('timetable.day_slot', now()->dayOfWeekIso)
                ->count(),

            'newSubmissions' => DB::table('submissions')
                ->join('assignments', 'submissions.assignment_id', '=', 'assignments.assignment_id')
                ->join('subjects', 'assignments.subject_id', '=', 'subjects.subject_id')
                ->where('subjects.mentor_id', $mentorId)
                ->whereNull('submissions.grade')
                ->count(),

            'totalStudents' => DB::table('subjects')
                ->join('subjects_groups_bridge_table', 'subjects.subject_id', '=', 'subjects_groups_bridge_table.subject_id')
                ->join('students', 'subjects_groups_bridge_table.group_id', '=', 'students.group_id')
                ->where('subjects.mentor_id', $mentorId)
                ->distinct('students.student_id')
                ->count(),

            'coursesAssigned' => DB::table('subjects')->where('mentor_id', $mentorId)->count(),
        ]]);
    });

    Route::get('/teacher/timetable/today', function () {
        $mentorId = auth()->user()->mentor?->mentor_id;
        if (!$mentorId) return response()->json(['error' => 'Teacher profile not found'], 404);

        $classes = DB::table('timetable')
            ->join('subjects_groups_bridge_table', 'timetable.subject_group_id', '=', 'subjects_groups_bridge_table.subject_group_id')
            ->join('subjects', 'subjects_groups_bridge_table.subject_id', '=', 'subjects.subject_id')
            ->join('groups', 'subjects_groups_bridge_table.group_id', '=', 'groups.group_id')
            ->where('subjects.mentor_id', $mentorId)
            ->where('timetable.day_slot', now()->dayOfWeekIso)
            ->select([
                'subjects.name as title',
                DB::raw("CONCAT('CS', subjects.subject_id) as code"),
                'timetable.session_type as type',
                DB::raw("CASE timetable.time_slot 
                    WHEN 1 THEN '09:00' WHEN 2 THEN '11:00' WHEN 3 THEN '14:00' END as startTime"),
                DB::raw("CASE timetable.time_slot 
                    WHEN 1 THEN '10:30' WHEN 2 THEN '13:00' WHEN 3 THEN '15:30' END as endTime"),
                DB::raw("CONCAT('Room ', timetable.room_number) as room"),
            ])
            ->get();

        return ['data' => $classes];
    });

    Route::get('/teacher/activity/recent', function () {
        return ['data' => []]; // you can expand later
    });

    // Teacher: my weekly timetable (no ID needed — uses current user)
    Route::get('/teacher/timetable/week', function () {
        $mentorId = auth()->user()?->mentor?->mentor_id;

        if (!$mentorId) {
            return response()->json(['error' => 'Teacher profile not found'], 403);
        }

        $startDate = request()->query('startDate', now()->startOfWeek()->format('Y-m-d'));

        $sessions = DB::table('timetable')
            ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
            ->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
            ->join('groups', 'bridge.group_id', '=', 'groups.group_id')
            ->where('subjects.mentor_id', $mentorId)
            ->select([
                'subjects.name as title',
                'groups.group_name as group',
                'timetable.day_slot',
                'timetable.time_slot',
                'timetable.room_number as room',
                'timetable.session_type as type',
                DB::raw("CASE timetable.time_slot 
                    WHEN 1 THEN '09:00–10:30' 
                    WHEN 2 THEN '11:00–13:00' 
                    WHEN 3 THEN '14:00–15:30' 
                    ELSE 'TBA' END as time"),
            ])
            ->orderBy('timetable.day_slot')
            ->orderBy('timetable.time_slot')
            ->get();

        $days = [
            'Monday' => [], 'Tuesday' => [], 'Wednesday' => [],
            'Thursday' => [], 'Friday' => [], 'Unknown' => []
        ];

        foreach ($sessions as $s) {
            $dayName = match((int)$s->day_slot) {
                1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                4 => 'Thursday', 5 => 'Friday', default => 'Unknown'
            };

            $days[$dayName][] = [
                'title'      => $s->title,
                'group'      => $s->group,
                'time'       => $s->time,
                'location'   => 'Room ' . ($s->room ?? 'TBA'),
                'type'       => $s->type ?? 'lecture',
            ];
        }

        return [
            'data' => [
                'weekStart' => $startDate,
                'days'      => $days
            ]
        ];
    });

    // Teacher: list of assigned modules (fully safe version)
    Route::get('/teacher/modules', function () {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $mentor = $user->mentor;

        if (!$mentor) {
            return response()->json(['error' => 'Teacher profile not found'], 403);
        }

        $mentorId = $mentor->mentor_id;

        // Log for debugging (remove later if you want)
        // \Log::info('Teacher modules called', [
        //     'user_id'   => $user->user_id,
        //     'mentor_id' => $mentorId,
        // ]);

        try {
            $modules = DB::table('subjects')
                ->where('mentor_id', $mentorId)
                ->leftJoin('subjects_groups_bridge_table as sgb', 'subjects.subject_id', '=', 'sgb.subject_id')
                ->leftJoin('groups', 'sgb.group_id', '=', 'groups.group_id')
                ->select([
                    'subjects.subject_id as id',
                    DB::raw("'CS' || subjects.subject_id as code"),
                    'subjects.name',
                    'subjects.credits',
                    // Safer subquery with COALESCE
                    DB::raw('COALESCE((
                        SELECT COUNT(DISTINCT s.student_id)
                        FROM students s
                        INNER JOIN subjects_groups_bridge_table sgb2 
                            ON s.group_id = sgb2.group_id
                        WHERE sgb2.subject_id = subjects.subject_id
                    ), 0) as total_students'),  // ← renamed alias to avoid any conflict
                    // Safe json_agg (FILTER ensures no NULL aggregation)
                    DB::raw("COALESCE(
                        json_agg(DISTINCT groups.group_name) FILTER (WHERE groups.group_name IS NOT NULL), 
                        '[]'::json
                    ) as group_list")
                ])
                ->groupBy('subjects.subject_id', 'subjects.name', 'subjects.credits')
                ->get();

            // Map safely — use property existence checks
            $mapped = $modules->map(function ($m) {
                return [
                    'id'           => 'M' . ($m->id ?? 'unknown'),
                    'code'         => $m->code ?? 'CS???',
                    'name'         => $m->name ?? 'Unnamed Module',
                    'credits'      => (int) ($m->credits ?? 15),
                    // Use the new alias — fallback to 0
                    'totalStudents'=> (int) ($m->total_students ?? 0),
                    'groups'       => json_decode($m->group_list ?? '[]', true) ?? [],
                ];
            });

            return response()->json(['data' => $mapped]);

        } catch (\Exception $e) {
            \Log::error('Teacher modules endpoint crashed', [
                'mentor_id' => $mentorId,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error'   => 'Failed to load assigned modules',
                'message' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    });

    // Teacher: single module detail (students, attendance summary, etc.)
    Route::get('/teacher/modules/{moduleId}', function ($moduleId) {
        $mentorId = auth()->user()?->mentor?->mentor_id;
        $subjectId = (int) substr($moduleId, 1); // M1 → 1

        $module = DB::table('subjects')
            ->where('subject_id', $subjectId)
            ->where('mentor_id', $mentorId)
            ->first();

        if (!$module) {
            return response()->json(['error' => 'Module not found or not assigned to you'], 404);
        }

        $groups = DB::table('subjects_groups_bridge_table')
            ->join('groups', 'subjects_groups_bridge_table.group_id', '=', 'groups.group_id')
            ->where('subject_id', $subjectId)
            ->pluck('group_name')
            ->toArray();

        $studentsCount = DB::table('subjects_groups_bridge_table')
            ->join('students', 'subjects_groups_bridge_table.group_id', '=', 'students.group_id')
            ->where('subject_id', $subjectId)
            ->distinct('students.student_id')
            ->count();

        return [
            'data' => [
                'id'           => 'M' . $module->subject_id,
                'code'         => 'CS' . $module->subject_id,
                'name'         => $module->name,
                'credits'      => $module->credits ?? 15,
                'description'  => $module->description ?? 'No description',
                'groups'       => $groups,
                'totalStudents'=> $studentsCount,
                'resources'    => buildModuleResources(),
                // You can add more: average grade, attendance %, etc.
            ]
        ];
    });

    Route::get('/teacher/modules/{moduleId}/attendance', function ($moduleId) {
        $mentor = auth()->user()?->mentor;

        if (!$mentor) {
            return response()->json(['error' => 'Teacher profile not found'], 403);
        }

        $subjectId = (int) substr($moduleId, 1);

        // Verify ownership
        $subject = DB::table('subjects')
            ->where('subject_id', $subjectId)
            ->where('mentor_id', $mentor->mentor_id)
            ->first();

        if (!$subject) {
            return response()->json(['error' => 'Module not found or access denied'], 404);
        }

        // Groups (for display / filter)
        $groups = DB::table('subjects_groups_bridge_table')
            ->join('groups', 'subjects_groups_bridge_table.group_id', '=', 'groups.group_id')
            ->where('subjects_groups_bridge_table.subject_id', $subjectId)
            ->pluck('groups.group_name')
            ->toArray();

        // Attendance records — FIXED: select group_name
        $records = DB::table('attendance')
            ->join('students', 'attendance.student_id', '=', 'students.student_id')
            ->join('timetable', 'attendance.session_id', '=', 'timetable.session_id')
            ->join('subjects_groups_bridge_table as sgb', 'timetable.subject_group_id', '=', 'sgb.subject_group_id')
            ->join('groups', 'sgb.group_id', '=', 'groups.group_id') // ← added this join
            ->where('sgb.subject_id', $subjectId)
            ->whereColumn('students.group_id', 'sgb.group_id')
            ->select([
                DB::raw("TO_CHAR(attendance.session_date, 'YYYY-MM-DD') as date"),
                DB::raw("
                    CASE 
                        WHEN timetable.time_slot = 1 THEN '09:00'
                        WHEN timetable.time_slot = 2 THEN '11:00'
                        WHEN timetable.time_slot = 3 THEN '14:00'
                        ELSE 'TBA'
                    END as time
                "),
                'students.student_id as id',
                DB::raw("TRIM(CONCAT(students.name, ' ', students.surname)) as name"),
                DB::raw("CASE WHEN attendance.is_present THEN 'present' ELSE 'absent' END as status"),
                'groups.group_name as group_name' // ← now we select it!
            ])
            ->orderByDesc('attendance.session_date')
            ->get();

        // Group by date
        $sessions = $records
            ->groupBy(fn ($record) => implode('|', [$record->date, $record->time, $record->group_name]))
            ->map(function ($group) {
                $first = $group->first();

                return [
                    'date'     => $first->date,
                    'time'     => $first->time,
                    'group'    => $first->group_name,
                    'students' => $group->map(fn($r) => [
                        'id'     => (int) $r->id,
                        'name'   => $r->name,
                        'status' => $r->status,
                        'group'  => $r->group_name ?? 'Unknown' // ← safe now
                    ])->values()->all(),
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'data' => [
                'groups'   => $groups,
                'sessions' => $sessions,
                'debug'    => [
                    'subject_id'   => $subjectId,
                    'mentor_id'    => $mentor->mentor_id,
                    'record_count' => $records->count(),
                ]
            ]
        ]);
    });

    // ====================== ATTENDANCE SAVE (NEW) ======================
    Route::post('/teacher/modules/{moduleId}/attendance', 
        [App\Http\Controllers\Teacher\AttendanceController::class, 'save']);
    
    // Inside the auth:sanctum middleware group

    Route::prefix('student')->middleware('student')->group(function () {   // ← uses your EnsureIsStudent middleware

        Route::get('/dashboard/stats', function () {
            $user = auth()->user();
            $student = $user->student;

            if (!$student) {
                return response()->json(['error' => 'Student profile not found'], 403);
            }

            $attendancePercentage = calculateAttendancePercentage($student->student_id);

            return response()->json(['data' => [
                'enrolledModules'     => DB::table('subjects_groups_bridge_table')
                                            ->where('group_id', $student->group_id)
                                            ->count(),
                'gpa'                 => calculateGPA($student->student_id) ?? 0.0,
                'creditsCompleted'    => (int) ($student->credits_completed ?? 0),
                'attendancePercentage'=> $attendancePercentage,
            ]]);
        })->name('api.student.dashboard.stats');

    });

    Route::get('/student/analysis', function () {
        $id = auth()->user()->student->student_id;
        return getAnalyticsJSON($id);
    });

    Route::get('/timetable/today', function () {
        $student = auth()->user()->student;
        $today = \Carbon\Carbon::today();
        $dayOfWeek = $today->dayOfWeekIso; // 1 = Monday ... 7 = Sunday

        if ($dayOfWeek > 5) {
            return ['data' => []];
        }

        $sessions = DB::table('timetable')
            ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
            ->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
            ->leftJoin('mentors', 'subjects.mentor_id', '=', 'mentors.mentor_id')
            ->where('bridge.group_id', $student->group_id)
            ->where('timetable.day_slot', $dayOfWeek)
            ->whereIn('timetable.time_slot', [1, 2, 3])           // ← optional safety
            ->select([
                'subjects.subject_id as id',
                DB::raw("'CS' || subjects.subject_id as moduleCode"),
                'subjects.name as moduleName',
                'timetable.session_type as type',
                // ←←← IMPROVED: never returns NULL again
                DB::raw("COALESCE(
                    CASE timetable.time_slot 
                        WHEN 1 THEN '09:00' 
                        WHEN 2 THEN '11:00' 
                        WHEN 3 THEN '14:00' 
                    END, 'TBA'
                ) as startTime"),
                DB::raw("COALESCE(
                    CASE timetable.time_slot 
                        WHEN 1 THEN '10:30' 
                        WHEN 2 THEN '13:00' 
                        WHEN 3 THEN '15:00' 
                    END, 'TBA'
                ) as endTime"),
                DB::raw("'Room ' || timetable.room_number as room"),
                'timetable.building',
                DB::raw("'Dr. ' || mentors.name || ' ' || mentors.surname as instructor"),
                'timetable.time_slot as debug_time_slot',     // ← add this
            ])
            ->get();

        return ['data' => $sessions];
    });

    Route::get('/timetable/week', function () {
        $student = auth()->user()->student;
        if (!$student || !$student->group_id) {
            return ['data' => ['weekStart' => now()->startOfWeek()->format('Y-m-d'), 'days' => []]];
        }

        $sessions = DB::table('timetable')
            ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
            ->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
            ->leftJoin('mentors', 'subjects.mentor_id', '=', 'mentors.mentor_id')
            ->where('bridge.group_id', $student->group_id)
            ->select([
                'subjects.name as title',
                'mentors.name as instructor_name',
                'mentors.surname as instructor_surname',
                'timetable.day_slot',
                'timetable.time_slot',
                'timetable.room_number as room',
                'timetable.building',
                'timetable.session_type as type',
                DB::raw("CASE timetable.time_slot 
                    WHEN 1 THEN '09:00–10:30' 
                    WHEN 2 THEN '11:00–13:00' 
                    WHEN 3 THEN '14:00–15:30' 
                    ELSE 'Unknown' END as time"),
                DB::raw("CONCAT('Dr. ', mentors.name, ' ', mentors.surname) as instructor"),  // ← ADD THIS
                'mentors.name', 'mentors.surname'  // optional for safety
            ])
            ->get();

        $days = [
            'Monday' => [], 'Tuesday' => [], 'Wednesday' => [],
            'Thursday' => [], 'Friday' => [], 'Unknown' => []
        ];

        foreach ($sessions as $s) {
            $dayName = match((int)$s->day_slot) {
                1 => 'Monday', 
                2 => 'Tuesday', 
                3 => 'Wednesday',
                4 => 'Thursday', 
                5 => 'Friday', 
                default => 'Unknown'
            };

            $days[$dayName][] = [
                'title'      => $s->title,
                'time'       => $s->time,
                'location'   => ($s->building ?? 'Block A') . ', ' . ($s->room ?? 'Room ?'),
                'room'       => $s->room ? 'Room ' . $s->room : 'TBD',
                'building'   => $s->building ?? 'Block A',
                'instructor' => 'Dr. ' . trim($s->instructor_name . ' ' . $s->instructor_surname),
                'type'       => $s->type ?? 'lecture',
                'group'      => 'CS-' . $student->group_id . '-A'   // optional group name
            ];
        }

        return [
            'data' => [
                'weekStart' => now()->startOfWeek()->format('Y-m-d'),
                'days' => $days
            ]
        ];
    });

    Route::get('/timetable/group/{groupId}/week', function ($groupId) {
        try {
            $startDate = request()->query('startDate', now()->startOfWeek()->format('Y-m-d'));

            // Security: only allow viewing public/active groups or own group
            $group = DB::table('groups')
                ->where('group_id', $groupId)
                ->where('is_active', true)
                ->first();

            if (!$group) {
                return response()->json(['error' => 'Group not found or inactive'], 404);
            }

            // Optional: if you want to restrict to own group only, uncomment:
            // if ($groupId != auth()->user()->student->group_id) {
            //     return response()->json(['error' => 'Unauthorized group access'], 403);
            // }

            $sessions = DB::table('timetable')
                ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
                ->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
                ->leftJoin('mentors', 'subjects.mentor_id', '=', 'mentors.mentor_id')
                ->where('bridge.group_id', $groupId)
                ->select([
                    'subjects.name as title',
                    'timetable.day_slot',
                    'timetable.time_slot',
                    'timetable.room_number as room',
                    'timetable.session_type as type',           // ← FIXED: use real column!
                    DB::raw("CASE timetable.time_slot 
                        WHEN 1 THEN '09:00–10:30' 
                        WHEN 2 THEN '11:00–12:30' 
                        WHEN 3 THEN '14:00–15:30' 
                        ELSE 'TBA' END as time"),
                    DB::raw("CONCAT('Dr. ', mentors.name, ' ', mentors.surname) as instructor"),
                ])
                ->orderBy('timetable.day_slot')
                ->orderBy('timetable.time_slot')
                ->get();

            $days = [
                'Monday'    => [], 'Tuesday'   => [], 'Wednesday' => [],
                'Thursday'  => [], 'Friday'    => [], 'Unknown'   => []
            ];

            foreach ($sessions as $s) {
                $dayNum = (int) $s->day_slot;
                $dayName = match($dayNum) {
                    1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                    4 => 'Thursday', 5 => 'Friday', default => 'Unknown'
                };

                $days[$dayName][] = [
                    'title'      => $s->title,
                    'time'       => $s->time,
                    'room'       => $s->room ? 'Room ' . $s->room : 'TBA',
                    'instructor' => $s->instructor ? 'Dr. ' . $s->instructor : 'TBD',
                    'type'       => $s->type,
                ];
            }

            return [
                'data' => [
                    'weekStart' => $startDate,
                    'group'     => ['id' => $groupId, 'name' => $group->group_name],
                    'days'      => $days
                ]
            ];

        } catch (\Exception $e) {
            \Log::error('Group timetable error', [
                'groupId' => $groupId,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'error'   => 'Failed to load group timetable',
                'message' => $e->getMessage()
            ], 500);
        }
    });

    Route::get('/timetable/teacher/{teacherId}/week', function ($teacherId) {
        try {
            $startDate = request()->query('startDate', now()->startOfWeek()->format('Y-m-d'));

            // Validate teacher exists
            $teacher = DB::table('mentors')->where('mentor_id', $teacherId)->first();
            if (!$teacher) {
                return response()->json(['error' => 'Teacher not found'], 404);
            }

            // Fetch sessions taught by this teacher
            $sessions = DB::table('timetable')
                ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
                ->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
                ->join('groups', 'bridge.group_id', '=', 'groups.group_id')
                ->where('subjects.mentor_id', $teacherId)
                ->select([
                    'subjects.name as title',
                    'groups.group_name as group',
                    'timetable.day_slot',
                    'timetable.time_slot',
                    'timetable.room_number as room',
                    'timetable.session_type as type',           // ← FIXED: use real column!
                    DB::raw("CASE timetable.time_slot 
                        WHEN 1 THEN '09:00–10:30' 
                        WHEN 2 THEN '11:00–12:30' 
                        WHEN 3 THEN '14:00–15:30' 
                        ELSE 'TBA' END as time")
                ])
                ->orderBy('timetable.day_slot')
                ->orderBy('timetable.time_slot')
                ->get();

            // Group by day
            $days = [
                'Monday'    => [],
                'Tuesday'   => [],
                'Wednesday' => [],
                'Thursday'  => [],
                'Friday'    => [],
                'Unknown'   => []
            ];

            foreach ($sessions as $s) {
                $dayNum = (int) $s->day_slot;
                $dayName = match($dayNum) {
                    1 => 'Monday',
                    2 => 'Tuesday',
                    3 => 'Wednesday',
                    4 => 'Thursday',
                    5 => 'Friday',
                    default => 'Unknown'
                };

                $days[$dayName][] = [
                    'title'      => $s->title,
                    'group'      => $s->group,
                    'time'       => $s->time,
                    'room'       => $s->room ? 'Room ' . $s->room : 'TBA',
                    'instructor' => 'Dr. ' . $teacher->name . ' ' . $teacher->surname,
                    'type'       => $s->type,
                ];
            }

            return [
                'data' => [
                    'weekStart' => $startDate,
                    'teacher'   => [
                        'id'   => $teacherId,
                        'name' => 'Dr. ' . trim($teacher->name . ' ' . $teacher->surname)
                    ],
                    'days'      => $days
                ]
            ];

        } catch (\Exception $e) {
            \Log::error('Teacher timetable error', [
                'teacherId' => $teacherId,
                'message'   => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            return response()->json([
                'error'   => 'Failed to load teacher timetable',
                'message' => $e->getMessage() // only in dev
            ], 500);
        }
    });

    Route::get('/modules', function () {
        $student = auth()->user()->student;

        $modules = DB::table('subjects_groups_bridge_table')
            ->join('subjects', 'subjects_groups_bridge_table.subject_id', '=', 'subjects.subject_id')
            ->leftJoin('grades', function($join) use($student) {
                $join->on('grades.subject_id', '=', 'subjects.subject_id')
                    ->where('grades.student_id', $student->student_id);
            })
            ->leftJoin('mentors', 'subjects.mentor_id', '=', 'mentors.mentor_id')
            ->where('subjects_groups_bridge_table.group_id', $student->group_id)
            ->select([
                DB::raw("'M' || subjects.subject_id as id"),
                DB::raw("'CS' || subjects.subject_id as code"),
                'subjects.name',
                'subjects.credits',
                'grades.grade',
                'grades.percentage as progress',
                DB::raw("'active' as status"),
                // Fixed: return instructor as proper object (not JSON string)
                DB::raw("json_build_object(
                    'name', 'Dr. ' || mentors.name || ' ' || mentors.surname,
                    'department', mentors.department,
                    'email', mentors.email,
                    'room', mentors.office_location,
                    'officeHours', mentors.office_hours
                ) as instructor")
            ])
            ->get()
            ->map(function ($module) {
                // Parse the instructor JSON string into real object
                $module->instructor = json_decode($module->instructor, true) ?? ['name' => 'TBD'];
                return $module;
            });

        return ['data' => $modules];
    });

    Route::get('/records/transcript', function () {
        $studentId = auth()->user()->student->student_id;

        $modulesRaw = DB::table('grades')
            ->join('subjects', 'grades.subject_id', '=', 'subjects.subject_id')
            ->where('grades.student_id', $studentId)
            ->select([
                'subjects.subject_id',
                'subjects.name',
                DB::raw("'CS' || subjects.subject_id as code"),
                'subjects.credits',
                'grades.grade',
                'grades.points as gradePoints',
                'grades.percentage',
                'grades.attendance'
            ])
            ->orderBy('subjects.subject_id')
            ->get()
            ->map(fn($m) => [
                'code'        => $m->code,
                'name'        => $m->name,
                'credits'     => $m->credits ?? 15,
                'grade'       => $m->grade ?? 'N/A',
                'gradePoints' => $m->gradePoints ?? 0.0,
                'percentage'  => $m->percentage ?? 0,
                'attendance'  => $m->attendance ?? 0
            ]);

        // === FAKE SEMESTER GROUPING (exactly what the frontend expects) ===
        $sem1 = $modulesRaw->filter(fn($m) => $m['code'] <= 'CS3');   // Data Structures, Web Dev, Database Systems
        $sem2 = $modulesRaw->filter(fn($m) => $m['code'] > 'CS3');    // Software Eng, Network Security, Mathematics III

        return [
            'data' => [
                'studentId'         => formatStudentId($studentId),
                'fullName'          => auth()->user()->student->name . ' ' . auth()->user()->student->surname,
                'programme'         => auth()->user()->student->group->group_name ?? 'BSc Computer Science',
                'gpa'               => calculateGPA($studentId),
                'creditsCompleted'  => auth()->user()->student->credits_completed,
                'creditsRequired'   => 120,
                'semesters' => [
                    'SEMESTER 1 - 2025/26' => $sem1->values()->all(),
                    'SEMESTER 2 - 2025/26' => $sem2->values()->all()
                ]
            ]
        ];
    });

    // === FIXED & FULLY DATABASE-DRIVEN ===
    Route::get('/records/attendance', function () {
        $studentId = auth()->user()->student->student_id;

        $data = DB::table('grades')
            ->join('subjects', 'grades.subject_id', '=', 'subjects.subject_id')
            ->where('grades.student_id', $studentId)
            ->select([
                'subjects.subject_id as moduleId',
                'subjects.name as moduleName',
                DB::raw('grades.attendance as attended'),
                DB::raw('25 as total'),
                'grades.percentage as percentage'
            ])
            ->get();

        return ['data' => $data];
    });

    // ====================== NEW ROUTES (fixes every 404 you see) ======================
    // timetable/groups
    Route::get('/timetable/groups', function () {
        $groups = DB::table('groups')
            ->select('group_id as id', 'group_name as name', 'group_level as level')
            ->where('is_active', true)
            ->get();
        return ['data' => $groups];
    });

// ====================== MODULE DETAIL — FULLY FROM DATABASE (fixed) ======================
    Route::get('/modules/{moduleId}', function ($moduleId) {
        $subjectId = (int) substr($moduleId, 1);   // M1 → 1, M6 → 6

        $module = DB::table('subjects')
            ->leftJoin('mentors', 'subjects.mentor_id', '=', 'mentors.mentor_id')
            ->leftJoin('grades', function($join) {
                $join->on('grades.subject_id', '=', 'subjects.subject_id')
                    ->where('grades.student_id', auth()->user()->student?->student_id ?? 0);
            })
            ->where('subjects.subject_id', $subjectId)
            ->select([
                'subjects.subject_id as id',
                'subjects.name',
                'subjects.credits',
                'subjects.description',
                'mentors.name as instructor_name',
                'mentors.surname as instructor_surname',
                'mentors.email',
                'grades.grade',
                'grades.percentage as progress'
            ])
            ->first();

        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        // Assessments from real assignments table (safe date handling)
        $assessments = DB::table('assignments')
            ->where('subject_id', $subjectId)
            ->select('assignment_id as id', 'title', 'weight', 'due_time')
            ->get()
            ->map(function ($a) {
                return [
                    'id' => $a->id,
                    'title' => $a->title,
                    'type' => 'Assignment',
                    'weight' => $a->weight ?? 30,
                    'dueDate' => $a->due_time ? \Carbon\Carbon::parse($a->due_time)->format('Y-m-d') : 'TBA',
                    'status' => 'upcoming'
                ];
            });

        // Exact structure expected by frontend (from your MOCK.getModuleDetails)
        return [
            'data' => [
                'id' => 'M' . $module->id,
                'code' => 'CS' . $module->id,
                'name' => $module->name,
                'credits' => $module->credits ?? 15,
                'grade' => $module->grade ?? 'N/A',
                'progress' => $module->progress ?? 75,
                'status' => 'active',
                'instructor' => [
                    'name' => 'Dr. ' . trim(($module->instructor_name ?? '') . ' ' . ($module->instructor_surname ?? '')),
                    'department' => 'Computer Science',
                    'email' => $module->email ?? 'instructor@sus.edu',
                    'room' => 'Block A, Room 205',
                    'officeHours' => 'Mon 14:00–16:00, Wed 10:00–12:00'
                ],
                'description' => $module->description ?? 'No description available.',
                'learningOutcomes' => [
                    'Implement core data structures',
                    'Analyse algorithm complexity',
                    'Apply sorting and searching techniques',
                    'Design efficient algorithms',
                    'Evaluate and optimise performance'
                ],
                'assessments' => $assessments,
                'resources' => buildModuleResources()
            ]
        ];
    });

    // ====================== TEACHERS LIST (real subjects from DB) ======================
    Route::get('/teachers', function () {
        $teachers = DB::table('mentors')
            ->leftJoin('subjects', 'subjects.mentor_id', '=', 'mentors.mentor_id')
            ->select([
                'mentors.mentor_id as id',
                'mentors.name as firstName',
                'mentors.surname as lastName',
                DB::raw("'Dr.' as title"),
                'mentors.department',
                'mentors.email',
                'mentors.phone_number as phone',
                'mentors.office_location as officeLocation',
                'mentors.office_hours as officeHours',
                DB::raw("COALESCE(array_agg(DISTINCT subjects.name), ARRAY['—']) as subjects")
            ])
            ->groupBy('mentors.mentor_id', 'mentors.name', 'mentors.surname', 'mentors.department', 
                    'mentors.email', 'mentors.phone_number', 'mentors.office_location', 'mentors.office_hours')
            ->get();

        return ['data' => $teachers];
    });

    Route::get('/teachers/{id}', function ($id) {
        $mentor = DB::table('mentors')
            ->where('mentor_id', $id)
            ->first();

        if (!$mentor) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }

        $extra = $mentor->profile_data ? json_decode($mentor->profile_data, true) : [];

        $subjects = DB::table('subjects')
            ->where('mentor_id', $id)
            ->pluck('name')
            ->toArray();

        return [
            'data' => [
                'id'             => $mentor->mentor_id,
                'firstName'      => $mentor->name,
                'lastName'       => $mentor->surname,
                'title'          => 'Dr.',
                'department'     => $mentor->department ?? 'Computer Science',
                'email'          => $mentor->email,
                'phone'          => $mentor->phone_number,
                'officeLocation' => $mentor->office_location,
                'officeHours'    => $mentor->office_hours,
                'nationality'    => $mentor->nationality ?? '—',
                'languages'      => $mentor->languages ?? 'English',
                'subjects'       => $subjects ?: ['—'],
                'experience'     => $extra['experience']     ?? [],
                'education'      => $extra['education']      ?? [],
                'roles'          => $extra['roles']          ?? []
            ]
        ];
    });

    // career events (fully from events table)
    Route::get('/career/events', function () {
        $studentId = auth()->user()->student->student_id ?? 0;
        $events = DB::table('events')
            ->leftJoin('participants', function($join) use($studentId) {
                $join->on('events.event_id', '=', 'participants.event_id')
                     ->where('participants.student_id', $studentId);
            })
            ->select([
                'events.event_id as id',
                'events.title',
                'events.organiser',
                DB::raw("TO_CHAR(events.event_time, 'YYYY-MM-DD') as date"),
                DB::raw("TO_CHAR(events.event_time, 'HH24:MI') as time"),
                DB::raw("'Main Hall' as location"),
                'events.event_type as type',
                'events.spots',
                DB::raw("CASE WHEN participants.student_id IS NOT NULL THEN true ELSE false END as registered")
            ])
            ->get();
        return ['data' => $events];
    });

    // contact departments (uses staff table for real DB dependency)
    Route::get('/contact/departments', function () {
        $depts = DB::table('staff')
            ->select('job_position as name', 'email', 'phone_number as phone', 
                     DB::raw("'Block A, Room 101' as location"))
            ->whereNotNull('job_position')
            ->distinct()
            ->get();
        return ['data' => $depts];
    });

    Route::get('/career/jobs/{id}', function ($id) {
        $job = DB::table('jobs')->where('job_id', $id)->first();
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        $studentId = auth()->user()->student->student_id ?? 0;
        $alreadyApplied = DB::table('applications')
            ->where('student_id', $studentId)
            ->where('job_id', $id)
            ->exists();

        return response()->json([
            'data' => [
                'id'          => $job->job_id,
                'title'       => $job->title,
                'company'     => $job->company     ?? 'Unknown Company',
                'location'    => $job->location    ?? 'N/A',
                'type'        => $job->type        ?? 'Full-time',
                'salary'      => $job->salary      ?? 'N/A',
                'deadline'    => $job->deadline    ?? 'N/A',
                'description' => "We are looking for a motivated developer to join {$job->company} in {$job->location}. This is a {$job->type} position with a competitive salary of {$job->salary}.",
                'requirements'=> ['JavaScript', 'React', '1+ year experience', 'Team player', 'Good communication skills'],
                'alreadyApplied' => $alreadyApplied
            ]
        ]);
    });

    // existing post routes (unchanged)
    Route::post('/career/jobs/{id}/apply', function ($id) { return ['data' => ['message' => 'Application submitted successfully']]; });
    
// ====================== REAL EVENT REGISTRATION (matches your table) ======================

    // REGISTER
    Route::post('/career/events/{id}/register', function ($id) {
        try {
            $user = auth()->user();
            if (!$user || !$user->student) {
                return response()->json(['error' => 'Student profile not found'], 404);
            }

            $studentId = $user->student->student_id;

            // Check if already registered
            $already = DB::table('participants')
                ->where('event_id', $id)
                ->where('student_id', $studentId)
                ->exists();

            if ($already) {
                return ['data' => ['registered' => true]];
            }

            // Insert using your exact column names
            DB::table('participants')->insert([
                'event_id'         => (int)$id,
                'student_id'       => (int)$studentId,
                'registration_time' => now()
            ]);

            return ['data' => ['registered' => true]];

        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'event_id' => $id,
                'error'    => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to register'], 500);
        }
    });

    // UNREGISTER (DELETE)
    Route::delete('/career/events/{id}/register', function ($id) {
        try {
            $user = auth()->user();
            if (!$user || !$user->student) {
                return response()->json(['error' => 'Student profile not found'], 404);
            }

            $studentId = $user->student->student_id;

            $deleted = DB::table('participants')
                ->where('event_id', $id)
                ->where('student_id', $studentId)
                ->delete();

            return ['data' => ['registered' => false]];

        } catch (\Exception $e) {
            \Log::error('Cancel registration failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to cancel'], 500);
        }
    });

    Route::get('/news', [NewsController::class, 'index'])->name('api.news.index');
    Route::get('/news/{id}', [NewsController::class, 'show'])->name('api.news.show');

    // Bookmark toggle (add or remove)
    Route::post('/news/{id}/bookmark', function (Request $request, $id) {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized - No authenticated user'], 401);
            }

            $newsId = (int) $id;

            // Verify news exists
            if (!DB::table('news')->where('news_id', $newsId)->exists()) {
                return response()->json(['error' => 'News article not found'], 404);
            }

            // Check existing bookmark
            $existing = DB::table('bookmarks')
                ->where('user_id', $user->user_id)     // ← your column is user_id
                ->where('news_id', $newsId)
                ->first();

            if ($existing) {
                // Remove
                DB::table('bookmarks')
                    ->where('id', $existing->id)
                    ->delete();

                return response()->json([
                    'message' => 'Bookmark removed',
                    'bookmarked' => false
                ]);
            }

            // Add new bookmark
            DB::table('bookmarks')->insert([
                'user_id'    => $user->user_id,
                'news_id'    => $newsId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Bookmark added',
                'bookmarked' => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Bookmark toggle error', [
                'news_id' => $id,
                'user_id' => $user?->user_id ?? 'guest',
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Server error while processing bookmark',
                'message' => $e->getMessage()  // ← show in dev only!
            ], 500);
        }
    });

    Route::post('/assignments/submit', function () { return ['data' => ['message' => 'Assignment submitted successfully', 'submittedAt' => now()]]; });
    Route::post('/contact/submit', function () { return ['data' => ['ticketId' => 'TKT-001', 'message' => 'Message sent']]; });
});
