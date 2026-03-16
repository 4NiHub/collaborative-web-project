const API_BASE_URL = 'https://api.sus.edu/v1';
const USE_MOCK     = true;


function getToken()        { return localStorage.getItem("userToken"); }
function saveToken(token)  { localStorage.setItem('userToken', token); }
function deleteToken()     { localStorage.removeItem('userToken'); }

function isLoggedIn() {
    return !!getToken();
}

function authGuard() {
    if (!isLoggedIn()) {
        window.location.href = 'index.html';
    }
}

async function apiCall(endpoint, options = {}) {
    const token = getToken();

    const headers = {
        'Content-Type': 'application/json',
        ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        ...options.headers
    };

    if (options.body instanceof FormData) {
        delete headers['Content-Type'];
    }

    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            ...options,
            headers
        });

        if (response.status === 204) return null;

        const data = await response.json();

        if (response.status === 401) {
            deleteToken();
            window.location.href = 'index.html';
            return;
        }

        if (!response.ok) {
            const msg = data?.error?.message || `Server error (${response.status})`;
            throw new Error(msg);
        }

        return data;

    } catch (err) {
        console.error('[API Error]', options.method || 'GET', endpoint, err.message);
        throw err;
    }
}


const MOCK = {

    login: {
        data: {
            token: 'mock-jwt-token-teacher-001',
            teacher: {
                id: 1,
                firstName: 'Sarah',
                lastName: 'Johnson',
                title: 'Dr.',
                email: 'sjohnson@sus.edu',
                department: 'Computer Science',
                avatar: null
            }
        }
    },

    teacherProfile: {
        data: {
            id: 1,
            firstName: 'Sarah',
            lastName: 'Johnson',
            title: 'Dr.',
            email: 'sjohnson@sus.edu',
            phone: '+1 555-0101',
            department: 'Computer Science',
            office: 'Block A, Room 205',
            officeHours: 'Mon 14:00-16:00, Wed 10:00-12:00',
            nationality: 'British',
            languages: 'English, French',
            subjects: ['Data Structures', 'Algorithms', 'Computational Theory'],
            bio: 'Expert in data structures, algorithms and computational theory with over 12 years of teaching and research experience.',
            experience: [
                { title: 'Senior Lecturer', org: 'Smart University System', link: 'https://sus.edu', period: '2020 - Present', desc: 'Core module delivery, curriculum design and postgraduate supervision.' },
                { title: 'Lecturer', org: 'University of Manchester', link: 'https://manchester.ac.uk', period: '2015 - 2020', desc: 'Undergraduate teaching in algorithms and data structures.' }
            ],
            education: [
                { degree: 'PhD Computer Science', school: 'University of London', link: 'https://london.ac.uk', period: '2010 - 2014' },
                { degree: 'MSc Advanced Computing', school: 'University of Bristol', link: 'https://bristol.ac.uk', period: '2008 - 2010' }
            ]
        }
    },

    dashboardStats: {
        data: {
            upcomingClasses: 5,
            newSubmissions: 12,
            totalStudents: 248,
            coursesAssigned: 6
        }
    },

    todayClasses: {
        data: [
            { id: 1, title: 'Data Structures',  type: 'LECTURE',  startTime: '09:00', endTime: '10:00', room: 'Room 301, Block A', code: 'CS-301-A' },
            { id: 2, title: 'Algorithms',        type: 'TUTORIAL', startTime: '11:00', endTime: '12:00', room: 'Room 303, Block A', code: 'CS-202-A' },
            { id: 3, title: 'Data Structures',  type: 'LECTURE',  startTime: '13:00', endTime: '14:00', room: 'Room 305, Block A', code: 'CS-301-B' },
            { id: 4, title: 'Office Hours',      type: 'OFFICE',   startTime: '14:00', endTime: '16:00', room: 'Block A, Room 205', code: 'Open'    },
            { id: 5, title: 'Algorithms',        type: 'LAB',      startTime: '16:00', endTime: '17:00', room: 'Lab 2, Block B',   code: 'CS-202-B' }
        ]
    },

    recentActivity: {
        data: [
            { id: 1, student: 'Emma Johnson',  action: 'HW 3 - Integrals submitted', module: 'Data Structures', time: '8 min ago',  status: 'submission', checked: false },
            { id: 2, student: 'Liam Park',     action: 'Lab Report 2 submitted',     module: 'Algorithms',      time: '22 min ago', status: 'submission', checked: false },
            { id: 3, student: 'Priya Sharma',  action: 'Quiz 1 graded: 87/100',      module: 'Data Structures', time: '1 hr ago',   status: 'graded',     checked: true  },
            { id: 4, student: 'Tom Baker',     action: 'Final Project submitted',     module: 'Web Development', time: '2 hrs ago',  status: 'submission', checked: false },
            { id: 5, student: 'Chloe Nguyen',  action: 'Question about deadline',    module: 'Algorithms',      time: '3 hrs ago',  status: 'message',    checked: false }
        ]
    },

    modules: {
        data: [
            { id: 'M1', code: 'CS301', name: 'Data Structures',      credits: 15, groups: ['CS-301-A', 'CS-301-B'], totalStudents: 42, status: 'active' },
            { id: 'M2', code: 'CS302', name: 'Algorithms',           credits: 15, groups: ['CS-202-A', 'CS-202-B'], totalStudents: 38, status: 'active' },
            { id: 'M3', code: 'CS303', name: 'Computational Theory', credits: 15, groups: ['CS-303-A'],             totalStudents: 20, status: 'active' },
            { id: 'M4', code: 'CS304', name: 'Web Development',      credits: 15, groups: ['WD-101-A'],             totalStudents: 35, status: 'active' },
            { id: 'M5', code: 'CS305', name: 'Network Security',     credits: 15, groups: ['NS-201-A'],             totalStudents: 28, status: 'active' },
            { id: 'M6', code: 'MA201', name: 'Mathematics III',      credits: 10, groups: ['MA-201-A'],             totalStudents: 45, status: 'active' }
        ]
    },

    moduleDetails: {
        'M1': {
            id: 'M1', code: 'CS301', name: 'Data Structures', credits: 15,
            groups: ['CS-301-A', 'CS-301-B'],
            students: [
                { id: '20240001', name: 'Emma Johnson',  group: 'CS-301-A', quiz1: 71, midterm: 72, hw3: 79,   lab1: 81,   finalExam: null, grade: 'B+', status: 'PASS' },
                { id: '20240002', name: 'Liam Park',     group: 'CS-301-A', quiz1: 85, midterm: 88, hw3: 91,   lab1: 90,   finalExam: null, grade: 'A',  status: 'PASS' },
                { id: '20240003', name: 'Priya Sharma',  group: 'CS-301-B', quiz1: 60, midterm: 63, hw3: 55,   lab1: 70,   finalExam: null, grade: 'C',  status: 'PASS' },
                { id: '20240004', name: 'Tom Baker',     group: 'CS-301-B', quiz1: 40, midterm: 44, hw3: null, lab1: null, finalExam: null, grade: 'D',  status: 'FAIL' },
                { id: '20240005', name: 'Emma Wilson',   group: 'CS-301-A', quiz1: 80, midterm: 84, hw3: 87,   lab1: 85,   finalExam: null, grade: 'A-', status: 'PASS' },
                { id: '20240006', name: 'Sofia Rossi',   group: 'CS-301-A', quiz1: 73, midterm: 75, hw3: 78,   lab1: null, finalExam: null, grade: 'B',  status: 'PASS' },
                { id: '20240007', name: 'Marcus Owen',   group: 'CS-301-B', quiz1: 55, midterm: 58, hw3: 62,   lab1: 60,   finalExam: null, grade: 'C+', status: 'PASS' },
                { id: '20240008', name: 'Chloe Nguyen',  group: 'CS-301-B', quiz1: 35, midterm: 38, hw3: null, lab1: null, finalExam: null, grade: 'F',  status: 'FAIL' }
            ],
            submissions: [
                { student: 'Emma Johnson',  group: 'CS-301-A', assignment: 'HW 3 - Integrals', date: 'Mar 4, 2026', file: 'hw3_emma.pdf'    },
                { student: 'Liam Park',     group: 'CS-301-A', assignment: 'HW 3 - Integrals', date: 'Mar 5, 2026', file: 'hw3_liam.pdf'    },
                { student: 'Priya Sharma',  group: 'CS-301-B', assignment: 'HW 3 - Integrals', date: 'Mar 4, 2026', file: 'hw3_priya.pdf'   },
                { student: 'Emma Wilson',   group: 'CS-301-A', assignment: 'HW 3 - Integrals', date: 'Mar 4, 2026', file: 'hw3_ewilson.pdf' },
                { student: 'Sofia Rossi',   group: 'CS-301-A', assignment: 'HW 3 - Integrals', date: 'Mar 4, 2026', file: 'hw3_sofia.pdf'   },
                { student: 'Marcus Owen',   group: 'CS-301-B', assignment: 'HW 3 - Integrals', date: 'Mar 4, 2026', file: 'hw2_marcus.pdf'  }
            ],
            attendance: {
                groups: ['CS-301-A', 'CS-301-B'],
                sessions: {
                    'CS-301-A': [
                        { date: '2026-02-03', time: '09:00', students: [
                            { id: '20240001', name: 'Emma Johnson', status: 'present' },
                            { id: '20240002', name: 'Liam Park',    status: 'present' },
                            { id: '20240005', name: 'Emma Wilson',  status: 'present' },
                            { id: '20240006', name: 'Sofia Rossi',  status: 'absent'  }
                        ]},
                        { date: '2026-02-10', time: '09:00', students: [
                            { id: '20240001', name: 'Emma Johnson', status: 'present' },
                            { id: '20240002', name: 'Liam Park',    status: 'present' },
                            { id: '20240005', name: 'Emma Wilson',  status: 'present' },
                            { id: '20240006', name: 'Sofia Rossi',  status: 'present' }
                        ]},
                        { date: '2026-02-17', time: '09:00', students: [
                            { id: '20240001', name: 'Emma Johnson', status: 'present' },
                            { id: '20240002', name: 'Liam Park',    status: 'present' },
                            { id: '20240005', name: 'Emma Wilson',  status: 'leave'   },
                            { id: '20240006', name: 'Sofia Rossi',  status: 'absent'  }
                        ]},
                        { date: '2026-02-24', time: '09:00', students: [
                            { id: '20240001', name: 'Emma Johnson', status: 'present' },
                            { id: '20240002', name: 'Liam Park',    status: 'present' },
                            { id: '20240005', name: 'Emma Wilson',  status: 'present' },
                            { id: '20240006', name: 'Sofia Rossi',  status: 'present' }
                        ]},
                        { date: '2026-03-03', time: '09:00', students: [
                            { id: '20240001', name: 'Emma Johnson', status: 'present' },
                            { id: '20240002', name: 'Liam Park',    status: 'present' },
                            { id: '20240005', name: 'Emma Wilson',  status: 'absent'  },
                            { id: '20240006', name: 'Sofia Rossi',  status: 'present' }
                        ]}
                    ],
                    'CS-301-B': [
                        { date: '2026-02-03', time: '09:00', students: [
                            { id: '20240003', name: 'Priya Sharma', status: 'present' },
                            { id: '20240004', name: 'Tom Baker',    status: 'absent'  },
                            { id: '20240007', name: 'Marcus Owen',  status: 'present' },
                            { id: '20240008', name: 'Chloe Nguyen', status: 'absent'  }
                        ]},
                        { date: '2026-02-10', time: '09:00', students: [
                            { id: '20240003', name: 'Priya Sharma', status: 'absent'  },
                            { id: '20240004', name: 'Tom Baker',    status: 'absent'  },
                            { id: '20240007', name: 'Marcus Owen',  status: 'present' },
                            { id: '20240008', name: 'Chloe Nguyen', status: 'present' }
                        ]},
                        { date: '2026-02-17', time: '09:00', students: [
                            { id: '20240003', name: 'Priya Sharma', status: 'present' },
                            { id: '20240004', name: 'Tom Baker',    status: 'leave'   },
                            { id: '20240007', name: 'Marcus Owen',  status: 'present' },
                            { id: '20240008', name: 'Chloe Nguyen', status: 'absent'  }
                        ]},
                        { date: '2026-02-24', time: '09:00', students: [
                            { id: '20240003', name: 'Priya Sharma', status: 'present' },
                            { id: '20240004', name: 'Tom Baker',    status: 'present' },
                            { id: '20240007', name: 'Marcus Owen',  status: 'absent'  },
                            { id: '20240008', name: 'Chloe Nguyen', status: 'present' }
                        ]},
                        { date: '2026-03-03', time: '09:00', students: [
                            { id: '20240003', name: 'Priya Sharma', status: 'absent'  },
                            { id: '20240004', name: 'Tom Baker',    status: 'present' },
                            { id: '20240007', name: 'Marcus Owen',  status: 'present' },
                            { id: '20240008', name: 'Chloe Nguyen', status: 'absent'  }
                        ]}
                    ]
                }
            },
            grades: {
                'Emma Johnson':  { score: 79,   feedback: 'Good work overall. Watch indexing errors.' },
                'Liam Park':     { score: 91,   feedback: 'Excellent submission.' },
                'Priya Sharma':  { score: null, feedback: '' },
                'Emma Wilson':   { score: 87,   feedback: 'Well structured. Minor formatting issues.' },
                'Sofia Rossi':   { score: 78,   feedback: 'Satisfactory.' },
                'Marcus Owen':   { score: 62,   feedback: 'Needs improvement on edge cases.' }
            }
        }
    },

    timetableMyWeek: {
        data: {
            days: {
                Monday:    [
                    { title: 'Data Structures', time: '09:00', location: 'Room 301, Block A', group: 'CS-301-A', type: 'lecture' },
                    { title: 'Algorithms',       time: '14:00', location: 'Room 303, Block A', group: 'CS-202-A', type: 'lecture' }
                ],
                Tuesday:   [
                    { title: 'Data Structures', time: '10:00', location: 'Room 305, Block A', group: 'CS-301-B', type: 'lecture' },
                    { title: 'Office Hours',     time: '13:00', location: 'Block A, Room 205',  group: 'Open',    type: 'office'  }
                ],
                Wednesday: [
                    { title: 'Algorithms', time: '09:00', location: 'Room 303, Block A', group: 'CS-202-A', type: 'tutorial' }
                ],
                Thursday:  [
                    { title: 'Data Structures', time: '11:00', location: 'Lab 2, Block B', group: 'CS-301-B', type: 'lab' },
                    { title: 'Algorithms',       time: '14:00', location: 'Lab 2, Block B', group: 'CS-202-A', type: 'lab'  }
                ],
                Friday: [], Saturday: [], Sunday: []
            }
        }
    },

    groups: {
        data: [
            { id: 'CS-301-A', name: 'CS-301-A' },
            { id: 'CS-301-B', name: 'CS-301-B' },
            { id: 'CS-202-A', name: 'CS-202-A' },
            { id: 'CS-202-B', name: 'CS-202-B' }
        ]
    },

    news: {
        data: [
            { id: 1, title: 'University Wins Research Award',  category: 'Academic', excerpt: 'The university has been awarded...', publishedAt: '2026-02-20', author: 'Admin'         },
            { id: 2, title: 'Career Fair 2026 - Register Now', category: 'Events',   excerpt: 'Annual career fair coming up...',    publishedAt: '2026-02-18', author: 'Career Centre' },
            { id: 3, title: 'Library Extended Hours',          category: 'Campus',   excerpt: 'The main library will now be...',    publishedAt: '2026-02-15', author: 'Admin'         }
        ],
        meta: { page: 1, limit: 10, total: 3, totalPages: 1 }
    },

    jobs: {
        data: [
            { id: 1, title: 'Junior Developer',        company: 'TechCorp',    location: 'London', type: 'Full-time',  salary: '35,000', deadline: '2026-03-15' },
            { id: 2, title: 'Data Analyst Intern',     company: 'DataViz Ltd', location: 'Remote', type: 'Internship', salary: '800/mo', deadline: '2026-03-01' },
            { id: 3, title: 'UX Designer (Part-time)', company: 'CreativeHub', location: 'London', type: 'Part-time',  salary: '20/hr',  deadline: '2026-03-20' }
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


const AuthAPI = {

    login: async (email, password) => {
        if (USE_MOCK) {
            saveToken(MOCK.login.data.token);
            return MOCK.login;
        }
        const res = await apiCall('/auth/login', {
            method: 'POST',
            body: JSON.stringify({ email, password })
        });
        if (res?.data?.token) saveToken(res.data.token);
        return res;
    },

    logout: async () => {
        if (!USE_MOCK) {
            try { await apiCall('/auth/logout', { method: 'POST' }); } catch (_) {}
        }
        deleteToken();
        localStorage.removeItem('darkMode');
        window.location.href = 'index.html';
    }
};


const TimetableAPI = {

    getMyWeeklyTimetable: async (startDate) => {
        if (USE_MOCK) return MOCK.timetableMyWeek;
        const q = startDate ? `?startDate=${startDate}` : '';
        return apiCall(`/teacher/timetable/week${q}`);
    },

    getGroups: async () => {
        if (USE_MOCK) return MOCK.groups;
        return apiCall('/timetable/groups');
    },

    getGroupWeeklyTimetable: async (groupId, startDate) => {
        if (USE_MOCK) {
            const MOCK_GROUPS = {
                'CS-301-A': {
                    Monday:    [{ title: 'Data Structures', time: '09:00', location: 'Room 301', group: 'CS-301-A', type: 'lecture' }],
                    Wednesday: [{ title: 'Mathematics',      time: '14:00', location: 'Room 203', group: 'CS-301-A', type: 'lecture' }]
                },
                'CS-301-B': {
                    Tuesday:   [{ title: 'Data Structures', time: '10:00', location: 'Room 305', group: 'CS-301-B', type: 'lecture' }],
                    Thursday:  [{ title: 'Lab Session',      time: '11:00', location: 'Lab 2',    group: 'CS-301-B', type: 'lab'     }]
                },
                'CS-202-A': {
                    Monday:    [{ title: 'Algorithms', time: '14:00', location: 'Room 303', group: 'CS-202-A', type: 'lecture'  }],
                    Wednesday: [{ title: 'Algorithms', time: '09:00', location: 'Room 303', group: 'CS-202-A', type: 'tutorial' }]
                },
                'CS-202-B': {
                    Friday:    [{ title: 'Algorithms', time: '10:00', location: 'Room 303', group: 'CS-202-B', type: 'lecture' }]
                }
            };
            const today  = new Date();
            const dow    = today.getDay();
            const diff   = (dow === 0) ? -6 : 1 - dow;
            const monday = new Date(today);
            monday.setDate(today.getDate() + diff);
            return { data: { groupId, weekStart: startDate || monday.toISOString().slice(0,10), days: MOCK_GROUPS[groupId] || {} } };
        }
        const q = startDate ? `?startDate=${startDate}` : '';
        return apiCall(`/timetable/group/${groupId}/week${q}`);
    }
};


const TeacherAPI = {

    getProfile: async () => {
        if (USE_MOCK) return MOCK.teacherProfile;
        return apiCall('/teacher/profile');
    },

    updateProfile: async (profileData) => {
        if (USE_MOCK) return { data: { ...MOCK.teacherProfile.data, ...profileData } };
        return apiCall('/teacher/profile', { method: 'PUT', body: JSON.stringify(profileData) });
    },

    getDashboardStats: async () => {
        if (USE_MOCK) return MOCK.dashboardStats;
        return apiCall('/teacher/dashboard/stats');
    },

    getTodayClasses: async () => {
        if (USE_MOCK) return MOCK.todayClasses;
        return apiCall('/teacher/timetable/today');
    },

    getRecentActivity: async () => {
        if (USE_MOCK) return MOCK.recentActivity;
        return apiCall('/teacher/activity/recent');
    },

    getModules: async () => {
        if (USE_MOCK) return MOCK.modules;
        return apiCall('/teacher/modules');
    },

    getModuleDetails: async (moduleId) => {
        if (USE_MOCK) {
            const detail = MOCK.moduleDetails[moduleId] || MOCK.moduleDetails['M1'];
            return { data: detail };
        }
        return apiCall(`/teacher/modules/${moduleId}`);
    },
    getTeacherWeeklyTimetable: async (teacherId, startDate) => {
        if (USE_MOCK) return { data: { days: {} } }; // replaced by real call
        const q = startDate ? `?startDate=${startDate}` : '';
        return apiCall(`/timetable/teacher/${teacherId}/week${q}`);
},

    // POST /teacher/modules/:id/attendance 
    saveAttendance: async (moduleId, groupId, date, time, students) => {
        if (USE_MOCK) {
            return { data: { message: 'Attendance saved (mock)', moduleId, groupId, date, time, students } };
        }
        return apiCall(`/teacher/modules/${moduleId}/attendance`, {
            method: 'POST',
            body: JSON.stringify({ groupId, date, time, students })
        });
    },

    // PUT /teacher/modules/:id/attendance/:sessionId 
    updateAttendance: async (moduleId, sessionId, students) => {
        if (USE_MOCK) {
            return { data: { message: 'Attendance updated (mock)', moduleId, sessionId, students } };
        }
        return apiCall(`/teacher/modules/${moduleId}/attendance/${sessionId}`, {
            method: 'PUT',
            body: JSON.stringify({ students })
        });
    },

    // POST /teacher/modules/:id/grade 
    gradeSubmission: async (moduleId, studentId, assignmentId, score, feedback) => {
        if (USE_MOCK) {
            return { data: { message: 'Grade saved (mock)', moduleId, studentId, assignmentId, score, feedback } };
        }
        return apiCall(`/teacher/modules/${moduleId}/grade`, {
            method: 'POST',
            body: JSON.stringify({ studentId, assignmentId, score, feedback })
        });
    }
    
};


const NewsAPI = {

    getArticles: async (page = 1, category = null, limit = 10) => {
        if (USE_MOCK) return MOCK.news;
        const params = new URLSearchParams({ page, limit });
        if (category) params.set('category', category);
        return apiCall(`/news?${params}`);
    },

    getArticle: async (articleId) => {
        if (USE_MOCK) {
            const found = MOCK.news.data.find(a => a.id == articleId) || MOCK.news.data[0];
            return { data: { ...found, content: '<p>Full article content goes here once backend is connected.</p>' } };
        }
        return apiCall(`/news/${articleId}`);
    },

    toggleBookmark: async (articleId) => {
        if (USE_MOCK) return { data: { bookmarked: true } };
        return apiCall(`/news/${articleId}/bookmark`, { method: 'POST' });
    }
};


const CareerAPI = {

    getJobs: async (page = 1, type = null, location = null, search = null) => {
        if (USE_MOCK) return MOCK.jobs;
        const params = new URLSearchParams({ page });
        if (type)     params.set('type', type);
        if (location) params.set('location', location);
        if (search)   params.set('search', search);
        return apiCall(`/career/jobs?${params}`);
    },

    getJobDetails: async (jobId) => {
        if (USE_MOCK) {
            const found = MOCK.jobs.data.find(j => j.id == jobId) || MOCK.jobs.data[0];
            return { data: { ...found, description: 'We are looking for a motivated candidate...', requirements: ['Relevant degree', '1+ year experience'] } };
        }
        return apiCall(`/career/jobs/${jobId}`);
    },

    applyForJob: async (jobId, coverLetter = '', cvUrl = '') => {
        if (USE_MOCK) return { data: { message: 'Application submitted (mock)' } };
        return apiCall(`/career/jobs/${jobId}/apply`, {
            method: 'POST',
            body: JSON.stringify({ coverLetter, cvUrl })
        });
    },

    getEvents: async () => {
        if (USE_MOCK) return MOCK.careerEvents;
        return apiCall('/career/events');
    },

    registerForEvent: async (eventId) => {
        if (USE_MOCK) return { data: { registered: true, message: 'Registered successfully (mock)' } };
        return apiCall(`/career/events/${eventId}/register`, { method: 'POST' });
    },

    cancelEventRegistration: async (eventId) => {
        if (USE_MOCK) return { data: { registered: false } };
        return apiCall(`/career/events/${eventId}/register`, { method: 'DELETE' });
    }
};


const ContactAPI = {
    getDepartments: async () => {
        if (USE_MOCK) return MOCK.departments;
        return apiCall('/contact/departments');
    },

    submitForm: async (formData) => {
        if (USE_MOCK) return { data: { ticketId: 'TKT-001', message: 'Message sent (mock)' } };
        return apiCall('/contact/submit', {
            method: 'POST',
            body: JSON.stringify(formData)
        });
    }
};