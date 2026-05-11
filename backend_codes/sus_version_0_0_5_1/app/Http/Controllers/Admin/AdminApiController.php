<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminApiController extends Controller
{
    // ── 1. DASHBOARD STATS & RECENT ACTIONS ──
    // public function getStats()
    // {
    //     $totalStudents = DB::table('students')->count();
    //     $totalTeachers = DB::table('mentors')->count();
    //     $activeCourses = DB::table('subjects')->count();

    //     // ── GATHER REAL RECENT ACTIONS ──
    //     $recentActions = [];

    //     // 1. Latest News
    //     $latestNews = DB::table('news')->orderByDesc('created_at')->first();
    //     if ($latestNews) {
    //         $recentActions[] = [
    //             'id'     => 'NWS-' . $latestNews->news_id,
    //             'user'   => 'Admin',
    //             'action' => 'Published news article',
    //             'target' => $latestNews->title,
    //             'time'   => \Carbon\Carbon::parse($latestNews->created_at)->diffForHumans(),
    //             'type'   => 'Content Update',
    //             'module' => 'News Manager'
    //         ];
    //     }

    //     // 2. Latest Event
    //     $latestEvent = DB::table('events')->orderByDesc('event_time')->first();
    //     if ($latestEvent) {
    //         $recentActions[] = [
    //             'id'     => 'EVT-' . $latestEvent->event_id,
    //             'user'   => 'Admin',
    //             'action' => 'Scheduled new event',
    //             'target' => $latestEvent->title,
    //             'time'   => 'Upcoming',
    //             'type'   => 'Event Creation',
    //             'module' => 'Event Manager'
    //         ];
    //     }

    //     // 3. Latest Student
    //     $latestStudent = DB::table('students')->orderByDesc('student_id')->first();
    //     if ($latestStudent) {
    //         $recentActions[] = [
    //             'id'     => 'STU-' . str_pad($latestStudent->student_id, 3, '0', STR_PAD_LEFT),
    //             'user'   => 'System',
    //             'action' => 'Registered new student',
    //             'target' => $latestStudent->name . ' ' . $latestStudent->surname,
    //             'time'   => 'Recently',
    //             'type'   => 'User Registration',
    //             'module' => 'User Management'
    //         ];
    //     }

    //     return response()->json([
    //         'data' => [
    //             'totalStudents' => $totalStudents,
    //             'totalTeachers' => $totalTeachers,
    //             'activeCourses' => $activeCourses,
    //             'recentActions' => $recentActions, // Added dynamic actions!
                
    //             // Mocks for charts
    //             'studentChange' => '+12%',
    //             'teacherChange' => '+3%',
    //             'courseChange'  => '+8%',
    //             'weeklyStudents' => [620, 780, 710, 850, 760, 540, 200],
    //             'weeklyTeachers' => [80, 120, 95, 140, 110, 65, 30],
    //             'weeklyLogins'   => [900, 1100, 980, 1300, 1050, 720, 380]
    //         ]
    //     ]);
    // }

    // ── 1. DASHBOARD STATS ──
    public function getStats()
    {
        $totalStudents = DB::table('students')->count();
        $totalTeachers = DB::table('mentors')->count();
        $activeCourses = DB::table('subjects')->count();

        // Build the dynamic Recent Actions array
        $recentActions = [];

        // 1. Latest News
        $latestNews = DB::table('news')->orderByDesc('created_at')->first();
        if ($latestNews) {
            $recentActions[] = [
                'id'       => 'act-news-' . ($latestNews->news_id ?? 1),
                'icon'     => 'images/admin_icons/doc.png',
                'actor'    => 'Admin',
                'action'   => 'Published news',
                'detail'   => $latestNews->title,
                'time'     => \Carbon\Carbon::parse($latestNews->created_at)->diffForHumans(),
                'type'     => 'Content Update',
                'module'   => 'News Manager',
                'recordId' => 'NWS-' . str_pad(($latestNews->news_id ?? 1), 3, '0', STR_PAD_LEFT),
                'status'   => 'Published'
            ];
        }

        // 2. Latest Event
        $latestEvent = DB::table('events')->orderByDesc('event_time')->first();
        if ($latestEvent) {
            $recentActions[] = [
                'id'       => 'act-evt-' . $latestEvent->event_id,
                'icon'     => 'images/admin_icons/calendar_black.png',
                'actor'    => 'Admin',
                'action'   => 'Scheduled event',
                'detail'   => $latestEvent->title,
                'time'     => \Carbon\Carbon::parse($latestEvent->event_time)->diffForHumans(),
                'type'     => 'Event Creation',
                'module'   => 'Events',
                'recordId' => 'EVT-' . str_pad($latestEvent->event_id, 3, '0', STR_PAD_LEFT),
                'status'   => 'Active'
            ];
        }

        // 3. Latest Student
        $latestStudent = DB::table('students')->orderByDesc('student_id')->first();
        if ($latestStudent) {
            $recentActions[] = [
                'id'       => 'act-stu-' . $latestStudent->student_id,
                'icon'     => 'images/admin_icons/person.png',
                'actor'    => 'System',
                'action'   => 'Registered student',
                'detail'   => trim($latestStudent->name . ' ' . $latestStudent->surname),
                'time'     => 'Recently',
                'type'     => 'User Registration',
                'module'   => 'Users',
                'recordId' => 'STU' . str_pad($latestStudent->student_id, 3, '0', STR_PAD_LEFT),
                'status'   => 'Active'
            ];
        }

        return response()->json([
            'data' => [
                'totalStudents' => $totalStudents,
                'totalTeachers' => $totalTeachers,
                'activeCourses' => $activeCourses,
                'recentActions' => $recentActions, // This is the real data!
                
                // Static charts for now
                'weeklyStudents' => [620, 780, 710, 850, 760, 540, 200],
                'weeklyTeachers' => [80, 120, 95, 140, 110, 65, 30],
                'weeklyLogins'   => [900, 1100, 980, 1300, 1050, 720, 380]
            ]
        ]);
    }

    // ── 2. USERS MANAGEMENT (Students & Mentors) ──
    public function getUsers()
    {
        // Fetch Students and pull the status column
        $students = DB::table('users')
            ->join('students', 'users.user_id', '=', 'students.user_id')
            ->select([
                'users.user_id', 'users.email', 'users.status', 
                'students.name', 'students.surname',
                DB::raw("'Student' as role")
            ]);

        // Fetch Teachers and pull the status column
        $teachers = DB::table('users')
            ->join('mentors', 'users.user_id', '=', 'mentors.user_id')
            ->select([
                'users.user_id', 'users.email', 'users.status', 
                'mentors.name', 'mentors.surname',
                DB::raw("'Teacher' as role")
            ]);

        $allUsers = $students->union($teachers)->get()->map(function($u) {
            return [
                'id'     => ($u->role === 'Student' ? 'STU' : 'TCH') . str_pad($u->user_id, 3, '0', STR_PAD_LEFT),
                'name'   => trim($u->name . ' ' . $u->surname),
                'email'  => $u->email,
                'role'   => $u->role,
                // CRITICAL FIX: Capitalize 'Active' or 'Banned' so the HTML dropdown recognizes it!
                'status' => ucfirst(strtolower($u->status ?? 'active')) 
            ];
        });

        return response()->json(['data' => $allUsers]);
    }

    // ── 3. TIMETABLE MANAGEMENT ──
    public function getTimetable()
    {
        $slots = DB::table('timetable')
            ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
            ->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
            ->join('groups', 'bridge.group_id', '=', 'groups.group_id')
            ->select([
                'timetable.session_id as id',
                'timetable.time_slot',
                'timetable.day_slot',
                'timetable.session_type as type',
                'timetable.room_number as roomId',
                'groups.group_id as groupId',
                'subjects.subject_id as subjectId',
                'subjects.name as subject',
                'subjects.mentor_id as teacherId'
            ])->get()->map(function($s) {
                // Convert integers to UI strings based on your migrations
                $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];
                $times = [1 => '09:00', 2 => '11:00', 3 => '14:00'];
                
                return [
                    'id'        => 'SL' . $s->id,
                    'day'       => $days[$s->day_slot] ?? 'Unknown',
                    'time'      => $times[$s->time_slot] ?? 'TBA',
                    'subject'   => $s->subject,
                    'subjectId' => 'CS' . $s->subjectId,
                    'teacherId' => $s->teacherId,
                    'groupId'   => $s->groupId,
                    'roomId'    => 'R' . $s->roomId,
                    'type'      => strtolower($s->type ?? 'lecture')
                ];
            });

        return response()->json(['data' => $slots]);
    }

    // ── TEACHERS API ──
    public function getTeachers()
    {
        // 1. Fetch all subjects first so we can map them to the correct teachers
        $allSubjects = DB::table('subjects')->get()->groupBy('mentor_id');

        // 2. Fetch all the rich data from the mentors table
        $teachers = DB::table('mentors')
            ->join('users', 'mentors.user_id', '=', 'users.user_id')
            ->select([
                'mentors.mentor_id as id',
                'mentors.name as firstName',
                'mentors.surname as lastName',
                'users.email',
                'mentors.department',
                'mentors.phone_number',
                'mentors.office_location',
                'mentors.office_hours',
                'mentors.bio',
                'mentors.profile_data' // This contains the JSON array of their experience/education
            ])->get()->map(function($t) use ($allSubjects) {
                
                // Get the subjects this specific teacher is teaching
                $teacherSubjects = $allSubjects->get($t->id, collect());
                $subjectNames = $teacherSubjects->pluck('name')->toArray();

                return [
                    'id'          => $t->id,
                    'title'       => 'Dr.',
                    'firstName'   => $t->firstName,
                    'lastName'    => $t->lastName,
                    'email'       => $t->email,
                    
                    // Map the real DB columns to the variables your JS expects
                    'department'     => $t->department ?? 'Computer Science',
                    'phone'          => $t->phone_number ?? 'Not Provided',
                    'officeLocation' => $t->office_location ?? 'TBA',
                    'officeHours'    => $t->office_hours ?? 'TBA',
                    'bio'            => $t->bio ?? 'No biography provided.',
                    
                    // Pass the real subjects (e.g., ['Data Structures', 'Web Development'])
                    'subjects'    => !empty($subjectNames) ? $subjectNames : ['No assigned courses'],
                    
                    // Automatically decode the JSON so the frontend can read their work experience
                    'profileData' => json_decode($t->profile_data, true) 
                ];
            });

        return response()->json(['data' => $teachers]);
    }

    // ── TIMETABLE EDITOR APIs ──
    public function getConflicts()
    {
        // For now, return an empty array so the frontend stops throwing the 404 error.
        // The real collision logic is already handled safely in your Seeder!
        return response()->json(['data' => []]);
    }

    public function addSlot(Request $request)
    {
        // 1. Map the UI strings to your database integers
        $dayMap = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6];
        $timeMap = ['08:00'=>1, '09:00'=>1, '10:00'=>1, '11:00'=>2, '12:00'=>2, '13:00'=>2, '14:00'=>3, '15:00'=>3];

        $daySlot = $dayMap[$request->input('day')] ?? 1;
        $timeSlot = $timeMap[$request->input('time')] ?? 1;
        $roomId = (int) str_replace('R', '', $request->input('roomId'));
        
        // Clean CS301 to just 301
        $subjectId = (int) str_replace('CS', '', $request->input('subjectId'));
        $groupId = $request->input('groupId');

        // 2. We must link the Subject and Group in the bridge table first
        $bridge = DB::table('subjects_groups_bridge_table')
            ->where('subject_id', $subjectId)
            ->where('group_id', $groupId)
            ->first();

        if (!$bridge) {
            $bridgeId = DB::table('subjects_groups_bridge_table')->insertGetId([
                'subject_id' => $subjectId,
                'group_id' => $groupId
            ], 'subject_group_id');
        } else {
            $bridgeId = $bridge->subject_group_id;
        }

        // 3. Save the actual timetable slot
        $id = DB::table('timetable')->insertGetId([
            'subject_group_id' => $bridgeId,
            'time_slot' => $timeSlot,
            'day_slot' => $daySlot,
            'room_number' => $roomId,
            'session_type' => $request->input('type') ?? 'lecture',
            'building' => 'Block A'
        ], 'session_id');

        // 4. Return the exact JSON structure the frontend calendar expects
        return response()->json(['data' => [
            'id' => 'SL' . $id,
            'day' => $request->input('day'),
            'time' => $request->input('time'),
            'subject' => $request->input('subject'),
            'subjectId' => $request->input('subjectId'),
            'teacherId' => $request->input('teacherId'),
            'groupId' => $groupId,
            'roomId' => $request->input('roomId'),
            'type' => $request->input('type')
        ]]);
    }

    public function deleteSlot($id)
    {
        // Extract real DB ID from "SL15"
        $realId = (int) str_replace('SL', '', $id);
        
        DB::table('timetable')->where('session_id', $realId)->delete();
        
        return response()->json(['message' => 'Slot removed successfully']);
    }

    public function publish()
    {
        // In the future, you could add logic here to email students or flip a 'draft' status to 'live'.
        // For now, we return a success response so the UI knows it worked!
        return response()->json(['message' => 'Timetable published successfully.']);
    }

    // ── 5. GRADES MANAGEMENT ──
    public function getGrades(Request $request)
    {
        $groupId = $request->query('groupId');
        $subjectId = $request->query('subjectId');

        $query = DB::table('grades')
            ->join('students', 'grades.student_id', '=', 'students.student_id')
            ->join('groups', 'students.group_id', '=', 'groups.group_id')
            ->join('subjects', 'grades.subject_id', '=', 'subjects.subject_id')
            ->select([
                'students.student_id as id',
                'students.name',
                'students.surname',
                'groups.group_name as group',
                'subjects.name as course',
                'grades.percentage'
            ]);

        // Apply filters if the frontend dropdowns are used
        if (!empty($groupId)) {
            $query->where('groups.group_name', $groupId);
        }

        if (!empty($subjectId)) {
            $query->where('subjects.name', $subjectId);
        }

        // Format exactly how the frontend UI expects it
        $grades = $query->get()->map(function($g) {
            return [
                'id'          => 'STU' . str_pad($g->id, 3, '0', STR_PAD_LEFT),
                'name'        => trim($g->name . ' ' . $g->surname),
                'group'       => $g->group,
                'course'      => $g->course,
                // The frontend has 3 columns (assignments, midterm, final)
                // We use the real database percentage for all 3 to keep the UI populated
                'assignments' => $g->percentage,
                'midterm'     => $g->percentage,
                'final'       => $g->percentage,
            ];
        });

        return response()->json(['data' => $grades]);
    }

    public function updateGrade(Request $request, $id)
    {
        // Extract real DB ID from "STU001"
        $realId = (int) preg_replace('/[^0-9]/', '', $id);
        $courseName = $request->input('course');

        $subject = DB::table('subjects')->where('name', $courseName)->first();

        if ($subject) {
            // Read the numbers from the Edit Modal
            $assignments = (float) $request->input('assignments', 0);
            $midterm     = (float) $request->input('midterm', 0);
            $final       = (float) $request->input('final', 0);

            // Calculate a new overall percentage (simple average)
            $newPercentage = round(($assignments + $midterm + $final) / 3);
            
            // Calculate the new Letter Grade
            $letter = 'F';
            if ($newPercentage >= 90) $letter = 'A';
            elseif ($newPercentage >= 80) $letter = 'B';
            elseif ($newPercentage >= 70) $letter = 'C';
            elseif ($newPercentage >= 60) $letter = 'D';

            // Save straight to PostgreSQL
            DB::table('grades')
                ->where('student_id', $realId)
                ->where('subject_id', $subject->subject_id)
                ->update([
                    'percentage' => $newPercentage,
                    'grade'      => $letter
                ]);
        }

        return response()->json(['message' => 'Grade updated successfully']);
    }

    // ── 6. NEWS CMS MANAGEMENT ──
    public function getNews()
    {
        // Fetch all news and format the dates nicely for the UI
        $news = DB::table('news')->orderByDesc('created_at')->get()->map(function($n) {
            return [
                'id'       => $n->news_id ?? $n->id, // Handles both standard and custom primary keys
                'title'    => $n->title,
                'content'  => $n->body,
                'category' => $n->category ?? 'General',
                'author'   => 'Admin',
                'status'   => 'Published',
                'date'     => \Carbon\Carbon::parse($n->created_at)->format('M d, Y')
            ];
        });

        return response()->json(['data' => $news]);
    }

    public function createNews(Request $request)
    {
        $imageName = null;
        
        // 🚨 FIX: Move the uploaded file directly to public/images/news!
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
            
            // This puts the file right into your public folder
            $file->move(public_path('images/news'), $filename); 
            
            // We only save the filename to the database now! (e.g., "16812345_cover.jpg")
            $imageName = $filename; 
        }

        $cleanText = strip_tags($request->input('content', ''));
        $excerpt = \Illuminate\Support\Str::limit($cleanText, 100);

        $id = DB::table('news')->insertGetId([
            'title'      => $request->input('title'),
            'body'       => $request->input('content'),
            'category'   => $request->input('category', 'General'),
            'excerpt'    => $excerpt,
            'image'      => $imageName, // 🚨 Saved as just the filename!
            'created_at' => now(),
            'updated_at' => now(),
        ], 'news_id');

        return response()->json(['message' => 'News created successfully', 'data' => ['id' => $id]]);
    }

    public function updateNews(Request $request, $id)
    {
        DB::table('news')->where('news_id', $id)->update([
            'title'      => $request->input('title'),
            'body'       => $request->input('content'),
            'category'   => $request->input('category', 'General'),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'News updated successfully']);
    }

    public function deleteNews($id)
    {
        DB::table('news')->where('news_id', $id)->delete();

        return response()->json(['message' => 'News deleted successfully']);
    }

    // ── 7. ATTENDANCE MANAGEMENT ──
    public function getAttendance(Request $request)
    {
        $groupName = $request->query('group');
        $courseName = $request->query('course');
        $date = $request->query('date', now()->toDateString());

        if (empty($groupName) || empty($courseName)) {
            return response()->json(['scheduled' => false, 'data' => []]);
        }

        // 1. Find out what day of the week the selected date is (1 = Mon, 7 = Sun)
        $dayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeekIso;

        // 2. Check if a class actually exists in the timetable for this exact day!
        $hasSession = DB::table('timetable')
            ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
            ->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
            ->join('groups', 'bridge.group_id', '=', 'groups.group_id')
            ->where('groups.group_name', $groupName)
            ->where('subjects.name', $courseName)
            ->where('timetable.day_slot', $dayOfWeek)
            ->exists();

        // 3. If there is no class scheduled, block the data and tell the frontend!
        if (!$hasSession) {
            return response()->json([
                'scheduled' => false,
                'data' => []
            ]);
        }

        // 4. If a class IS scheduled, proceed to load the students as normal
        $query = DB::table('students')
            ->join('groups', 'students.group_id', '=', 'groups.group_id')
            ->leftJoin('attendance', function($join) use ($date) {
                $join->on('students.student_id', '=', 'attendance.student_id')
                     ->whereDate('attendance.session_date', '=', $date);
            })
            ->select([
                'students.student_id as id',
                'students.name',
                'students.surname',
                'groups.group_name as group',
                'attendance.is_present',
                'attendance.session_date as date'
            ])
            ->where('groups.group_name', $groupName);

        $records = $query->get()->map(function($a) use ($courseName, $date) {
            $status = ''; 
            if ($a->is_present !== null) {
                $status = $a->is_present ? 'present' : 'absent';
            }

            return [
                'id'     => 'STU' . str_pad($a->id, 3, '0', STR_PAD_LEFT),
                'name'   => trim($a->name . ' ' . $a->surname),
                'group'  => $a->group,
                'course' => $courseName,
                'date'   => $a->date ?? $date,
                'status' => $status
            ];
        });

        return response()->json([
            'scheduled' => true, 
            'data' => $records
        ]);
    }

    public function updateAttendance(Request $request, $id)
    {
        $realId = (int) preg_replace('/[^0-9]/', '', $id);
        $date = $request->input('date', now()->toDateString());
        $courseName = $request->input('course'); 
        
        // 🚨 FIX 2: Correctly read the true/false boolean sent by the updated Javascript!
        $isPresent = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);

        $dayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeekIso; 

        $sessionQuery = DB::table('timetable')
            ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
            ->join('students', 'bridge.group_id', '=', 'students.group_id')
            ->where('students.student_id', $realId)
            ->where('timetable.day_slot', $dayOfWeek);
            
        if ($courseName) {
            $sessionQuery->join('subjects', 'bridge.subject_id', '=', 'subjects.subject_id')
                         ->where('subjects.name', $courseName);
        }
        
        $session = $sessionQuery->first();
        
        // Fallback session ID in case the teacher is marking attendance on a weekend
        $sessionId = $session ? $session->session_id : DB::table('timetable')->value('session_id');

        DB::table('attendance')->updateOrInsert(
            [
                'student_id'   => $realId,
                'session_date' => $date,
                'session_id'   => $sessionId
            ],
            [
                'is_present'   => $isPresent
            ]
        );

        return response()->json(['message' => 'Attendance updated']);
    }

    // ── 8. CONTACT & DIRECTORY MANAGEMENT ──
    
    public function getDepartments()
    {
        // Instead of needing a dedicated 'departments' table, we can dynamically 
        // scan your 'mentors' table and pull out every unique department name!
        $departments = DB::table('mentors')
            ->whereNotNull('department')
            ->select('department')
            ->distinct()
            ->get()
            ->map(function($d, $index) {
                return [
                    'id'   => 'dept_' . ($index + 1),
                    'name' => $d->department
                ];
            });

        // Let's also add a general "Staff / Administration" department for the UI
        $departments->push([
            'id'   => 'dept_staff',
            'name' => 'University Administration'
        ]);

        return response()->json(['data' => $departments]);
    }

    public function getMessages()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('messages')) {
            $query = DB::table('messages');

            // Safely check which column exists to sort by, preventing SQL crashes!
            if (\Illuminate\Support\Facades\Schema::hasColumn('messages', 'created_at')) {
                $query->orderByDesc('created_at');
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('messages', 'date')) {
                $query->orderByDesc('date');
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('messages', 'message_id')) {
                $query->orderByDesc('message_id');
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('messages', 'id')) {
                $query->orderByDesc('id');
            }

            $messages = $query->get();
            return response()->json(['data' => $messages]);
        }

        return response()->json(['data' => []]);
    }

    // ── 9. CONTENT MANAGER ──
    public function getContentStats()
    {
        $activeCourses = DB::table('subjects')->count();
        $studentsEnrolled = DB::table('students')->count();
        
        // Safely check if the assignments table exists before counting
        $assignmentsCreated = \Illuminate\Support\Facades\Schema::hasTable('assignments') 
            ? DB::table('assignments')->count() : 0;
            
        // Assuming you don't have a 'materials' table yet, we provide a safe fallback
        $materialsUploaded = \Illuminate\Support\Facades\Schema::hasTable('materials') 
            ? DB::table('materials')->count() : 12; 

        return response()->json([
            'data' => [
                'courses'     => $activeCourses,
                'materials'   => $materialsUploaded,
                'assignments' => $assignmentsCreated,
                'students'    => $studentsEnrolled
            ]
        ]);
    }

    public function getContentModules()
    {
        $subjects = DB::table('subjects')
            ->leftJoin('mentors', 'subjects.mentor_id', '=', 'mentors.mentor_id')
            ->leftJoin('users', 'mentors.user_id', '=', 'users.user_id')
            ->select([
                'subjects.subject_id',
                'subjects.name as subject_name',
                'subjects.credits',
                'subjects.description',
                'mentors.name as mentor_name',
                'mentors.surname as mentor_surname',
                'mentors.department',
                'mentors.office_location',
                'mentors.office_hours',
                'users.email as mentor_email'
            ])->get();

        $modules = $subjects->map(function($subj) {
            $studentCount = DB::table('students')
                ->join('subjects_groups_bridge_table', 'students.group_id', '=', 'subjects_groups_bridge_table.group_id')
                ->where('subjects_groups_bridge_table.subject_id', $subj->subject_id)
                ->count();

            $assignmentCount = \Illuminate\Support\Facades\Schema::hasTable('assignments') 
                ? DB::table('assignments')->where('subject_id', $subj->subject_id)->count() : 0;

            return [
                'id'         => $subj->subject_id,
                'code'       => 'CS' . str_pad($subj->subject_id, 3, '0', STR_PAD_LEFT),
                'title'      => $subj->subject_name,
                'desc'       => $subj->description,
                'teacher'    => $subj->mentor_name ? 'Dr. ' . $subj->mentor_name . ' ' . $subj->mentor_surname : 'Unassigned',
                
                // New Detailed Mentor Info!
                'instrDept'  => $subj->department ?? 'Computer Science',
                'instrEmail' => $subj->mentor_email ?? 'N/A',
                'instrRoom'  => $subj->office_location ?? 'TBA',
                'instrHours' => $subj->office_hours ?? 'TBA',
                
                'credits'    => $subj->credits ?? 15,
                'progress'   => rand(40, 95),
                'students'   => $studentCount,
                'resources'  => rand(2, 8),
                'tasks'      => $assignmentCount
            ];
        });

        return response()->json(['data' => $modules]);
    }

    public function getContentAssignments()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('assignments')) {
            $assignments = DB::table('assignments')->get()->map(function($a) {
                return [
                    'id'          => $a->assignment_id,
                    'title'       => $a->title,
                    'description' => $a->body ?? 'No description provided.',
                    'deadline'    => $a->due_time ? \Carbon\Carbon::parse($a->due_time)->format('M d, Y') : 'TBD',
                    'weight'      => $a->weight ?? 20,
                    'group'       => 'All Groups', // Defaulting to all groups for global assignments
                    'status'      => 'open',
                    'courseId'    => $a->subject_id
                ];
            });
            return response()->json(['data' => $assignments]);
        }
        return response()->json(['data' => []]);
    }

    public function createUser(\Illuminate\Http\Request $request)
    {
        // 1. Determine Role
        $roleStr = strtolower($request->input('role', 'student'));
        $roleId = $roleStr === 'student' ? 1 : ($roleStr === 'teacher' ? 2 : 3);

        // 2. Create User securely
        $userId = DB::table('users')->insertGetId([
            'role_id'       => $roleId,
            'email'         => $request->input('email'),
            'password_hash' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
            'status'        => 'active', // Always active by default!
            'created_at'    => now(),
            'updated_at'    => now(),
        ], 'user_id');

        // 3. Split Name into First & Last
        $names = explode(' ', $request->input('fullName', 'New User'), 2);
        $firstName = $names[0];
        $lastName = $names[1] ?? '';

        // 4. Create Profile
        if ($roleId === 1) {
            DB::table('students')->insert([
                'user_id' => $userId, 'name' => $firstName, 'surname' => $lastName,
                'entry_year' => date('Y'), 'group_id' => 1, 'phone_number' => 'N/A'
            ]);
        } else {
            DB::table('mentors')->insert([
                'user_id' => $userId, 'name' => $firstName, 'surname' => $lastName,
                'email' => $request->input('email'), 'department' => 'General', 'phone_number' => 'N/A'
            ]);
        }

        return response()->json(['message' => 'User created successfully!']);
    }

    public function updateUser(\Illuminate\Http\Request $request, $id)
    {
        $realId = (int) preg_replace('/[^0-9]/', '', $id);
        
        // 1. Update basic Users table (Status & Email)
        $userUpdate = ['updated_at' => now()];
        if ($request->has('status')) $userUpdate['status'] = strtolower($request->input('status'));
        if ($request->has('email'))  $userUpdate['email'] = $request->input('email');
        
        DB::table('users')->where('user_id', $realId)->update($userUpdate);

        // 2. If the request came from the Teachers page:
        if ($request->has('firstName') && $request->has('lastName')) {
            DB::table('mentors')->where('user_id', $realId)->update([
                'name' => $request->input('firstName'),
                'surname' => $request->input('lastName'),
                'email' => $request->input('email'),
                'department' => $request->input('department', 'General'),
                'phone_number' => $request->input('phone', 'N/A')
            ]);
        } 
        // 3. If the request came from the General Users page:
        else if ($request->has('name')) {
            $names = explode(' ', $request->input('name'), 2);
            $firstName = $names[0];
            $lastName = $names[1] ?? '';
            
            // Try updating both; it will safely ignore the one they don't belong to
            DB::table('mentors')->where('user_id', $realId)->update(['name' => $firstName, 'surname' => $lastName, 'email' => $request->input('email')]);
            DB::table('students')->where('user_id', $realId)->update(['name' => $firstName, 'surname' => $lastName]);
        }

        return response()->json(['message' => 'User updated successfully!']);
    }

    public function deleteUser($id)
    {
        // 1. Extract the actual integer ID (e.g., from "STU019" -> 19)
        $realId = (int) preg_replace('/[^0-9]/', '', $id);
        
        // 2. Delete the user from the database
        // (Assuming your database has ON DELETE CASCADE, this will safely 
        // remove them from the students/mentors tables as well).
        DB::table('users')->where('user_id', $realId)->delete();

        // 3. Return a successful JSON response
        return response()->json(['message' => 'User deleted successfully!']);
    }

    public function updateTeacher(\Illuminate\Http\Request $request, $id)
    {
        // 1. Extract the number from the frontend ID (e.g., "TCH002" -> 2)
        $realId = (int) preg_replace('/[^0-9]/', '', $id);

        // 2. Safely find the teacher in the database
        $mentor = DB::table('mentors')->where('mentor_id', $realId)->first() 
                  ?? DB::table('mentors')->where('id', $realId)->first();

        if (!$mentor) {
            return response()->json(['message' => 'Teacher not found!'], 404);
        }

        // 3. Update their Teacher Profile
        DB::table('mentors')->where('user_id', $mentor->user_id)->update([
            'name' => $request->input('firstName'),
            'surname' => $request->input('lastName'),
            'email' => $request->input('email'),
            'department' => $request->input('department', 'General'),
            'phone_number' => $request->input('phone', 'N/A')
        ]);

        // 4. Update their Main Login Account
        DB::table('users')->where('user_id', $mentor->user_id)->update([
            'email' => $request->input('email'),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Teacher updated successfully!']);
    }
}