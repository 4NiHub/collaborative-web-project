const API_BASE_URL = 'https://api.sus.edu/v1';
const USE_MOCK     = true;


function getToken()        { return localStorage.getItem('userToken'); }
function saveToken(token)  { localStorage.setItem('userToken', token); }
function deleteToken()     { localStorage.removeItem('userToken'); }

// function isLoggedIn() {
//     return !!getToken();
// }

// function authGuard() {
//     console.log('authGuard called');
//     if (!isLoggedIn()) {
//         console.log('Not logged in → redirect to /login');
//         window.location.href = '/login';
//     } else {
//         console.log('Logged in → OK');
//     }
// }

// async function apiCall(endpoint, options = {}) {
//     const token = getToken();

//     const headers = {
//         'Content-Type': 'application/json',
//         ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
//         ...options.headers
//     };

//     if (options.body instanceof FormData) {
//         delete headers['Content-Type'];
//     }

//     try {
//         const response = await fetch(`${API_BASE_URL}${endpoint}`, {
//             ...options,
//             headers
//         });

//         if (response.status === 204) return null;

//         const data = await response.json();

//         if (response.status === 401) {
//             deleteToken();
//             window.location.href = 'index.html';
//             return;
//         }

//         if (!response.ok) {
//             const msg = data?.error?.message || `Server error (${response.status})`;
//             throw new Error(msg);
//         }

//         return data;

//     } catch (err) {
//         console.error('[API Error]', options.method || 'GET', endpoint, err.message);
//         throw err;
//     }
// }


const MOCK = {

    login: {
        data: {
            token: 'mock-jwt-token-12345',
            student: {
                id: 'STU001',
                firstName: 'Alex',
                lastName: 'Morgan',
                email: 'student@sus.edu',
                studentId: '20240001',
                programme: 'BSc Computer Science',
                year: 3,
                avatar: null
            }
        }
    },

    dashboardStats: {
        data: {
            enrolledModules: 6,
            gpa: 3.7,
            creditsCompleted: 90,
            attendancePercentage: 88,
            upcomingDeadlines: 3
        }
    },

    profile: {
        data: {
            id: 'STU001',
            firstName: 'Alex',
            lastName: 'Morgan',
            email: 'a.morgan@sus.edu',
            studentId: '20240001',
            programme: 'BSc Computer Science',
            year: 3,
            gpa: 3.7,
            creditsCompleted: 90,
            creditsRequired: 120,
            attendancePercentage: 88,
            avatar: null
        }
    },

    todaySchedule: {
        data: [
            { id: 1, moduleCode: 'CS301', moduleName: 'Data Structures',  type: 'Lecture',  startTime: '09:00', endTime: '10:30', room: 'Room 301', building: 'Block A', instructor: 'Dr. Johnson'   },
            { id: 2, moduleCode: 'CS302', moduleName: 'Web Development',  type: 'Lab',      startTime: '11:00', endTime: '13:00', room: 'Lab 3',    building: 'Block B', instructor: 'Prof. Chen'    },
            { id: 3, moduleCode: 'CS303', moduleName: 'Database Systems', type: 'Tutorial', startTime: '14:00', endTime: '15:00', room: 'Room 204', building: 'Block A', instructor: 'Dr. Rodriguez' }
        ]
    },

    modules: {
        data: [
            { id: 'M1', code: 'CS301', name: 'Data Structures',      credits: 15, grade: 'A',  progress: 75, status: 'active', instructor: { name: 'Dr. Johnson'    } },
            { id: 'M2', code: 'CS302', name: 'Web Development',      credits: 15, grade: 'B+', progress: 60, status: 'active', instructor: { name: 'Prof. Chen'     } },
            { id: 'M3', code: 'CS303', name: 'Database Systems',     credits: 15, grade: 'A-', progress: 80, status: 'active', instructor: { name: 'Dr. Rodriguez'  } },
            { id: 'M4', code: 'CS304', name: 'Software Engineering', credits: 15, grade: 'B',  progress: 55, status: 'active', instructor: { name: 'Prof. Williams' } },
            { id: 'M5', code: 'CS305', name: 'Network Security',     credits: 15, grade: 'A',  progress: 90, status: 'active', instructor: { name: 'Dr. Anderson'   } },
            { id: 'M6', code: 'MA201', name: 'Mathematics III',      credits: 10, grade: 'B+', progress: 70, status: 'active', instructor: { name: 'Prof. Taylor'   } }
        ]
    },

    transcript: {
        data: {
            studentId: '20240001',
            fullName: 'Alex Morgan',
            programme: 'BSc Computer Science',
            gpa: 3.7,
            creditsCompleted: 90,
            creditsRequired: 120,
            semesters: {
                'Semester 1 – 2024/25': [
                    { code: 'CS101', name: 'Intro to Programming',        credits: 15, grade: 'A',  gradePoints: 4.0 },
                    { code: 'CS102', name: 'Computer Architecture',       credits: 15, grade: 'B+', gradePoints: 3.3 }
                ],
                'Semester 2 – 2024/25': [
                    { code: 'CS201', name: 'Algorithms',                  credits: 15, grade: 'A-', gradePoints: 3.7 },
                    { code: 'CS202', name: 'Object Oriented Programming', credits: 15, grade: 'A',  gradePoints: 4.0 }
                ]
            }
        }
    },

    news: {
        data: [
            { id: 1, title: 'University Wins Research Award',  category: 'Academic', excerpt: 'The university has been awarded...', publishedAt: '2026-02-20', author: 'Admin'         },
            { id: 2, title: 'Career Fair 2026 – Register Now', category: 'Events',   excerpt: 'Annual career fair coming up...',    publishedAt: '2026-02-18', author: 'Career Centre' },
            { id: 3, title: 'Library Extended Hours',          category: 'Campus',   excerpt: 'The main library will now be...',    publishedAt: '2026-02-15', author: 'Admin'         }
        ],
        meta: { page: 1, limit: 10, total: 3, totalPages: 1 }
    },

    teachers: {
        data: [
            { id: 1, firstName: 'Sarah',   lastName: 'Johnson',   title: 'Dr.',   department: 'Computer Science',     email: 's.johnson@sus.edu',  phone: '+1 555-0101', subjects: ['Data Structures', 'Algorithms'],              officeLocation: 'Block A, Room 205', officeHours: 'Mon 14:00–16:00, Wed 10:00–12:00' },
            { id: 2, firstName: 'Michael', lastName: 'Chen',      title: 'Prof.', department: 'Computer Science',     email: 'm.chen@sus.edu',      phone: '+1 555-0102', subjects: ['Web Development', 'Mobile Development'],      officeLocation: 'Block B, Room 310', officeHours: 'Tue 13:00–15:00, Thu 10:00–12:00' },
            { id: 3, firstName: 'Emily',   lastName: 'Rodriguez', title: 'Dr.',   department: 'Computer Science',     email: 'e.rodriguez@sus.edu', phone: '+1 555-0103', subjects: ['Database Systems', 'Advanced Databases'],     officeLocation: 'Block A, Room 208', officeHours: 'Mon 10:00–12:00, Fri 14:00–16:00' },
            { id: 4, firstName: 'Susan',   lastName: 'Taylor',    title: 'Prof.', department: 'Mathematics',          email: 's.taylor@sus.edu',    phone: '+1 555-0104', subjects: ['Mathematics III'],                            officeLocation: 'Block C, Room 102', officeHours: 'Wed 09:00–11:00, Thu 14:00–16:00' },
            { id: 5, firstName: 'David',   lastName: 'Williams',  title: 'Prof.', department: 'Software Engineering', email: 'd.williams@sus.edu',  phone: '+1 555-0105', subjects: ['Software Engineering', 'Project Management'], officeLocation: 'Block B, Room 405', officeHours: 'Tue 10:00–12:00, Fri 13:00–15:00' },
            { id: 6, firstName: 'James',   lastName: 'Anderson',  title: 'Dr.',   department: 'Computer Science',     email: 'j.anderson@sus.edu',  phone: '+1 555-0106', subjects: ['Network Security'],                           officeLocation: 'Block A, Room 210', officeHours: 'Mon 11:00–13:00, Thu 15:00–17:00' }
        ]
    },

    jobs: {
        data: [
            { id: 1, title: 'Junior Developer',        company: 'TechCorp',    location: 'London', type: 'Full-time',  salary: '£35,000', deadline: '2026-03-15' },
            { id: 2, title: 'Data Analyst Intern',     company: 'DataViz Ltd', location: 'Remote', type: 'Internship', salary: '£800/mo', deadline: '2026-03-01' },
            { id: 3, title: 'UX Designer (Part-time)', company: 'CreativeHub', location: 'London', type: 'Part-time',  salary: '£20/hr',  deadline: '2026-03-20' }
        ],
        meta: { page: 1, limit: 10, total: 3, totalPages: 1 }
    },

    careerEvents: {
        data: [
            { id: 1, title: 'Annual Career Fair 2026',    organiser: 'Career Centre', date: '2026-03-10', time: '10:00', location: 'Main Hall',   type: 'Fair',     spots: 200, registered: false },
            { id: 2, title: 'CV Writing Workshop',         organiser: 'Career Centre', date: '2026-03-05', time: '14:00', location: 'Room 401',    type: 'Workshop', spots: 30,  registered: false },
            { id: 3, title: 'Mock Interviews with Google', organiser: 'Career Centre', date: '2026-03-18', time: '09:00', location: 'Careers Hub', type: 'Workshop', spots: 20,  registered: true  }
        ]
    },

    departments: {
        data: [
            { id: 1, name: 'Student Services',  email: 'student.services@sus.edu', phone: '+1 555-1000', location: 'Block A, Room 101'     },
            { id: 2, name: 'Academic Registry', email: 'registry@sus.edu',          phone: '+1 555-1001', location: 'Block B, Room 201'     },
            { id: 3, name: 'IT Helpdesk',        email: 'it@sus.edu',                phone: '+1 555-1002', location: 'Block C, Ground Floor' },
            { id: 4, name: 'Finance Office',     email: 'finance@sus.edu',           phone: '+1 555-1003', location: 'Block A, Room 102'     },
            { id: 5, name: 'Career Centre',      email: 'careers@sus.edu',           phone: '+1 555-1004', location: 'Block D, Room 301'     }
        ]
    }
};


// const AuthAPI = {

//     // POST /auth/login
//     login: async (email, password) => {
//         if (USE_MOCK) {
//             saveToken(MOCK.login.data.token);
//             return MOCK.login;
//         }
//         const res = await apiCall('/auth/login', {
//             method: 'POST',
//             body: JSON.stringify({ email, password })
//         });
//         if (res?.data?.token) saveToken(res.data.token);
//         return res;
//     },

//     // POST /auth/logout
//     logout: async function(){
//         try {
//             // Send POST request to Laravel's /logout route (clears server session)
//             const form = new FormData();
//             form.append('_token', document.querySelector('meta[name="csrf-token"]').content);

//             await fetch('/logout', {
//                 method: 'POST',
//                 body: form,
//                 headers: {
//                     'Accept': 'application/json',
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
//                 }
//             });

//             // Clear local storage
//             deleteToken();
//             localStorage.removeItem('darkMode');
//             localStorage.removeItem('userToken');  // if you use this too

//             // Redirect to Laravel login page
//             window.location.href = '/login';
//         } catch (err) {
//             console.error('Logout failed:', err);
//             // Fallback: still clear local data and redirect
//             deleteToken();
//             localStorage.removeItem('darkMode');
//             localStorage.removeItem('userToken');
//             window.location.href = '/login';
//         }
//     }
// };


const AuthAPI = {
    login: async (email, password) => {
        if (USE_MOCK) {
            // Mock login — save fake token
            localStorage.setItem('userToken', 'mock-jwt-token-12345');
            return MOCK.login;
        }
        // Real login would go here later
    },

    // logout: async function() {
    //     try {
    //         const form = new FormData();
    //         form.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

    //         await fetch('/logout', {
    //             method: 'POST',
    //             body: form,
    //             headers: {
    //                 'Accept': 'application/json',
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
    //             }
    //         });

    //         // Clean up client-side
    //         localStorage.removeItem('userToken');
    //         localStorage.removeItem('darkMode');

    //         window.location.href = '/login';
    //     } catch (err) {
    //         console.error('Logout failed:', err);
    //         // Force logout anyway
    //         localStorage.removeItem('userToken');
    //         localStorage.removeItem('darkMode');
    //         window.location.href = '/login';
    //     }
    // }

    logout: async function() {
    console.log('Logout button clicked — starting process');

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        console.log('CSRF token found:', csrfToken ? 'yes' : 'NO TOKEN FOUND');

        const form = new FormData();
        form.append('_token', csrfToken);

        console.log('Sending POST to /logout...');
        const response = await fetch('/logout', {
            method: 'POST',
            body: form,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        console.log('Logout response status:', response.status);

        localStorage.removeItem('userToken');
        localStorage.removeItem('darkMode');
        console.log('Local storage cleared — redirecting now');
        window.location.href = '/login';
        } catch (err) {
            console.error('Logout failed with error:', err);
            localStorage.removeItem('userToken');
            localStorage.removeItem('darkMode');
            console.log('Force redirect in catch block');
            window.location.href = '/login';
        }
    }
};


const StudentAPI = {

    // GET /student/profile
    getProfile: async () => {
        if (USE_MOCK) return MOCK.profile;
        return apiCall('/student/profile');
    },

    // PUT /student/profile
    updateProfile: async (profileData) => {
        if (USE_MOCK) return { data: { ...MOCK.profile.data, ...profileData } };
        return apiCall('/student/profile', {
            method: 'PUT',
            body: JSON.stringify(profileData)
        });
    },

    // GET /student/dashboard/stats
    getDashboardStats: async () => {
        if (USE_MOCK) return MOCK.dashboardStats;
        return apiCall('/student/dashboard/stats');
    }
};


// const TimetableAPI = {

//     // GET /timetable/today?date=YYYY-MM-DD
//     getTodaySchedule: async (date) => {
//         if (USE_MOCK) return MOCK.todaySchedule;
//         const q = date ? `?date=${date}` : '';
//         return apiCall(`/timetable/today${q}`);
//     },

//     // GET /timetable/week?startDate=YYYY-MM-DD
//     getWeeklyTimetable: async (startDate) => {
//         if (USE_MOCK) {
//             return {
//                 data: {
//                     weekStart: startDate || '2026-02-23',
//                     days: {
//                         Monday:    MOCK.todaySchedule.data,
//                         Tuesday:   [MOCK.todaySchedule.data[1]],
//                         Wednesday: [MOCK.todaySchedule.data[0]],
//                         Thursday:  [MOCK.todaySchedule.data[2]],
//                         Friday:    []
//                     }
//                 }
//             };
//         }
//         const q = startDate ? `?startDate=${startDate}` : '';
//         return apiCall(`/timetable/week${q}`);
//     },

//     // GET /timetable/groups
//     getGroups: async () => {
//         if (USE_MOCK) {
//             return {
//                 data: [
//                     { id: 'CS-301-A', name: 'CS-301-A' },
//                     { id: 'CS-301-B', name: 'CS-301-B' },
//                     { id: 'CS-302-A', name: 'CS-302-A' },
//                     { id: 'SE-201-A', name: 'SE-201-A' }
//                 ]
//             };
//         }
//         return apiCall('/timetable/groups');
//     },

//     // GET /timetable/group/:groupId/week?startDate=YYYY-MM-DD
//     getGroupWeeklyTimetable: async (groupId, startDate) => {
//         if (USE_MOCK) {
//             const MOCK_GROUPS = {
//                 'CS-301-A': {
//                     Monday:    [
//                         { title: 'Data Structures', time: '09:00 AM', location: 'Room 301', instructor: 'Dr. Johnson',  type: 'lecture' },
//                         { title: 'Web Development',  time: '11:00 AM', location: 'Lab 3',    instructor: 'Prof. Chen',   type: 'lab'     }
//                     ],
//                     Wednesday: [
//                         { title: 'Mathematics',      time: '14:00 PM', location: 'Room 203', instructor: 'Prof. Taylor', type: 'lecture' }
//                     ]
//                 },
//                 'CS-301-B': {
//                     Tuesday:   [
//                         { title: 'Data Structures', time: '10:00 AM', location: 'Room 305', instructor: 'Dr. Johnson',  type: 'lecture' },
//                         { title: 'Web Development',  time: '13:00 PM', location: 'Lab 5',    instructor: 'Prof. Chen',   type: 'lab'     }
//                     ],
//                     Wednesday: [
//                         { title: 'Mathematics',      time: '15:00 PM', location: 'Room 204', instructor: 'Prof. Taylor', type: 'lecture' }
//                     ]
//                 },
//                 'CS-302-A': {
//                     Monday: [ { title: 'Database Systems',     time: '09:00 AM', location: 'Room 302', instructor: 'Dr. Rodriguez',  type: 'lecture' } ]
//                 },
//                 'SE-201-A': {
//                     Tuesday: [ { title: 'Software Engineering', time: '11:00 AM', location: 'Room 401', instructor: 'Prof. Williams', type: 'lecture' } ]
//                 }
//             };
//             return { data: { groupId, weekStart: startDate || '2026-02-23', days: MOCK_GROUPS[groupId] || {} } };
//         }
//         const q = startDate ? `?startDate=${startDate}` : '';
//         return apiCall(`/timetable/group/${groupId}/week${q}`);
//     },

//     // GET /timetable/teacher/:teacherId/week?startDate=YYYY-MM-DD
//     getTeacherWeeklyTimetable: async (teacherId, startDate) => {
//         if (USE_MOCK) {
//             const MOCK_TEACHERS = {
//                 1: {
//                     Monday:  [
//                         { title: 'Data Structures', time: '09:00 AM', location: 'Room 301', group: 'CS-301-A', type: 'lecture' },
//                         { title: 'Algorithms',       time: '14:00 PM', location: 'Room 303', group: 'CS-202-A', type: 'lecture' }
//                     ],
//                     Tuesday: [
//                         { title: 'Data Structures', time: '10:00 AM', location: 'Room 305', group: 'CS-301-B', type: 'lecture' }
//                     ]
//                 },
//                 2: {
//                     Monday:  [
//                         { title: 'Web Development',    time: '11:00 AM', location: 'Lab 3', group: 'CS-301-A', type: 'lab' },
//                         { title: 'Mobile Development', time: '15:00 PM', location: 'Lab 4', group: 'CS-401-A', type: 'lab' }
//                     ],
//                     Tuesday: [
//                         { title: 'Web Development',    time: '13:00 PM', location: 'Lab 5', group: 'CS-301-B', type: 'lab' }
//                     ]
//                 },
//                 3: {
//                     Monday:  [ { title: 'Database Systems',   time: '09:00 AM', location: 'Room 302', group: 'CS-302-A', type: 'lecture' } ],
//                     Tuesday: [ { title: 'Advanced Databases', time: '14:00 PM', location: 'Room 304', group: 'CS-402-A', type: 'lecture' } ]
//                 },
//                 4: {
//                     Wednesday: [ { title: 'Mathematics III',  time: '10:00 AM', location: 'Room 201', group: 'CS-301-A', type: 'lecture' } ]
//                 }
//             };
//             return { data: { teacherId, weekStart: startDate || '2026-02-23', days: MOCK_TEACHERS[teacherId] || {} } };
//         }
//         const q = startDate ? `?startDate=${startDate}` : '';
//         return apiCall(`/timetable/teacher/${teacherId}/week${q}`);
//     }
// };


const TimetableAPI = {

    // GET /timetable/today?date=YYYY-MM-DD
    getTodaySchedule: async (date) => {
        if (USE_MOCK) return MOCK.todaySchedule;
        const q = date ? `?date=${date}` : '';
        return apiCall(`/timetable/today${q}`);
    },

    // GET /timetable/week?startDate=YYYY-MM-DD
    getWeeklyTimetable: async (startDate) => {
        if (USE_MOCK) {
            // Normalize mock data to match what renderCalendar() expects
            const normalizedEvents = MOCK.todaySchedule.data.map(event => ({
                title: event.moduleName || 'Untitled Class',
                time: event.startTime ? `${event.startTime}–${event.endTime || '??:??'}` : 'Time TBD',
                location: event.room || 'Location TBD',
                instructor: event.instructor || 'TBD',
                group: 'CS-301-A', // ← placeholder; update later if needed
                type: (event.type || 'lecture').toLowerCase()
            }));

            return {
                data: {
                    weekStart: startDate || '2026-02-23',
                    days: {
                        Monday:    normalizedEvents,
                        Tuesday:   [normalizedEvents[1] || {}],    // reuse events or empty
                        Wednesday: [normalizedEvents[0] || {}],
                        Thursday:  [normalizedEvents[2] || {}],
                        Friday:    [],
                        Saturday:  [],
                        Sunday:    []
                    }
                }
            };
        }
        const q = startDate ? `?startDate=${startDate}` : '';
        return apiCall(`/timetable/week${q}`);
    },

    // GET /timetable/groups
    getGroups: async () => {
        if (USE_MOCK) {
            return {
                data: [
                    { id: 'CS-301-A', name: 'CS-301-A' },
                    { id: 'CS-301-B', name: 'CS-301-B' },
                    { id: 'CS-302-A', name: 'CS-302-A' },
                    { id: 'SE-201-A', name: 'SE-201-A' }
                ]
            };
        }
        return apiCall('/timetable/groups');
    },

    // GET /timetable/group/:groupId/week?startDate=YYYY-MM-DD
    getGroupWeeklyTimetable: async (groupId, startDate) => {
        if (USE_MOCK) {
            const MOCK_GROUPS = {
                'CS-301-A': {
                    Monday: [
                        { title: 'Data Structures', time: '09:00 AM', location: 'Room 301', instructor: 'Dr. Johnson', type: 'lecture' },
                        { title: 'Web Development', time: '11:00 AM', location: 'Lab 3', instructor: 'Prof. Chen', type: 'lab' }
                        ],
                    Wednesday: [
                        { title: 'Mathematics', time: '14:00 PM', location: 'Room 203', instructor: 'Prof. Taylor', type: 'lecture' }
                    ]
                },
                'CS-301-B': {
                    Tuesday: [
                        { title: 'Data Structures', time: '10:00 AM', location: 'Room 305', instructor: 'Dr. Johnson', type: 'lecture' },
                        { title: 'Web Development', time: '13:00 PM', location: 'Lab 5', instructor: 'Prof. Chen', type: 'lab' }
                    ],
                    Wednesday: [
                        { title: 'Mathematics', time: '15:00 PM', location: 'Room 204', instructor: 'Prof. Taylor', type: 'lecture' }
                    ]
                },
                'CS-302-A': {
                    Monday: [
                        { title: 'Database Systems', time: '09:00 AM', location: 'Room 302', instructor: 'Dr. Rodriguez', type: 'lecture' }
                    ]
                },
                'SE-201-A': {
                    Tuesday: [
                        { title: 'Software Engineering', time: '11:00 AM', location: 'Room 401', instructor: 'Prof. Williams', type: 'lecture' }
                    ]
                }
            };
            return { data: { groupId, weekStart: startDate || '2026-02-23', days: MOCK_GROUPS[groupId] || {} } };
        }
        const q = startDate ? `?startDate=${startDate}` : '';
        return apiCall(`/timetable/group/${groupId}/week${q}`);
    },

    // GET /timetable/teacher/:teacherId/week?startDate=YYYY-MM-DD
    getTeacherWeeklyTimetable: async (teacherId, startDate) => {
        if (USE_MOCK) {
            const MOCK_TEACHERS = {
                1: {
                    Monday: [
                        { title: 'Data Structures', time: '09:00 AM', location: 'Room 301', group: 'CS-301-A', type: 'lecture' },
                        { title: 'Algorithms', time: '14:00 PM', location: 'Room 303', group: 'CS-202-A', type: 'lecture' }
                    ],
                    Tuesday: [
                        { title: 'Data Structures', time: '10:00 AM', location: 'Room 305', group: 'CS-301-B', type: 'lecture' }
                    ]
                },
                2: {
                    Monday: [
                        { title: 'Web Development', time: '11:00 AM', location: 'Lab 3', group: 'CS-301-A', type: 'lab' },
                        { title: 'Mobile Development', time: '15:00 PM', location: 'Lab 4', group: 'CS-401-A', type: 'lab' }
                    ],
                    Tuesday: [
                        { title: 'Web Development', time: '13:00 PM', location: 'Lab 5', group: 'CS-301-B', type: 'lab' }
                    ]
                },
                3: {
                    Monday: [
                        { title: 'Database Systems', time: '09:00 AM', location: 'Room 302', group: 'CS-302-A', type: 'lecture' }
                    ],
                    Tuesday: [
                        { title: 'Advanced Databases', time: '14:00 PM', location: 'Room 304', group: 'CS-402-A', type: 'lecture' }
                    ]
                },
                4: {
                    Wednesday: [
                        { title: 'Mathematics III', time: '10:00 AM', location: 'Room 201', group: 'CS-301-A', type: 'lecture' }
                    ]
                }
            };
            return { data: { teacherId, weekStart: startDate || '2026-02-23', days: MOCK_TEACHERS[teacherId] || {} } };
        }
        const q = startDate ? `?startDate=${startDate}` : '';
        return apiCall(`/timetable/teacher/${teacherId}/week${q}`);
    }
};


const ModuleAPI = {

    // GET /modules?semester=current
    getEnrolledModules: async (semester = 'current') => {
        if (USE_MOCK) return MOCK.modules;
        return apiCall(`/modules?semester=${semester}`);
    },

    // GET /modules/:id
    getModuleDetails: async (moduleId) => {
        if (USE_MOCK) {
            const found = MOCK.modules.data.find(m => m.id === moduleId) || MOCK.modules.data[0];
            return {
                data: {
                    ...found,
                    description: 'In-depth study of core data structures and algorithmic design.',
                    learningOutcomes: [
                        'Implement core data structures',
                        'Analyse algorithm complexity',
                        'Apply sorting and searching techniques'
                    ],
                    assessments: [
                        { id: 'A1', title: 'Coursework 1', type: 'Assignment', weight: 30, dueDate: '2026-03-01', status: 'pending'  },
                        { id: 'A2', title: 'Midterm Exam', type: 'Exam',       weight: 30, dueDate: '2026-03-20', status: 'upcoming' },
                        { id: 'A3', title: 'Final Exam',   type: 'Exam',       weight: 40, dueDate: '2026-05-10', status: 'upcoming' }
                    ],
                    resources: [
                        { id: 'R1', title: 'Lecture Slides Week 1', type: 'pdf',  url: '#', uploadedAt: '2026-02-01' },
                        { id: 'R2', title: 'Lab Sheet 1',           type: 'docx', url: '#', uploadedAt: '2026-02-05' }
                    ]
                }
            };
        }
        return apiCall(`/modules/${moduleId}`);
    }
};


const RecordsAPI = {

    // GET /records/transcript
    getTranscript: async () => {
        if (USE_MOCK) return MOCK.transcript;
        return apiCall('/records/transcript');
    },

    // GET /records/attendance?moduleId=&from=&to=
    getAttendance: async (moduleId, from, to) => {
        if (USE_MOCK) {
            return {
                data: [
                    { moduleId: 'M1', moduleName: 'Data Structures',      attended: 22, total: 25, percentage: 88 },
                    { moduleId: 'M2', moduleName: 'Web Development',      attended: 18, total: 20, percentage: 90 },
                    { moduleId: 'M3', moduleName: 'Database Systems',     attended: 15, total: 20, percentage: 75 },
                    { moduleId: 'M4', moduleName: 'Software Engineering', attended: 17, total: 22, percentage: 77 },
                    { moduleId: 'M5', moduleName: ' work Security',     attended: 21, total: 22, percentage: 95 },
                    { moduleId: 'M6', moduleName: 'Mathematics III',      attended: 14, total: 18, percentage: 78 }
                ]
            };
        }
        const params = new URLSearchParams();
        if (moduleId) params.set('moduleId', moduleId);
        if (from)     params.set('from', from);
        if (to)       params.set('to', to);
        const q = params.toString() ? `?${params}` : '';
        return apiCall(`/records/attendance${q}`);
    }
};


const NewsAPI = {

    // GET /news?page=1&limit=10&category=
    getArticles: async (page = 1, category = null, limit = 10) => {
        if (USE_MOCK) return MOCK.news;
        const params = new URLSearchParams({ page, limit });
        if (category) params.set('category', category);
        return apiCall(`/news?${params}`);
    },

    // GET /news/:id
    getArticle: async (articleId) => {
        if (USE_MOCK) {
            const found = MOCK.news.data.find(a => a.id == articleId) || MOCK.news.data[0];
            return { data: { ...found, content: '<p>Full article content goes here once backend is connected.</p>' } };
        }
        return apiCall(`/news/${articleId}`);
    },

    // POST /news/:id/bookmark
    toggleBookmark: async (articleId) => {
        if (USE_MOCK) return { data: { bookmarked: true } };
        return apiCall(`/news/${articleId}/bookmark`, { method: 'POST' });
    }
};


const TeacherAPI = {

    // GET /teachers?department=&search=
    getTeachers: async (department = null, search = null) => {
        if (USE_MOCK) return MOCK.teachers;
        const params = new URLSearchParams();
        if (department) params.set('department', department);
        if (search)     params.set('search', search);
        const q = params.toString() ? `?${params}` : '';
        return apiCall(`/teachers${q}`);
    },

    // GET /teachers/:id
    getTeacherProfile: async (teacherId) => {
        if (USE_MOCK) {
            const found = MOCK.teachers.data.find(t => t.id == teacherId) || MOCK.teachers.data[0];
            return {
                data: {
                    ...found,
                    bio: 'Expert in computer science with 10+ years of teaching experience.',
                    nationality: 'British',
                    languages: ['English'],
                    experience: [
                        { title: 'Professor', org: 'Smart University System', period: '2020 – Present', desc: 'Teaching and research.' }
                    ],
                    education: [
                        { degree: 'PhD Computer Science', school: 'University of London', period: '2010 – 2014' }
                    ],
                    roles: [
                        { title: 'Lecturer', org: 'Smart University System', status: 'Current', desc: 'Core module delivery.' }
                    ]
                }
            };
        }
        return apiCall(`/teachers/${teacherId}`);
    }
};



const CareerAPI = {

    // GET /career/jobs?page=1&type=&location=&search=
    getJobs: async (page = 1, type = null, location = null, search = null) => {
        if (USE_MOCK) return MOCK.jobs;
        const params = new URLSearchParams({ page });
        if (type)     params.set('type', type);
        if (location) params.set('location', location);
        if (search)   params.set('search', search);
        return apiCall(`/career/jobs?${params}`);
    },

    // GET /career/jobs/:id
    getJobDetails: async (jobId) => {
        if (USE_MOCK) {
            const found = MOCK.jobs.data.find(j => j.id == jobId) || MOCK.jobs.data[0];
            return { data: { ...found, description: 'We are looking for a motivated developer...', requirements: ['JavaScript', 'React', '1+ year experience'] } };
        }
        return apiCall(`/career/jobs/${jobId}`);
    },

    // POST /career/jobs/:id/apply
    applyForJob: async (jobId, coverLetter = '', cvUrl = '') => {
        if (USE_MOCK) return { data: { message: 'Application submitted (mock)' } };
        return apiCall(`/career/jobs/${jobId}/apply`, {
            method: 'POST',
            body: JSON.stringify({ coverLetter, cvUrl })
        });
    },

    // GET /career/events
    getEvents: async () => {
        if (USE_MOCK) return MOCK.careerEvents;
        return apiCall('/career/events');
    },

    // POST /career/events/:id/register
    registerForEvent: async (eventId) => {
        if (USE_MOCK) return { data: { registered: true, message: 'Registered successfully (mock)' } };
        return apiCall(`/career/events/${eventId}/register`, { method: 'POST' });
    },

    // DELETE /career/events/:id/register
    cancelEventRegistration: async (eventId) => {
        if (USE_MOCK) return { data: { registered: false } };
        return apiCall(`/career/events/${eventId}/register`, { method: 'DELETE' });
    }
};


const ContactAPI = {

    // GET /contact/departments
    getDepartments: async () => {
        if (USE_MOCK) return MOCK.departments;
        return apiCall('/contact/departments');
    },

    // POST /contact/submit
    submitForm: async (formData) => {
        if (USE_MOCK) return { data: { ticketId: 'TKT-001', message: 'Message sent (mock)' } };
        return apiCall('/contact/submit', {
            method: 'POST',
            body: JSON.stringify(formData)
        });
    }
};