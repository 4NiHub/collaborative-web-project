<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FullDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // PostgreSQL: defer foreign key checks during seeding
        DB::statement('SET CONSTRAINTS ALL DEFERRED;');

        // ────────────────────────────────────────────────
        // 1. ROLES
        // ────────────────────────────────────────────────
        DB::table('roles')->insertOrIgnore([
            ['role_id' => 1, 'role_name' => 'student'],
            ['role_id' => 2, 'role_name' => 'mentor'],
            ['role_id' => 3, 'role_name' => 'staff'],
            ['role_id' => 4, 'role_name' => 'admin'],
        ]);

        // ────────────────────────────────────────────────
        // 2. USERS
        // ────────────────────────────────────────────────
        $bcryptHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

        DB::table('users')->insertOrIgnore([
            ['user_id' => 1, 'role_id' => 1, 'email' => 'a.morgan@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 09:00:00'],
            ['user_id' => 2, 'role_id' => 2, 'email' => 's.johnson@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:00:00'],
            ['user_id' => 3, 'role_id' => 2, 'email' => 'm.chen@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:01:00'],
            ['user_id' => 4, 'role_id' => 2, 'email' => 'e.rodriguez@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:02:00'],
            ['user_id' => 5, 'role_id' => 2, 'email' => 's.taylor@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:03:00'],
            ['user_id' => 6, 'role_id' => 2, 'email' => 'd.williams@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:04:00'],
            ['user_id' => 7, 'role_id' => 2, 'email' => 'j.anderson@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:05:00'],
            ['user_id' => 8, 'role_id' => 3, 'email' => 'student.services@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:06:00'],
            ['user_id' => 9, 'role_id' => 3, 'email' => 'registry@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:07:00'],
            ['user_id' => 10, 'role_id' => 3, 'email' => 'it@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:08:00'],
            ['user_id' => 11, 'role_id' => 3, 'email' => 'finance@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:09:00'],
            ['user_id' => 12, 'role_id' => 3, 'email' => 'careers@wlv.ac.uk', 'password_hash' => $bcryptHash, 'creation_time' => '2025-09-01 10:10:00'],
        ]);

        // ────────────────────────────────────────────────
        // 3. GROUPS
        // ────────────────────────────────────────────────
        DB::table('groups')->insertOrIgnore([
            [
                'group_id'      => 1,
                'group_name'    => 'BSc Computer Science',
                'group_level'   => 1,
                'is_active'     => true,
                'creation_date' => '2025-09-01',
            ],
            [
                'group_id' => 2, 
                'group_name' => 'MSc Artificial Intelligence',
                'group_level' => 2, 
                'is_active' => true,  
                'creation_date' => '2025-09-01'
            ],
        ]);

        // ────────────────────────────────────────────────
        // 4. STUDENTS – Alex Morgan
        // ────────────────────────────────────────────────
        DB::table('students')->insertOrIgnore([
            [
                'student_id'            => 1,
                'user_id'               => 1,
                'name'                  => 'Alex',
                'surname'               => 'Morgan',
                'entry_year'            => 3,
                'group_id'              => 1,
                'phone_number'          => '+1 555-1234',
                'gpa'                   => 0.0,
                'credits_completed'     => 18,
                'attendance_percentage' => 89,
            ],
        ]);

        // 5. MENTORS (6 teachers) — STRUCTURED OBJECTS for frontend rendering
        // ────────────────────────────────────────────────
        DB::table('mentors')->insertOrIgnore([
            [
                'mentor_id'        => 1,
                'user_id'          => 2,
                'name'             => 'Sarah',
                'surname'          => 'Johnson',
                'email'            => 's.johnson@wlv.ac.uk',
                'phone_number'     => '+1 555-0101',
                'department'       => 'Computer Science',
                'office_location'  => 'Block A, Room 205',
                'office_hours'     => 'Mon 14:00–16:00, Wed 10:00–12:00',
                'bio'              => 'Expert in computer science with 10+ years of teaching experience.',
                'nationality'      => 'British',
                'languages'        => 'English',
                'profile_data'     => json_encode([
                    'experience' => [
                        [
                            'title'  => 'Senior Lecturer in Computer Science',
                            'org'    => 'Wolverhampton',
                            'link'   => 'https://www.wlv.ac.uk/',
                            'period' => '2015–present',
                            'desc'   => 'Leading undergraduate and postgraduate modules in algorithms and data structures.',
                            'color'  => 'blue'
                        ],
                        [
                            'title'  => 'Postdoctoral Researcher',
                            'org'    => 'University of Manchester',
                            'link'   => 'https://www.manchester.ac.uk/',
                            'period' => '2012–2015',
                            'desc'   => 'Research in efficient algorithm design and complexity theory.',
                            'color'  => 'blue'
                        ],
                        [
                            'title'  => 'Software Engineer',
                            'org'    => 'ARM Holdings',
                            'link'   => 'https://www.arm.com/',
                            'period' => '2008–2012',
                            'desc'   => 'Developed low-level software components for embedded systems.',
                            'color'  => 'blue'
                        ],
                    ],
                    'education' => [
                        [
                            'degree'  => 'PhD Computer Science',
                            'school'  => 'University of Manchester',
                            'link'    => 'https://www.manchester.ac.uk/',
                            'period'  => '2012'
                        ],
                        [
                            'degree'  => 'MSc Advanced Computer Science',
                            'school'  => 'University of Bristol',
                            'link'    => 'https://www.bristol.ac.uk/',
                            'period'  => '2008'
                        ],
                        [
                            'degree'  => 'BSc Computer Science (First Class)',
                            'school'  => 'University of Warwick',
                            'link'    =>  'https://warwick.ac.uk/',
                            'period'  => '2007'
                        ],
                    ],
                    'roles' => [
                        [
                            'title'  => 'Programme Leader — BSc Computer Science',
                            'org'    => 'Wolverhampton',
                            'status' => 'Current',
                            'desc'   => 'Responsible for curriculum development and quality assurance (2022–present)'
                        ],
                        [
                            'title'  => 'Module Coordinator — Data Structures & Algorithms',
                            'org'    => 'Wolverhampton',
                            'status' => 'Current',
                            'desc'   => 'Designing assessments and supervising teaching assistants'
                        ],
                    ]
                ]),
            ],

            [
                'mentor_id'        => 2,
                'user_id'          => 3,
                'name'             => 'Michael',
                'surname'          => 'Chen',
                'email'            => 'm.chen@wlv.ac.uk',
                'phone_number'     => '+1 555-0102',
                'department'       => 'Computer Science',
                'office_location'  => 'Block B, Room 310',
                'office_hours'     => 'Tue 13:00–15:00, Thu 10:00–12:00',
                'bio'              => 'Passionate about modern web technologies and accessible design.',
                'nationality'      => 'British',
                'languages'        => 'English, Mandarin',
                'profile_data'     => json_encode([
                    'experience' => [
                        [
                            'title'  => 'Lecturer in Web Technologies',
                            'org'    => 'Wolverhampton',
                            'link'   => 'https://www.wlv.ac.uk/',
                            'period' => '2018–present',
                            'desc'   => 'Teaching frontend, backend and full-stack development courses.',
                            'color'  => 'blue'
                        ],
                        [
                            'title'  => 'Frontend Developer',
                            'org'    => 'BBC Digital',
                            'link'   => 'https://www.bbcdigital.com.au/',
                            'period' => '2014–2018',
                            'desc'   => 'Built responsive web applications for news and media platforms.',
                            'color'  => 'blue'
                        ],
                    ],
                    'education' => [
                        [
                            'degree'  => 'MSc Internet Computing',
                            'school'  => 'Imperial College London',
                            'link' => 'https://www.imperial.ac.uk/',
                            'period'  => '2014'
                        ],
                        [
                            'degree'  => 'BSc Computer Science',
                            'school'  => 'University of Edinburgh',
                            'link'    => 'https://www.ed.ac.uk/',
                            'period'  => '2012'
                        ],
                    ],
                    'roles' => [
                        [
                            'title'  => 'Web Accessibility Champion',
                            'org'    => 'Wolverhampton Faculty of Computing',
                            'status' => 'Current',
                            'desc'   => 'Leading initiative to make all departmental websites WCAG 2.2 compliant'
                        ],
                    ]
                ]),
            ],

            [
                'mentor_id'        => 3,
                'user_id'          => 4,
                'name'             => 'Emily',
                'surname'          => 'Rodriguez',
                'email'            => 'e.rodriguez@wlv.ac.uk',
                'phone_number'     => '+1 555-0103',
                'department'       => 'Computer Science',
                'office_location'  => 'Block A, Room 208',
                'office_hours'     => 'Mon 10:00–12:00, Fri 14:00–16:00',
                'bio'              => 'Database specialist with strong focus on data modelling and performance.',
                'nationality'      => 'British',
                'languages'        => 'English, Spanish',
                'profile_data'     => json_encode([
                    'experience' => [
                        [
                            'title'  => 'Lecturer in Database Systems',
                            'org'    => 'Wolverhampton',
                            'link'   => 'https://www.wlv.ac.uk/',
                            'period' => '2019–present',
                            'desc'   => 'Teaching relational databases, NoSQL and big data technologies.',
                            'color'  => 'blue'
                        ],
                        [
                            'title'  => 'Database Administrator',
                            'org'    => 'NHS Digital',
                            'link'   => 'https://digital.nhs.uk/',
                            'period' => '2015–2019',
                            'desc'   => 'Managed large-scale healthcare databases with high availability requirements.',
                            'color'  => 'blue'
                        ],
                    ],
                    'education' => [
                        [
                            'degree'  => 'PhD Information Systems',
                            'school'  => 'University College London',
                            'link'    => 'https://www.ucl.ac.uk/',
                            'period'  => '2015'
                        ],
                        [
                            'degree'  => 'MSc Database Systems',
                            'school'  => 'City University London',
                            'link'    => 'https://www.citystgeorges.ac.uk/',
                            'period'  => '2011'
                        ],
                    ],
                    'roles' => [
                        [
                            'title'  => 'Best Lecturer Award Winner',
                            'org'    => 'Wolverhampton Student Union',
                            'status' => '2024',
                            'desc'   => 'Recognized for outstanding teaching and student support'
                        ],
                    ]
                ]),
            ],

            [
                'mentor_id'        => 4,
                'user_id'          => 5,
                'name'             => 'Susan',
                'surname'          => 'Taylor',
                'email'            => 's.taylor@wlv.ac.uk',
                'phone_number'     => '+1 555-0104',
                'department'       => 'Mathematics',
                'office_location'  => 'Block C, Room 102',
                'office_hours'     => 'Wed 09:00–11:00, Thu 14:00–16:00',
                'bio'              => 'Applied mathematician with experience in modelling complex systems.',
                'nationality'      => 'British',
                'languages'        => 'English',
                'profile_data'     => json_encode([
                    'experience' => [
                        [
                            'title'  => 'Senior Lecturer in Mathematics',
                            'org'    => 'Wolverhampton',
                            'link'   => 'https://www.wlv.ac.uk/',
                            'period' => '2010–present',
                            'desc'   => 'Teaching advanced calculus, linear algebra and mathematical modelling.',
                            'color'  => 'blue'
                        ],
                        [
                            'title'  => 'Mathematical Modeller',
                            'org'    => 'Met Office',
                            'link'   => 'https://www.metoffice.gov.uk/',
                            'period' => '2006–2010',
                            'desc'   => 'Developed weather prediction models using numerical methods.',
                            'color'  => 'blue'
                        ],
                    ],
                    'education' => [
                        [
                            'degree'  => 'PhD Applied Mathematics',
                            'school'  => 'University of Oxford',
                            'link'    => 'https://www.ox.ac.uk/',
                            'period'  => '2006'
                        ],
                        [
                            'degree'  => 'MSc Mathematical Modelling',
                            'school'  => 'Imperial College London',
                            'link'    => 'https://www.imperial.ac.uk/',
                            'period'  => '2003'
                        ],
                        [
                            'degree'  => 'BSc Mathematics (First Class)',
                            'school'  => 'University of Bath',
                            'link'    => 'https://www.bath.ac.uk/',
                            'period'  => '2002'
                        ],
                    ],
                    'roles' => [
                        [
                            'title'  => 'Examinations Officer',
                            'org'    => 'Faculty of Computing & Mathematics',
                            'status' => 'Current',
                            'desc'   => 'Overseeing assessment processes and academic integrity'
                        ],
                    ]
                ]),
            ],

            [
                'mentor_id'        => 5,
                'user_id'          => 6,
                'name'             => 'David',
                'surname'          => 'Williams',
                'email'            => 'd.williams@wlv.ac.uk',
                'phone_number'     => '+1 555-0105',
                'department'       => 'Software Engineering',
                'office_location'  => 'Block B, Room 405',
                'office_hours'     => 'Tue 10:00–12:00, Fri 13:00–15:00',
                'bio'              => 'Cybersecurity lecturer and former penetration tester.',
                'nationality'      => 'British',
                'languages'        => 'English',
                'profile_data'     => json_encode([
                    'experience' => [
                        [
                            'title'  => 'Lecturer in Cybersecurity',
                            'org'    => 'Wolverhampton',
                            'link'   => 'https://www.wlv.ac.uk/',
                            'period' => '2020–present',
                            'desc'   => 'Teaching network security, ethical hacking and secure software development.',
                            'color'  => 'blue'
                        ],
                        [
                            'title'  => 'Security Consultant',
                            'org'    => 'Deloitte Cyber',
                            'link'   => 'https://www.deloitte.com/global/en/services/consulting/services/cyber.html',
                            'period' => '2016–2020',
                            'desc'   => 'Conducted security assessments for Fortune 500 clients.',
                            'color'  => 'blue'
                        ],
                        [
                            'title'  => 'Penetration Tester (freelance)',
                            'org'    => 'Various clients',
                            'link'   => 'https://www.linkedin.com/company/consultancyclients',
                            'period' => '2014–2016',
                            'desc'   => 'Performed vulnerability assessments and red team exercises.',
                            'color'  => 'blue'
                        ],
                    ],
                    'education' => [
                        [
                            'degree'  => 'MSc Cyber Security',
                            'school'  => 'Royal Holloway, University of London',
                            'link'    => 'https://www.royalholloway.ac.uk/',
                            'period'  => '2016'
                        ],
                        [
                            'degree'  => 'BSc Computer Science & Security',
                            'school'  => 'University of Surrey',
                            'link'    => 'https://www.surrey.ac.uk/',
                            'period'  => '2014'
                        ],
                    ],
                    'roles' => [
                        [
                            'title'  => 'Cyber Security Society Advisor',
                            'org'    => 'Wolverhampton',
                            'status' => 'Current',
                            'desc'   => 'Mentoring student cybersecurity club and organising CTF events'
                        ],
                    ]
                ]),
            ],

            [
                'mentor_id'        => 6,
                'user_id'          => 7,
                'name'             => 'James',
                'surname'          => 'Anderson',
                'email'            => 'j.anderson@wlv.ac.uk',
                'phone_number'     => '+1 555-0106',
                'department'       => 'Computer Science',
                'office_location'  => 'Block A, Room 210',
                'office_hours'     => 'Mon 11:00–13:00, Thu 15:00–17:00',
                'bio'              => 'Pure mathematician specialising in discrete structures.',
                'nationality'      => 'British',
                'languages'        => 'English',
                'profile_data'     => json_encode([
                    'experience' => [
                        [
                            'title'  => 'Lecturer in Discrete Mathematics',
                            'org'    => 'Wolverhampton',
                            'link' => 'https://www.wlv.ac.uk/',
                            'period' => '2017–present',
                            'desc'   => 'Teaching graph theory, combinatorics and algorithm analysis.',
                            'color'  => 'blue'
                        ],
                    ],
                    'education' => [
                        [
                            'degree'  => 'PhD Pure Mathematics',
                            'school'  => 'University of Cambridge',
                            'link'    => 'https://www.cam.ac.uk/',
                            'period'  => '2017'
                        ],
                        [
                            'degree'  => 'MMath Mathematics',
                            'school'  => 'University of Warwick',
                            'link'    => 'https://warwick.ac.uk/',
                            'period'  => '2013'
                        ],
                    ],
                    'roles' => [
                        [
                            'title'  => 'Research Group Coordinator — Discrete Algorithms',
                            'org'    => 'Wolverhampton',
                            'status' => 'Current',
                            'desc'   => 'Organising weekly seminars and supervising MSc projects'
                        ],
                    ]
                ]),
            ],
        ]);

        // ────────────────────────────────────────────────
        // 6. SUBJECTS (6 modules)
        // ────────────────────────────────────────────────
        DB::table('subjects')->insertOrIgnore([
            ['subject_id' => 1, 'name' => 'Data Structures',     'mentor_id' => 1, 'credits' => 15, 'description' => 'Core data structures'],
            ['subject_id' => 2, 'name' => 'Web Development',     'mentor_id' => 2, 'credits' => 15, 'description' => 'Frontend & backend'],
            ['subject_id' => 3, 'name' => 'Database Systems',    'mentor_id' => 3, 'credits' => 15, 'description' => 'SQL & normalisation'],
            ['subject_id' => 4, 'name' => 'Software Engineering','mentor_id' => 4, 'credits' => 15, 'description' => 'Agile & UML'],
            ['subject_id' => 5, 'name' => 'Network Security',    'mentor_id' => 6, 'credits' => 15, 'description' => 'Cybersecurity'],
            ['subject_id' => 6, 'name' => 'Mathematics III',     'mentor_id' => 5, 'credits' => 10, 'description' => 'Advanced calculus'],
        ]);

        // ────────────────────────────────────────────────
        // 7. subjects_groups_bridge_table
        // ────────────────────────────────────────────────
        // DB::table('subjects_groups_bridge_table')->insertOrIgnore([
        //     ['subject_group_id' => 1, 'subject_id' => 1, 'group_id' => 1],
        //     ['subject_group_id' => 2, 'subject_id' => 2, 'group_id' => 1],
        //     ['subject_group_id' => 3, 'subject_id' => 3, 'group_id' => 1],
        //     ['subject_group_id' => 4, 'subject_id' => 4, 'group_id' => 1],
        //     ['subject_group_id' => 5, 'subject_id' => 5, 'group_id' => 1],
        //     ['subject_group_id' => 6, 'subject_id' => 6, 'group_id' => 1],
        // ]);
        for ($groupId = 1; $groupId <= 2; $groupId++) {
            for ($subj = 1; $subj <= 6; $subj++) {
                DB::table('subjects_groups_bridge_table')->insertOrIgnore([
                    'subject_id' => $subj,
                    'group_id'   => $groupId,
                ]);
            }
        }

        // ────────────────────────────────────────────────
        // 8. TIMETABLE (exactly as in your SQL)
        // ────────────────────────────────────────────────
        // DB::table('timetable')->insertOrIgnore([
        //     ['session_id' => 1, 'subject_group_id' => 1, 'time_slot' => 1, 'day_slot' => 1, 'room_number' => 301, 'session_type' => 'Lecture', 'building' => 'Block A'],
        //     ['session_id' => 2, 'subject_group_id' => 2, 'time_slot' => 2, 'day_slot' => 2, 'room_number' => 302, 'session_type' => 'Lab',    'building' => 'Block B'],
        //     ['session_id' => 3, 'subject_group_id' => 3, 'time_slot' => 3, 'day_slot' => 3, 'room_number' => 204, 'session_type' => 'Tutorial','building' => 'Block A'],
        // ]);

        DB::table('timetable')->truncate();

        $sessions = [];
        $sessionId = 1;
        $buildings = [1 => 'Block A', 2 => 'Block B'];
        $roomBase   = [1 => 300, 2 => 400];

        for ($groupId = 1; $groupId <= 2; $groupId++) {
            // Get all subject_group_id for this group
            $bridgeIds = DB::table('subjects_groups_bridge_table')
                ->where('group_id', $groupId)
                ->pluck('subject_group_id')
                ->toArray();

            $bridgeIndex = 0;

            for ($day = 1; $day <= 5; $day++) {           // Mon–Fri
                for ($slot = 1; $slot <= 3; $slot++) {    // 3 slots per day
                    $sessions[] = [
                        'session_id'       => $sessionId++,
                        'subject_group_id' => $bridgeIds[$bridgeIndex % count($bridgeIds)],
                        'time_slot'        => $slot,
                        'day_slot'         => $day,
                        'room_number'      => $roomBase[$groupId] + ($day * 10) + $slot,
                        'session_type'     => ['Lecture','Lab','Tutorial'][array_rand(['Lecture','Lab','Tutorial'])],
                        'building'         => $buildings[$groupId],
                    ];
                    $bridgeIndex++;
                }
            }
        }
        DB::table('timetable')->insert($sessions);

        // ────────────────────────────────────────────────
        // 9. GRADES – exactly as shown
        // ────────────────────────────────────────────────
        DB::table('grades')->insertOrIgnore([
            ['grade_id' => 1, 'student_id' => 1, 'subject_id' => 1, 'grade' => 'A',   'points' => 4.0, 'percentage' => 95, 'attendance' => 95],
            ['grade_id' => 2, 'student_id' => 1, 'subject_id' => 2, 'grade' => 'B+',  'points' => 3.8, 'percentage' => 92, 'attendance' => 90],
            ['grade_id' => 3, 'student_id' => 1, 'subject_id' => 3, 'grade' => 'B',   'points' => 3.5, 'percentage' => 85, 'attendance' => 85],
            ['grade_id' => 4, 'student_id' => 1, 'subject_id' => 4, 'grade' => 'B',   'points' => 3.5, 'percentage' => 82, 'attendance' => 80],
            ['grade_id' => 5, 'student_id' => 1, 'subject_id' => 5, 'grade' => 'B+',  'points' => 3.6, 'percentage' => 88, 'attendance' => 88],
            ['grade_id' => 6, 'student_id' => 1, 'subject_id' => 6, 'grade' => 'C+',  'points' => 3.2, 'percentage' => 75, 'attendance' => 75],
        ]);
        

        // ────────────────────────────────────────────────
        // 10. ATTENDANCE – exactly 20 records as shown
        // ────────────────────────────────────────────────
        DB::table('attendance')->insertOrIgnore([
            ['attendee_id' => 1,  'student_id' => 1, 'session_id' => 1, 'session_date' => '2026-03-01', 'is_present' => true],
            ['attendee_id' => 2,  'student_id' => 1, 'session_id' => 2, 'session_date' => '2026-03-02', 'is_present' => true],
            ['attendee_id' => 3,  'student_id' => 1, 'session_id' => 3, 'session_date' => '2026-03-03', 'is_present' => true],
            ['attendee_id' => 4,  'student_id' => 1, 'session_id' => 1, 'session_date' => '2026-03-04', 'is_present' => true],
            ['attendee_id' => 5,  'student_id' => 1, 'session_id' => 2, 'session_date' => '2026-03-05', 'is_present' => false],
            ['attendee_id' => 6,  'student_id' => 1, 'session_id' => 3, 'session_date' => '2026-03-06', 'is_present' => true],
            ['attendee_id' => 7,  'student_id' => 1, 'session_id' => 1, 'session_date' => '2026-03-07', 'is_present' => true],
            ['attendee_id' => 8,  'student_id' => 1, 'session_id' => 2, 'session_date' => '2026-03-08', 'is_present' => true],
            ['attendee_id' => 9,  'student_id' => 1, 'session_id' => 3, 'session_date' => '2026-03-09', 'is_present' => true],
            ['attendee_id' => 10, 'student_id' => 1, 'session_id' => 1, 'session_date' => '2026-03-10', 'is_present' => true],
            ['attendee_id' => 11, 'student_id' => 1, 'session_id' => 2, 'session_date' => '2026-03-11', 'is_present' => true],
            ['attendee_id' => 12, 'student_id' => 1, 'session_id' => 3, 'session_date' => '2026-03-12', 'is_present' => false],
            ['attendee_id' => 13, 'student_id' => 1, 'session_id' => 1, 'session_date' => '2026-03-13', 'is_present' => true],
            ['attendee_id' => 14, 'student_id' => 1, 'session_id' => 2, 'session_date' => '2026-03-14', 'is_present' => true],
            ['attendee_id' => 15, 'student_id' => 1, 'session_id' => 3, 'session_date' => '2026-03-15', 'is_present' => true],
            ['attendee_id' => 16, 'student_id' => 1, 'session_id' => 1, 'session_date' => '2026-03-16', 'is_present' => true],
            ['attendee_id' => 17, 'student_id' => 1, 'session_id' => 2, 'session_date' => '2026-03-17', 'is_present' => true],
            ['attendee_id' => 18, 'student_id' => 1, 'session_id' => 3, 'session_date' => '2026-03-18', 'is_present' => true],
            ['attendee_id' => 19, 'student_id' => 1, 'session_id' => 1, 'session_date' => '2026-03-19', 'is_present' => true],
            ['attendee_id' => 20, 'student_id' => 1, 'session_id' => 2, 'session_date' => '2026-03-20', 'is_present' => true],
        ]);

        // ────────────────────────────────────────────────
        // 11. JOBS
        // ────────────────────────────────────────────────
        DB::table('jobs')->insertOrIgnore([
            [
                'job_id'        => 1,
                'title'         => 'Junior Developer',
                'company'       => 'TechCorp',
                'location'      => 'London',
                'type'          => 'Full-time',
                'salary'        => '£35,000',
                'deadline'      => '2026-03-15',
                'creation_time' => '2026-03-01',
                'status'        => 'active',
            ],
            [
                'job_id'        => 2,
                'title'         => 'Data Analyst Intern',
                'company'       => 'DataViz Ltd',
                'location'      => 'Remote',
                'type'          => 'Internship',
                'salary'        => '£800/mo',
                'deadline'      => '2026-03-01',
                'creation_time' => '2026-03-02',
                'status'        => 'active',
            ],
            [
                'job_id'        => 3,
                'title'         => 'UX Designer (Part-time)',
                'company'       => 'CreativeHub',
                'location'      => 'London',
                'type'          => 'Part-time',
                'salary'        => '£20/hr',
                'deadline'      => '2026-03-20',
                'creation_time' => '2026-03-03',
                'status'        => 'active',
            ],
        ]);

        // ────────────────────────────────────────────────
        // 12. EVENTS
        // ────────────────────────────────────────────────
        DB::table('events')->insertOrIgnore([
            [
                'event_id'         => 1,
                'title'            => 'Annual Career Fair 2026',
                'body'             => 'Join 50 companies',
                'registration_url' => '/register/1',
                'event_time'       => '2026-03-10 10:00:00',
                'status'           => 'active',
                'organiser'        => 'Career Centre',
                'spots'            => 200,
                'event_type'       => 'Fair',
            ],
            [
                'event_id'         => 2,
                'title'            => 'CV Writing Workshop',
                'body'             => 'Hands-on CV tips',
                'registration_url' => '/register/2',
                'event_time'       => '2026-03-05 14:00:00',
                'status'           => 'active',
                'organiser'        => 'Career Centre',
                'spots'            => 30,
                'event_type'       => 'Workshop',
            ],
            [
                'event_id'         => 3,
                'title'            => 'Mock Interviews with Google',
                'body'             => 'Real interview practice',
                'registration_url' => '/register/3',
                'event_time'       => '2026-03-18 09:00:00',
                'status'           => 'active',
                'organiser'        => 'Career Centre',
                'spots'            => 20,
                'event_type'       => 'Workshop',
            ],
        ]);

        // ────────────────────────────────────────────────
        // 13. NEWS
        // ────────────────────────────────────────────────
        DB::table('news')->insertOrIgnore([
            [
                'news_id'       => 1,
                'title'         => 'University Wins Research Award',
                'body'          => 'The university has been awarded a prestigious national research prize in recognition of its outstanding contributions to innovation and academic excellence. The award highlights the institution’s commitment to advancing knowledge across multiple disciplines, including technology, health sciences, and environmental studies. Faculty members and research teams have been actively involved in groundbreaking projects, many of which have received international attention. University leadership emphasized that this achievement reflects the collaborative effort between students, researchers, and academic staff. In addition to recognition, the award also brings increased funding opportunities, allowing further development of research facilities and support for future initiatives. Students are encouraged to participate in ongoing research programs to gain practical experience. This milestone strengthens the university’s reputation as a leading center for research and innovation, both nationally and globally.',
                'creation_time' => '2026-02-20 08:00:00',
                'category'      => 'Academic',
                'excerpt'       => 'The university has been awarded...',
            ],
            [
                'news_id'       => 2,
                'title'         => 'Career Fair 2026 – Register Now',
                'body'          => 'The annual Career Fair 2026 is set to take place on campus, bringing together over 50 leading companies from various industries. This event provides students with an excellent opportunity to connect directly with potential employers, explore internship programs, and learn about career pathways. Participating organizations include major firms in technology, finance, marketing, and engineering sectors. Attendees will have access to networking sessions, company presentations, and on-the-spot interview opportunities. Students are advised to prepare updated CVs and practice professional communication skills in advance. Registration is now open through the university portal, and early registration is recommended due to limited capacity. Career advisors will also be available during the event to provide guidance and feedback. This fair plays a crucial role in bridging the gap between academic learning and professional employment, helping students take their first steps toward successful careers.',
                'creation_time' => '2026-02-18 09:00:00',
                'category'      => 'Events',
                'excerpt'       => 'Annual career fair coming up...',
            ],
            [
                'news_id'       => 3,
                'title'         => 'Library Extended Hours',
                'body'          => 'The university has announced extended operating hours for the main library to better support students during the academic semester. Starting this week, the library will remain open until midnight on weekdays, providing additional time for study, research, and group work. This decision was made in response to student feedback requesting more flexible access to learning resources. The extended hours aim to create a more supportive academic environment, especially during midterm and final exam periods. All essential services, including computer labs, study rooms, and access to digital databases, will remain available during the extended time. Library staff will also be present to assist students with research inquiries and technical support. Students are encouraged to make full use of these extended hours to improve productivity and academic performance. This initiative reflects the university’s ongoing commitment to enhancing student learning experiences.',
                'creation_time' => '2026-02-15 10:00:00',
                'category'      => 'Campus',
                'excerpt'       => 'The main library will now be...',
            ],
        ]);

        // ────────────────────────────────────────────────
        // 14. STAFF
        // ────────────────────────────────────────────────
        DB::table('staff')->insertOrIgnore([
            ['staff_id' => 1, 'user_id' => 8, 'name' => 'Student Services', 'surname' => 'Team', 'email' => 'student.services@wlv.ac.uk', 'phone_number' => '+1 555-1000', 'job_position' => 'Student Services'],
            ['staff_id' => 2, 'user_id' => 9, 'name' => 'Academic Registry', 'surname' => 'Team', 'email' => 'registry@wlv.ac.uk',        'phone_number' => '+1 555-1001', 'job_position' => 'Academic Registry'],
            ['staff_id' => 3, 'user_id' => 10, 'name' => 'IT Helpdesk',       'surname' => 'Team', 'email' => 'it@wlv.ac.uk',              'phone_number' => '+1 555-1002', 'job_position' => 'IT Helpdesk'],
            ['staff_id' => 4, 'user_id' => 11, 'name' => 'Finance Office',    'surname' => 'Team', 'email' => 'finance@wlv.ac.uk',         'phone_number' => '+1 555-1003', 'job_position' => 'Finance Office'],
            ['staff_id' => 5, 'user_id' => 12, 'name' => 'Career Centre',     'surname' => 'Team', 'email' => 'careers@wlv.ac.uk',         'phone_number' => '+1 555-1004', 'job_position' => 'Career Centre'],
        ]);

        // ────────────────────────────────────────────────
        // 15. PARTICIPANTS
        // ────────────────────────────────────────────────
        DB::table('participants')->insertOrIgnore([
            ['participant_id' => 1, 'student_id' => 1, 'event_id' => 3, 'registration_time' => '2026-03-01 10:00:00'],
        ]);

        // ────────────────────────────────────────────────
        // 16. ASSIGNMENTS
        // ────────────────────────────────────────────────
        DB::table('assignments')->insertOrIgnore([
            [
                'assignment_id' => 1,
                'title'         => 'Coursework 1',
                'subject_id'    => 1,
                'body'          => 'Data Structures project',
                'weight'        => 30,
                'due_time'      => '2026-03-01 23:59:00',
                'file_url'      => null,
            ],
            [
                'assignment_id' => 2,
                'title'         => 'Midterm Exam',
                'subject_id'    => 3,
                'body'          => 'Database design',
                'weight'        => 30,
                'due_time'      => '2026-03-20 23:59:00',
                'file_url'      => null,
            ],
            [
                'assignment_id' => 3,
                'title'         => 'Final Project',
                'subject_id'    => 4,
                'body'          => 'Software Engineering report',
                'weight'        => 40,
                'due_time'      => '2026-05-10 23:59:00',
                'file_url'      => null,
            ],
        ]);

        $this->command->info('✅ FullDemoDataSeeder completed — Alex Morgan + 2 groups + full timetable ready!');
    }

    
    // ====================== REUSABLE METHOD (used by both seeder and RegisterController) ======================
    public function generateDemoStudentData(int $studentId, int $groupId): void
    {
        $possibleGrades = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'];
        
        // 1. Fetch all necessary data upfront
        $subjects = DB::table('subjects_groups_bridge_table')
            ->where('group_id', $groupId)
            ->pluck('subject_id');
    
        $sessionIds = DB::table('timetable')
            ->join('subjects_groups_bridge_table as bridge', 'timetable.subject_group_id', '=', 'bridge.subject_group_id')
            ->where('bridge.group_id', $groupId)
            ->pluck('timetable.session_id')
            ->toArray();
    
        if (empty($sessionIds)) return;
    
        // 2. Generate Attendance first so we can use the real % for grades
        $attendanceData = [];
        $numRecords = rand(20, 30);
        $presentCount = 0;
    
        for ($i = 0; $i < $numRecords; $i++) {
            $isPresent = rand(1, 100) <= 88;
            if ($isPresent) $presentCount++;
    
            $attendanceData[] = [
                'student_id'   => $studentId,
                'session_id'   => $sessionIds[array_rand($sessionIds)],
                'session_date' => now()->subDays(rand(1, 60)),
                'is_present'   => $isPresent,
                // 'created_at'   => now(),
                // 'updated_at'   => now(),
            ];
        }
    
        // Bulk Insert Attendance
        DB::table('attendance')->insert($attendanceData);
        $actualAttendancePercent = round(($presentCount / $numRecords) * 100);
    
        // 3. Generate Grades using the calculated attendance percentage
        $gradeData = [];
        foreach ($subjects as $subjId) {
            $gradeData[] = [
                'student_id' => $studentId,
                'subject_id' => $subjId,
                'grade'      => $possibleGrades[array_rand($possibleGrades)],
                'points'     => round(rand(30, 40) / 10, 1),
                'percentage' => rand(76, 97),
                'attendance' => $actualAttendancePercent, // Matches the real data now
                'created_at' => now(),
                // 'updated_at' => now(),
            ];
        }
    
        // Bulk Insert Grades
        DB::table('grades')->insert($gradeData);
    
        // 4. Update the Student record
        DB::table('students')
            ->where('student_id', $studentId)
            ->update(['attendance_percentage' => $actualAttendancePercent]);
    }
}
