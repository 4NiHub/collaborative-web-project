<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;

// =============================================
// PUBLIC ROUTES
// =============================================

Route::post('/auth/login', function () {
    // Simple Sanctum login example (you can expand with proper validation)
    $credentials = request()->only('email', 'password');

    if (auth()->attempt($credentials)) {
        $user = auth()->user();
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'data' => [
                'token' => $token,
                'student' => $user->student ? [
                    'id' => $user->student->student_id,
                    'firstName' => $user->student->name,
                    'lastName' => $user->student->surname,
                    'email' => $user->email,
                    'studentId' => $user->student->student_id,
                    'programme' => $user->student->group->group_name ?? 'BSc Computer Science',
                    'year' => $user->student->entry_year,
                    'avatar' => null
                ] : null
            ]
        ]);
    }
    return response()->json(['error' => 'Invalid credentials'], 401);
});

// Career jobs (exact MOCK structure)
Route::get('/career/jobs', function () {
    $jobs = DB::table('jobs')
        ->where('status', 'active')
        ->orderByDesc('creation_time')
        ->get()
        ->map(function ($job) {
            // Parse the body we inserted earlier
            $info = explode("\n", $job->body);
            return [
                'id' => $job->job_id,
                'title' => $job->title,
                'company' => str_contains($info[0], 'TechCorp') ? 'TechCorp' : 
                             (str_contains($info[0], 'DataViz') ? 'DataViz Ltd' : 'CreativeHub'),
                'location' => str_contains($job->body, 'London') ? 'London' : 'Remote',
                'type' => str_contains($job->body, 'Full-time') ? 'Full-time' : 
                          (str_contains($job->body, 'Internship') ? 'Internship' : 'Part-time'),
                'salary' => str_contains($job->body, '£35,000') ? '£35,000' : 
                            (str_contains($job->body, '£800') ? '£800/mo' : '£20/hr'),
                'deadline' => str_contains($job->body, '2026-03-15') ? '2026-03-15' : 
                              (str_contains($job->body, '2026-03-01') ? '2026-03-01' : '2026-03-20')
            ];
        });

    return ['data' => $jobs, 'meta' => ['page' => 1, 'limit' => 10, 'total' => $jobs->count(), 'totalPages' => 1]];
})->name('api.career.jobs');

// News (uses your controller - see below)
Route::get('/news', [NewsController::class, 'index'])->name('api.news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('api.news.show');

Route::get('/career/events', function () {
    $studentId = auth()->check() ? auth()->user()->student?->student_id : null;

    $events = DB::table('events')
        ->leftJoin('participants', function ($join) use ($studentId) {
            $join->on('events.event_id', '=', 'participants.event_id')
                 ->where('participants.student_id', $studentId);
        })
        ->select([
            'events.event_id as id',
            'events.title as title',           // use title (we already fixed header)
            'events.event_time',
            DB::raw("CASE WHEN participants.student_id IS NOT NULL THEN true ELSE false END as registered")
        ])
        ->orderByDesc('events.event_time')
        ->get()
        ->map(function ($e) {
            return [
                'id'         => $e->id,
                'title'      => $e->title ?? 'Untitled Event',
                'organiser'  => 'Career Centre',
                'date'       => substr($e->event_time ?? '', 0, 10),
                'time'       => substr($e->event_time ?? '', 11, 5),
                'location'   => 'Main Hall / Room 401 / Careers Hub',
                'type'       => 'Workshop / Fair',
                'spots'      => 200,
                'registered' => (bool)($e->registered ?? false)
            ];
        });

    return response()->json(['data' => $events]);
})->middleware('auth:sanctum');

// Job Detail - was missing
Route::get('/career/jobs/{id}', function ($id) {
    $job = DB::table('jobs')->where('job_id', $id)->first();

    if (!$job) {
        return response()->json(['error' => 'Job not found'], 404);
    }

    return response()->json([
        'data' => [
            'id'          => $job->job_id,
            'title'       => $job->title,
            'description' => $job->body,           // we stored details in body
            'company'     => 'TechCorp / DataViz Ltd / CreativeHub', // from your seed
            'location'    => str_contains($job->body, 'London') ? 'London' : 'Remote',
            'type'        => str_contains($job->body, 'Full-time') ? 'Full-time' : 'Part-time',
            'salary'      => str_contains($job->body, '£35,000') ? '£35,000' : '£800/mo',
            'deadline'    => '2026-03-15',
            'requirements'=> ['Experience in related field']
        ]
    ]);
})->middleware('auth:sanctum');

// Contact departments (exact MOCK structure)
Route::get('/contact/departments', function () {
    return [
        'data' => [
            ['id' => 1, 'name' => 'Student Services', 'email' => 'student.services@sus.edu', 'phone' => '+1 555-1000', 'location' => 'Block A, Room 101'],
            ['id' => 2, 'name' => 'Academic Registry', 'email' => 'registry@sus.edu', 'phone' => '+1 555-1001', 'location' => 'Block B, Room 201'],
            ['id' => 3, 'name' => 'IT Helpdesk', 'email' => 'it@sus.edu', 'phone' => '+1 555-1002', 'location' => 'Block C, Ground Floor'],
            ['id' => 4, 'name' => 'Finance Office', 'email' => 'finance@sus.edu', 'phone' => '+1 555-1003', 'location' => 'Block A, Room 102'],
            ['id' => 5, 'name' => 'Career Centre', 'email' => 'careers@sus.edu', 'phone' => '+1 555-1004', 'location' => 'Block D, Room 301']
        ]
    ];
});

// =============================================
// PROTECTED ROUTES (Sanctum)
// =============================================
Route::middleware('auth:sanctum')->group(function () {

    // Student Profile - EXACT MOCK.profile.data structure
    Route::get('/student/profile', function () {
        $user = auth()->user();
        $student = $user->student; // assumes Eloquent relation exists

        return [
            'data' => [
                'id' => 'STU001',
                'firstName' => $student->name,
                'lastName' => $student->surname,
                'email' => $user->email,
                'studentId' => $student->student_id,
                'programme' => $student->group->group_name ?? 'BSc Computer Science',
                'year' => $student->entry_year,
                'gpa' => 3.7,
                'creditsCompleted' => 90,
                'creditsRequired' => 120,
                'attendancePercentage' => 88,
                'avatar' => null
            ]
        ];
    });

    // Dashboard Stats - EXACT MOCK.dashboardStats.data
    Route::get('/student/dashboard/stats', function () {
        return [
            'data' => [
                'enrolledModules' => 6,
                'gpa' => 3.7,
                'creditsCompleted' => 90,
                'attendancePercentage' => 88,
                'upcomingDeadlines' => 3
            ]
        ];
    });

Route::get('/timetable/today', function () {
    try {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $student = $user->student;

        if (!$student || !$student->group_id) {
            return response()->json(['data' => []]);
        }

        $sessions = DB::table('timetable')
            ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
            ->join('groups', 'bridge.group_id', '=', 'groups.group_id')
            ->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
            ->leftJoin('mentors', 'subjects.mentor_id', '=', 'mentors.mentor_id')
            ->where('groups.group_id', $student->group_id)
            ->select([
                'subjects.name as module_name',           // ← lowercase + descriptive
                'mentors.name as instructor_name',
                DB::raw("CASE timetable.time_slot 
                    WHEN 1 THEN '09:00' WHEN 2 THEN '11:00' WHEN 3 THEN '14:00' ELSE '??:??' END as start_time"),
                DB::raw("CASE timetable.time_slot 
                    WHEN 1 THEN '10:30' WHEN 2 THEN '13:00' WHEN 3 THEN '15:00' ELSE '??:??' END as end_time"),
                'timetable.room_number as room_number',
                'timetable.time_slot'
            ])
            ->get();

        $formatted = $sessions->map(function ($s) {
            return [
                'id'         => $s->module_name ?? 'Unknown',
                'moduleCode' => 'CS' . rand(300, 399), // temporary placeholder
                'moduleName' => $s->module_name ?? 'Unknown Module',
                'type'       => 'Lecture',
                'startTime'  => $s->start_time ?? '??:??',
                'endTime'    => $s->end_time ?? '??:??',
                'room'       => $s->room_number ? 'Room ' . $s->room_number : 'TBD',
                'building'   => 'Block A',
                'instructor' => $s->instructor_name ? 'Dr. ' . $s->instructor_name : 'TBD'
            ];
        });

        return response()->json(['data' => $formatted]);

    } catch (\Exception $e) {
        \Log::error('Timetable crash', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine()
        ]);

        return response()->json([
            'error'   => 'Internal error',
            'message' => $e->getMessage()   // only visible in debug mode
        ], 500);
    }
})->middleware('auth:sanctum');

// GET /timetable/groups  ← Add this exact block
Route::get('/timetable/groups', function () {
    $groups = DB::table('groups')
        ->where('is_active', true)
        ->select('group_id as id', 'group_name as name')
        ->orderBy('group_name')
        ->get();

    return response()->json([
        'data' => $groups
    ]);
});

    // Modules (enrolled) - EXACT MOCK.modules.data
    Route::get('/modules', function () {
        $student = auth()->user()->student;
        $subjects = DB::table('subjects_groups_bridge_table')
            ->join('subjects', 'subjects_groups_bridge_table.subject_id', '=', 'subjects.subject_id')
            ->join('mentors', 'subjects.mentor_id', '=', 'mentors.mentor_id')
            ->where('subjects_groups_bridge_table.group_id', $student->group_id)
            ->select('subjects.subject_id as id', 'subjects.name')
            ->get()
            ->map(fn($s) => [
                'id' => 'M' . $s->id,
                'code' => 'CS' . rand(301, 305),
                'name' => $s->name,
                'credits' => 15,
                'grade' => 'A',
                'progress' => 75,
                'status' => 'active',
                'instructor' => ['name' => 'Dr. Johnson']
            ]);

        return ['data' => $subjects];
    });

    // Module Details
    Route::get('/modules/{id}', function ($id) {
        // Return exact mock structure for any module
        return [
            'data' => [
                'id' => $id,
                'code' => 'CS301',
                'name' => 'Data Structures',
                'credits' => 15,
                'grade' => 'A',
                'progress' => 75,
                'status' => 'active',
                'instructor' => [
                    'name' => 'Dr. Johnson',
                    'department' => 'Computer Science',
                    'email' => 's.johnson@sus.edu',
                    'room' => 'Block A, Room 205',
                    'officeHours' => 'Mon 14:00–16:00, Wed 10:00–12:00'
                ],
                'description' => 'In-depth study of core data structures and algorithmic design.',
                'learningOutcomes' => ['Implement core data structures', 'Analyse algorithm complexity', 'Apply sorting and searching techniques'],
                'assessments' => [
                    ['id' => 'A1', 'title' => 'Coursework 1', 'type' => 'Assignment', 'weight' => 30, 'dueDate' => '2026-03-01', 'status' => 'pending']
                ],
                'resources' => []
            ]
        ];
    });

    // Teachers list - EXACT MOCK.teachers.data
    Route::get('/teachers', function () {
        $teachers = DB::table('mentors')
            ->select('mentor_id as id', 'name', 'surname', 'email', 'phone_number')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'firstName' => $m->name,
                'lastName' => $m->surname,
                'title' => 'Dr.',
                'department' => 'Computer Science',
                'email' => $m->email,
                'phone' => $m->phone_number,
                'subjects' => ['Data Structures'],
                'officeLocation' => 'Block A, Room 205',
                'officeHours' => 'Mon 14:00–16:00, Wed 10:00–12:00'
            ]);

        return ['data' => $teachers];
    });

    // Teacher Profile
    Route::get('/teachers/{id}', function ($id) {
        return [
            'data' => [
                // ... same extra fields as in MOCK getTeacherProfile
                'bio' => 'Expert in computer science...',
                'nationality' => 'British',
                // ... (full mock object)
            ]
        ];
    });

    // Records Transcript
    Route::get('/records/transcript', function () {
        return [
            'data' => [
                'studentId' => '20240001',
                'fullName' => 'Alex Morgan',
                'programme' => 'BSc Computer Science',
                'gpa' => 3.7,
                'creditsCompleted' => 90,
                'creditsRequired' => 120,
                'semesters' => [
                    'Semester 1 – 2024/25' => [
                        ['code' => 'CS101', 'name' => 'Intro to Programming', 'credits' => 15, 'grade' => 'A']
                    ]
                ]
            ]
        ];
    });

// GET /records/attendance  ← ADD THIS
Route::get('/records/attendance', function () {
    $user = auth()->user();
    if (!$student = $user->student) {
        return response()->json(['data' => []]);
    }

    // Get enrolled modules + dummy attendance (you can make this real later)
    $attendance = DB::table('subjects_groups_bridge_table')
        ->join('subjects', 'subjects_groups_bridge_table.subject_id', '=', 'subjects.subject_id')
        ->where('subjects_groups_bridge_table.group_id', $student->group_id)
        ->select([
            'subjects.subject_id as moduleId',
            'subjects.name as moduleName',
            DB::raw('22 as attended'),           // dummy values
            DB::raw('25 as total'),
            DB::raw('88 as percentage')
        ])
        ->get()
        ->map(function ($item) {
            return [
                'moduleId'    => $item->moduleId,
                'moduleName'  => $item->moduleName,
                'attended'    => 22,
                'total'       => 25,
                'percentage'  => 88
            ];
        });

    return response()->json(['data' => $attendance]);
})->middleware('auth:sanctum');

    // Timetable Week (simple version - returns the normalized format your getWeeklyTimetable expects)
    Route::get('/timetable/week', function () {
        return [
            'data' => [
                'weekStart' => '2026-02-23',
                'days' => [
                    'Monday' => [
                        ['title' => 'Data Structures', 'time' => '09:00–10:30', 'location' => 'Room 301', 'instructor' => 'Dr. Johnson', 'type' => 'lecture']
                    ],
                    'Wednesday' => [
                        ['title' => 'Mathematics III', 'time' => '14:00–15:00', 'location' => 'Room 203', 'instructor' => 'Prof. Taylor', 'type' => 'lecture']
                    ]
                ]
            ]
        ];
    });

// Group weekly timetable
Route::get('/timetable/group/{groupId}/week', function ($groupId) {
    return response()->json([
        'data' => [
            'groupId' => $groupId,
            'weekStart' => now()->startOfWeek()->format('Y-m-d'),
            'days' => []   // you can expand later with real data
        ]
    ]);
});

// Teacher weekly timetable
Route::get('/timetable/teacher/{teacherId}/week', function ($teacherId) {
    return response()->json([
        'data' => [
            'teacherId' => $teacherId,
            'weekStart' => now()->startOfWeek()->format('Y-m-d'),
            'days' => []
        ]
    ]);
});

    // Career Job Apply (POST)
    Route::post('/career/jobs/{id}/apply', function ($id) {
        return ['data' => ['message' => 'Application submitted successfully']];
    });

    // Event Register / Cancel
    Route::post('/career/events/{id}/register', function ($id) { return ['data' => ['registered' => true]]; });
    Route::delete('/career/events/{id}/register', function ($id) { return ['data' => ['registered' => false]]; });

    // Assignment Submit
    Route::post('/assignments/submit', function () {
        return ['data' => ['message' => 'Assignment submitted successfully', 'submittedAt' => now()]];
    });

    // Contact Submit
    Route::post('/contact/submit', function () {
        return ['data' => ['ticketId' => 'TKT-001', 'message' => 'Message sent']];
    });
});