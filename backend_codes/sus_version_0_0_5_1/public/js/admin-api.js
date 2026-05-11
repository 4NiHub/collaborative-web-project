const API_BASE_URL = '/api/v1/admin'; // Points to your new Laravel Admin API routes
const USE_MOCK     = false;           // Turns OFF the fake data

// Use the exact same token storage as your main api.js
function getAdminToken()   { return localStorage.getItem('auth_token'); }
function deleteAdminToken(){ 
    localStorage.removeItem('auth_token');
    localStorage.removeItem('userToken'); 
    localStorage.removeItem('userRole'); 
}

// We completely removed adminAuthGuard()! Laravel handles security now.

async function adminApiCall(endpoint, options = {}) {
    const token = getAdminToken();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        ...options.headers
    };

    if (!(options.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
    }

    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, { ...options, headers });
        
        if (response.status === 204) return null;
        
        // If unauthorized, clear tokens and go to the main login page
        if (response.status === 401) { 
            deleteAdminToken(); 
            window.location.href = '/login'; 
            return null; 
        }
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data?.message || data?.error?.message || `Server error (${response.status})`);
        }
        return data;
        
    } catch (err) {
        console.error('[AdminAPI Error]', options.method || 'GET', endpoint, err.message);
        throw err;
    }
}

const AdminAuthAPI = {
    logout: async function() {
        const token = getAdminToken();
        try {
            // Hit the exact same logout route as api.js
            await fetch('/logout', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });
        } catch (err) {
            console.warn("Server logout failed, cleaning up locally.");
        }
        deleteAdminToken();
        window.location.href = '/login'; 
    }
};

const ADMIN_MOCK = {
    rooms: {
        data: [
            { id: 'R01', name: 'Room 101',  building: 'Block A', capacity: 40,  type: 'Classroom' },
            { id: 'R02', name: 'Room 201',  building: 'Block A', capacity: 35,  type: 'Classroom' },
            { id: 'R03', name: 'Room 301',  building: 'Block A', capacity: 35,  type: 'Classroom' },
            { id: 'R04', name: 'Room 302',  building: 'Block A', capacity: 35,  type: 'Classroom' },
            { id: 'R05', name: 'Room 303',  building: 'Block A', capacity: 35,  type: 'Classroom' },
            { id: 'R06', name: 'Room 305',  building: 'Block A', capacity: 35,  type: 'Classroom' },
            { id: 'R07', name: 'Lab 3',     building: 'Block B', capacity: 25,  type: 'Lab'       },
            { id: 'R08', name: 'Lab 4',     building: 'Block B', capacity: 25,  type: 'Lab'       },
            { id: 'R09', name: 'Lab 5',     building: 'Block B', capacity: 25,  type: 'Lab'       },
            { id: 'R10', name: 'Room 401',  building: 'Block B', capacity: 50,  type: 'Classroom' },
            { id: 'R11', name: 'Room 203',  building: 'Block A', capacity: 30,  type: 'Classroom' },
            { id: 'R12', name: 'Room 204',  building: 'Block A', capacity: 30,  type: 'Classroom' },
            { id: 'R13', name: 'Hall 1',    building: 'Block C', capacity: 200, type: 'Hall'      },
            { id: 'R14', name: 'Studio A',  building: 'Block D', capacity: 20,  type: 'Studio'    },
        ]
    },
    teachers: {
        data: [
            { id: 1, firstName: 'Sarah',   lastName: 'Johnson',   title: 'Dr.',   department: 'Computer Science',     email: 's.johnson@sus.edu',  subjects: ['Data Structures', 'Algorithms']              },
            { id: 2, firstName: 'Michael', lastName: 'Chen',      title: 'Prof.', department: 'Computer Science',     email: 'm.chen@sus.edu',      subjects: ['Web Development', 'Mobile Development']      },
            { id: 3, firstName: 'Emily',   lastName: 'Rodriguez', title: 'Dr.',   department: 'Computer Science',     email: 'e.rodriguez@sus.edu', subjects: ['Database Systems', 'Advanced Databases']     },
            { id: 4, firstName: 'Susan',   lastName: 'Taylor',    title: 'Prof.', department: 'Mathematics',          email: 's.taylor@sus.edu',    subjects: ['Mathematics III']                            },
            { id: 5, firstName: 'David',   lastName: 'Williams',  title: 'Prof.', department: 'Software Engineering', email: 'd.williams@sus.edu',  subjects: ['Software Engineering', 'Project Management'] },
            { id: 6, firstName: 'James',   lastName: 'Anderson',  title: 'Dr.',   department: 'Computer Science',     email: 'j.anderson@sus.edu',  subjects: ['Network Security']                           }
        ]
    },
    groups: {
        data: [
            { id: 'CS-301-A', name: 'CS-301-A', programme: 'BSc Computer Science',       year: 3 },
            { id: 'CS-301-B', name: 'CS-301-B', programme: 'BSc Computer Science',       year: 3 },
            { id: 'CS-302-A', name: 'CS-302-A', programme: 'BSc Computer Science',       year: 3 },
            { id: 'SE-201-A', name: 'SE-201-A', programme: 'BEng Software Engineering',  year: 2 }
        ]
    },
    subjects: {
        data: [
            { id: 'CS301', code: 'CS301', name: 'Data Structures'     },
            { id: 'CS302', code: 'CS302', name: 'Web Development'      },
            { id: 'CS303', code: 'CS303', name: 'Database Systems'     },
            { id: 'CS304', code: 'CS304', name: 'Software Engineering' },
            { id: 'CS305', code: 'CS305', name: 'Network Security'     },
            { id: 'CS306', code: 'CS306', name: 'Algorithms'           },
            { id: 'CS307', code: 'CS307', name: 'Mobile Development'   },
            { id: 'CS308', code: 'CS308', name: 'Advanced Databases'   },
            { id: 'MA201', code: 'MA201', name: 'Mathematics III'      },
            { id: 'SE201', code: 'SE201', name: 'Project Management'   }
        ]
    },
    timetable: {
        data: [
            { id: 'SL001', day: 'Monday',    time: '09:00', subject: 'Data Structures',    subjectId: 'CS301', teacherId: 1, groupId: 'CS-301-A', roomId: 'R03', type: 'lecture' },
            { id: 'SL002', day: 'Monday',    time: '11:00', subject: 'Web Development',    subjectId: 'CS302', teacherId: 2, groupId: 'CS-301-A', roomId: 'R07', type: 'lab'     },
            { id: 'SL003', day: 'Monday',    time: '09:00', subject: 'Database Systems',   subjectId: 'CS303', teacherId: 3, groupId: 'CS-302-A', roomId: 'R04', type: 'lecture' },
            { id: 'SL004', day: 'Monday',    time: '14:00', subject: 'Data Structures',    subjectId: 'CS301', teacherId: 1, groupId: 'CS-301-B', roomId: 'R05', type: 'lecture' },
            { id: 'SL005', day: 'Monday',    time: '15:00', subject: 'Web Development',    subjectId: 'CS302', teacherId: 2, groupId: 'CS-301-A', roomId: 'R08', type: 'lab'     },
            { id: 'SL006', day: 'Tuesday',   time: '10:00', subject: 'Data Structures',    subjectId: 'CS301', teacherId: 1, groupId: 'CS-301-B', roomId: 'R06', type: 'lecture' },
            { id: 'SL007', day: 'Tuesday',   time: '11:00', subject: 'Software Eng.',      subjectId: 'SE201', teacherId: 5, groupId: 'SE-201-A', roomId: 'R10', type: 'lecture' },
            { id: 'SL008', day: 'Tuesday',   time: '13:00', subject: 'Web Development',    subjectId: 'CS302', teacherId: 2, groupId: 'CS-301-B', roomId: 'R09', type: 'lab'     },
            { id: 'SL009', day: 'Tuesday',   time: '14:00', subject: 'Advanced Databases', subjectId: 'CS308', teacherId: 3, groupId: 'CS-302-A', roomId: 'R04', type: 'lecture' },
            { id: 'SL010', day: 'Wednesday', time: '10:00', subject: 'Mathematics III',    subjectId: 'MA201', teacherId: 4, groupId: 'CS-301-A', roomId: 'R11', type: 'lecture' },
            { id: 'SL011', day: 'Wednesday', time: '14:00', subject: 'Mathematics III',    subjectId: 'MA201', teacherId: 4, groupId: 'CS-301-B', roomId: 'R12', type: 'lecture' },
            { id: 'SL012', day: 'Thursday',  time: '11:00', subject: 'Network Security',   subjectId: 'CS305', teacherId: 6, groupId: 'CS-301-A', roomId: 'R03', type: 'lecture' },
            { id: 'SL013', day: 'Thursday',  time: '13:00', subject: 'Algorithms',         subjectId: 'CS306', teacherId: 1, groupId: 'CS-301-A', roomId: 'R04', type: 'lecture' },
            { id: 'SL014', day: 'Friday',    time: '09:00', subject: 'Database Systems',   subjectId: 'CS303', teacherId: 3, groupId: 'CS-301-A', roomId: 'R04', type: 'lecture' },
            { id: 'SL015', day: 'Friday',    time: '10:00', subject: 'Mobile Development', subjectId: 'CS307', teacherId: 2, groupId: 'CS-301-A', roomId: 'R07', type: 'lab'     },
            { id: 'SL016', day: 'Saturday',  time: '10:00', subject: 'Network Security',   subjectId: 'CS305', teacherId: 6, groupId: 'CS-302-A', roomId: 'R05', type: 'lecture' },
            { id: 'SL017', day: 'Saturday',  time: '11:00', subject: 'Project Management', subjectId: 'SE201', teacherId: 5, groupId: 'SE-201-A', roomId: 'R10', type: 'lecture' }
        ]
    },
    users: {
        data: [
            { id: 'STU001', name: 'Ali Karimov',     email: 'a.karimov@sus.edu',     role: 'Student', status: 'Active' },
            { id: 'STU002', name: 'Zarina Yusupova', email: 'z.yusupova@sus.edu',    role: 'Student', status: 'Active' },
            { id: 'STU003', name: 'Bobur Toshmatov', email: 'b.toshmatov@sus.edu',   role: 'Student', status: 'Active' },
            { id: 'STU004', name: 'Nilufar Rashidova',email: 'n.rashidova@sus.edu',  role: 'Student', status: 'Active' },
        ]
    },
    grades: {
        data: [
            { id: 'STU001', name: 'Ali Karimov',      group: 'Group A', course: 'Computer Science 101', assignments: 88, midterm: 92, final: 85 },
            { id: 'STU002', name: 'Zarina Yusupova',  group: 'Group A', course: 'Computer Science 101', assignments: 76, midterm: 80, final: 78 },
            { id: 'STU003', name: 'Bobur Toshmatov',  group: 'Group B', course: 'Mathematics 201',      assignments: 91, midterm: 87, final: 89 },
            { id: 'STU004', name: 'Nilufar Rashidova', group: 'Group B', course: 'Mathematics 201',     assignments: 94, midterm: 89, final: 91 },
        ]
    },
    attendance: {
        data: [
            { id: 'STU001', name: 'Ali Karimov',      initials: 'AK', color: '#2563eb', group: 'Group A', course: 'Computer Science 101', totalDays: 30, presentDays: 26 },
            { id: 'STU002', name: 'Zarina Yusupova',  initials: 'ZY', color: '#7c3aed', group: 'Group A', course: 'Computer Science 101', totalDays: 30, presentDays: 22 },
            { id: 'STU003', name: 'Bobur Toshmatov',  initials: 'BT', color: '#16a34a', group: 'Group B', course: 'Mathematics 201',      totalDays: 30, presentDays: 27 },
            { id: 'STU004', name: 'Nilufar Rashidova', initials: 'NR', color: '#d97706', group: 'Group B', course: 'Mathematics 201',     totalDays: 30, presentDays: 18 },
        ]
    },
    news: {
        data: [
            { id: 1, title: 'University Ranked Top 10 in National Research Excellence', category: 'Academic', author: 'Communications Office', content: '<p>Smart University System has once again demonstrated its commitment to academic excellence.</p>', status: 'Active', date: 'Apr 18, 2026' },
            { id: 2, title: 'New Student Wellbeing Centre Opens This Month',             category: 'Campus',   author: 'Student Services',       content: '<p>A brand-new Student Wellbeing Centre opened its doors on Monday.</p>',                            status: 'Active', date: 'Apr 15, 2026' },
            { id: 3, title: 'Career Fair 2026: 80+ Employers Attending',                category: 'Events',   author: 'Career Centre',           content: '<p>The annual SUS Career Fair returns on 10th May 2026 in the Main Hall.</p>',                      status: 'Draft',  date: 'Apr 12, 2026' },
            { id: 4, title: 'Library Extended Hours During Exam Period',                 category: 'Important',author: 'Library Services',        content: '<p>The main library will operate 24-hour opening from 4 May to 25 June.</p>',                        status: 'Active', date: 'Apr 10, 2026' },
        ]
    },
    messages: {
        data: [
            { id: 1, name: 'Ali Karimov',     email: 'a.karimov@sus.edu',  role: 'Student', department: 'Computer Science', subject: 'Assignment deadline extension', message: 'I would like to request an extension for the upcoming assignment.', status: 'unread',  date: 'Apr 27, 2026', initials: 'AK', avatarColor: '#2563eb' },
            { id: 2, name: 'Sarah Johnson',   email: 's.johnson@sus.edu',  role: 'Teacher', department: 'Computer Science', subject: 'Lab equipment request',         message: 'We need additional lab equipment for the upcoming practical sessions.',  status: 'replied', date: 'Apr 26, 2026', initials: 'SJ', avatarColor: '#7c3aed' },
        ]
    },
    departments: {
        data: [
            { id: 1, name: 'Computer Science',     phone: '+998 71 123 4567', email: 'cs@sus.edu',   location: 'Block A, Room 101' },
            { id: 2, name: 'Mathematics',           phone: '+998 71 123 4568', email: 'math@sus.edu', location: 'Block B, Room 201' },
            { id: 3, name: 'Software Engineering',  phone: '+998 71 123 4569', email: 'se@sus.edu',   location: 'Block C, Room 301' },
        ]
    },
    dashboard: {
        data: {
            totalStudents: 2847,
            totalTeachers: 184,
            activeCourses: 96,
            studentChange: '+12%',
            teacherChange: '+3%',
            courseChange:  '+8%',
            weeklyStudents: [620, 780, 710, 850, 760, 540, 200],
            weeklyTeachers: [80, 120, 95, 140, 110, 65, 30],
            weeklyLogins:   [900, 1100, 980, 1300, 1050, 720, 380]
        }
    }
};

const RoomAPI = {
    getRooms: async () => {
        if (USE_MOCK) return ADMIN_MOCK.rooms;
        return adminApiCall('/rooms');
    },
    createRoom: async (roomData) => {
        if (USE_MOCK) { const r = { id: 'R' + Date.now(), ...roomData }; ADMIN_MOCK.rooms.data.push(r); return { data: r }; }
        return adminApiCall('/rooms', { method: 'POST', body: JSON.stringify(roomData) });
    },
    updateRoom: async (roomId, roomData) => {
        if (USE_MOCK) { const i = ADMIN_MOCK.rooms.data.findIndex(r => r.id === roomId); if (i > -1) ADMIN_MOCK.rooms.data[i] = { ...ADMIN_MOCK.rooms.data[i], ...roomData }; return { data: ADMIN_MOCK.rooms.data[i] }; }
        return adminApiCall(`/rooms/${roomId}`, { method: 'PUT', body: JSON.stringify(roomData) });
    },
    deleteRoom: async (roomId) => {
        if (USE_MOCK) { ADMIN_MOCK.rooms.data = ADMIN_MOCK.rooms.data.filter(r => r.id !== roomId); return null; }
        return adminApiCall(`/rooms/${roomId}`, { method: 'DELETE' });
    }
};

const AdminTeacherAPI = {
    getTeachers: async () => {
        if (USE_MOCK) return ADMIN_MOCK.teachers;
        return adminApiCall('/teachers');
    },
    createTeacher: async (data) => {
        if (USE_MOCK) { const t = { id: Date.now(), ...data }; ADMIN_MOCK.teachers.data.push(t); return { data: t }; }
        return adminApiCall('/teachers', { method: 'POST', body: JSON.stringify(data) });
    },
    updateTeacher: async (id, data) => {
        if (USE_MOCK) { /* ... */ }
        return adminApiCall(`/teachers/${id}`, { method: 'PUT', body: JSON.stringify(data) });
    },
    deleteTeacher: async (id) => {
        if (USE_MOCK) { ADMIN_MOCK.teachers.data = ADMIN_MOCK.teachers.data.filter(t => t.id != id); return null; }
        return adminApiCall(`/teachers/${id}`, { method: 'DELETE' });
    }
};

const AdminGroupAPI = {
    getGroups: async () => {
        if (USE_MOCK) return ADMIN_MOCK.groups;
        return adminApiCall('/groups');
    },
    createGroup: async (data) => {
        if (USE_MOCK) { const g = { id: data.name, ...data }; ADMIN_MOCK.groups.data.push(g); return { data: g }; }
        return adminApiCall('/groups', { method: 'POST', body: JSON.stringify(data) });
    },
    updateGroup: async (id, data) => {
        if (USE_MOCK) { const i = ADMIN_MOCK.groups.data.findIndex(g => g.id === id); if (i > -1) ADMIN_MOCK.groups.data[i] = { ...ADMIN_MOCK.groups.data[i], ...data }; return { data: ADMIN_MOCK.groups.data[i] }; }
        return adminApiCall(`/groups/${id}`, { method: 'PUT', body: JSON.stringify(data) });
    },
    deleteGroup: async (id) => {
        if (USE_MOCK) { ADMIN_MOCK.groups.data = ADMIN_MOCK.groups.data.filter(g => g.id !== id); return null; }
        return adminApiCall(`/groups/${id}`, { method: 'DELETE' });
    }
};

const AdminSubjectAPI = {
    getSubjects: async () => {
        if (USE_MOCK) return ADMIN_MOCK.subjects;
        return adminApiCall('/subjects');
    }
};

const AdminTimetableAPI = {
    getAll: async () => {
        if (USE_MOCK) return ADMIN_MOCK.timetable;
        return adminApiCall('/timetable');
    },
    getByGroup: async (groupId) => {
        if (USE_MOCK) return { data: ADMIN_MOCK.timetable.data.filter(s => s.groupId === groupId) };
        return adminApiCall(`/timetable?groupId=${groupId}`);
    },
    getByTeacher: async (teacherId) => {
        if (USE_MOCK) return { data: ADMIN_MOCK.timetable.data.filter(s => s.teacherId == teacherId) };
        return adminApiCall(`/timetable?teacherId=${teacherId}`);
    },
    getByRoom: async (roomId) => {
        if (USE_MOCK) return { data: ADMIN_MOCK.timetable.data.filter(s => s.roomId === roomId) };
        return adminApiCall(`/timetable?roomId=${roomId}`);
    },
    addSlot: async (slotData) => {
        if (USE_MOCK) {
            const conflicts = ADMIN_MOCK.timetable.data.filter(s => s.day === slotData.day && s.time === slotData.time && (s.teacherId == slotData.teacherId || s.roomId === slotData.roomId || s.groupId === slotData.groupId));
            if (conflicts.length > 0) {
                const reasons = [];
                if (conflicts.some(c => c.teacherId == slotData.teacherId)) reasons.push('teacher already booked');
                if (conflicts.some(c => c.roomId === slotData.roomId))      reasons.push('room already booked');
                if (conflicts.some(c => c.groupId === slotData.groupId))    reasons.push('group already has a class');
                throw new Error('Conflict detected: ' + reasons.join(', '));
            }
            const newSlot = { id: 'SL' + Date.now(), ...slotData };
            ADMIN_MOCK.timetable.data.push(newSlot);
            return { data: newSlot };
        }
        return adminApiCall('/timetable', { method: 'POST', body: JSON.stringify(slotData) });
    },
    updateSlot: async (slotId, slotData) => {
        if (USE_MOCK) {
            const idx = ADMIN_MOCK.timetable.data.findIndex(s => s.id === slotId);
            if (idx === -1) throw new Error('Slot not found');
            const conflicts = ADMIN_MOCK.timetable.data.filter((s, i) => i !== idx && s.day === slotData.day && s.time === slotData.time && (s.teacherId == slotData.teacherId || s.roomId === slotData.roomId || s.groupId === slotData.groupId));
            if (conflicts.length > 0) {
                const reasons = [];
                if (conflicts.some(c => c.teacherId == slotData.teacherId)) reasons.push('teacher already booked');
                if (conflicts.some(c => c.roomId === slotData.roomId))      reasons.push('room already booked');
                if (conflicts.some(c => c.groupId === slotData.groupId))    reasons.push('group already has a class');
                throw new Error('Conflict detected: ' + reasons.join(', '));
            }
            ADMIN_MOCK.timetable.data[idx] = { ...ADMIN_MOCK.timetable.data[idx], ...slotData };
            return { data: ADMIN_MOCK.timetable.data[idx] };
        }
        return adminApiCall(`/timetable/${slotId}`, { method: 'PUT', body: JSON.stringify(slotData) });
    },
    deleteSlot: async (slotId) => {
        if (USE_MOCK) { ADMIN_MOCK.timetable.data = ADMIN_MOCK.timetable.data.filter(s => s.id !== slotId); return null; }
        return adminApiCall(`/timetable/${slotId}`, { method: 'DELETE' });
    },
    getConflicts: async () => {
        if (USE_MOCK) {
            const slots = ADMIN_MOCK.timetable.data;
            const conflicts = [];
            for (let i = 0; i < slots.length; i++) {
                for (let j = i + 1; j < slots.length; j++) {
                    const a = slots[i], b = slots[j];
                    if (a.day !== b.day || a.time !== b.time) continue;
                    if (a.teacherId == b.teacherId) conflicts.push({ type: 'teacher', slotA: a.id, slotB: b.id, message: `Teacher conflict at ${a.day} ${a.time}` });
                    if (a.roomId === b.roomId)      conflicts.push({ type: 'room',    slotA: a.id, slotB: b.id, message: `Room conflict at ${a.day} ${a.time}` });
                    if (a.groupId === b.groupId)    conflicts.push({ type: 'group',   slotA: a.id, slotB: b.id, message: `Group conflict at ${a.day} ${a.time}` });
                }
            }
            return { data: conflicts };
        }
        return adminApiCall('/timetable/conflicts');
    },
    publish: async () => {
        if (USE_MOCK) return { data: { message: 'Timetable published successfully (mock)', publishedAt: new Date().toISOString() } };
        return adminApiCall('/timetable/publish', { method: 'POST' });
    }
};

const AdminUserAPI = {
    getUsers: async () => {
        if (USE_MOCK) return ADMIN_MOCK.users;
        return adminApiCall('/users');
    },
    createUser: async (data) => {
        if (USE_MOCK) { const u = { id: 'STU' + Date.now(), ...data }; ADMIN_MOCK.users.data.unshift(u); return { data: u }; }
        return adminApiCall('/users', { method: 'POST', body: JSON.stringify(data) });
    },
    updateUser: async (id, data) => {
        if (USE_MOCK) { const i = ADMIN_MOCK.users.data.findIndex(u => u.id == id); if (i > -1) ADMIN_MOCK.users.data[i] = { ...ADMIN_MOCK.users.data[i], ...data }; return { data: ADMIN_MOCK.users.data[i] }; }
        return adminApiCall(`/users/${id}`, { method: 'PUT', body: JSON.stringify(data) });
    },
    deleteUser: async (id) => {
        if (USE_MOCK) { ADMIN_MOCK.users.data = ADMIN_MOCK.users.data.filter(u => u.id != id); return null; }
        return adminApiCall(`/users/${id}`, { method: 'DELETE' });
    }
};

const AdminGradeAPI = {
    getGrades: async (groupId, subjectId) => {
        if (USE_MOCK) {
            let data = ADMIN_MOCK.grades.data;
            if (groupId)   data = data.filter(s => s.group   === groupId);
            if (subjectId) data = data.filter(s => s.course  === subjectId);
            return { data };
        }
        return adminApiCall(`/grades?groupId=${groupId}&subjectId=${subjectId}`);
    },
    updateGrade: async (studentId, data) => {
        if (USE_MOCK) { const i = ADMIN_MOCK.grades.data.findIndex(s => s.id === studentId); if (i > -1) ADMIN_MOCK.grades.data[i] = { ...ADMIN_MOCK.grades.data[i], ...data }; return { data: ADMIN_MOCK.grades.data[i] }; }
        return adminApiCall(`/grades/${studentId}`, { method: 'PUT', body: JSON.stringify(data) });
    }
};

const AdminAttendanceAPI = {
    getSession: async (courseId, date) => {
        if (USE_MOCK) {
            let data = ADMIN_MOCK.attendance.data;
            if (courseId) data = data.filter(s => s.course === courseId);
            return { data };
        }
        return adminApiCall(`/attendance?courseId=${encodeURIComponent(courseId)}&date=${date}`);
    },
    saveSession: async (data) => {
        if (USE_MOCK) return { data: { saved: true } };
        return adminApiCall('/attendance', { method: 'POST', body: JSON.stringify(data) });
    }
};

const AdminNewsAPI = {
    getAll: async () => {
        if (USE_MOCK) return ADMIN_MOCK.news;
        return adminApiCall('/news');
    },
    create: async (data) => {
        if (USE_MOCK) { const a = { id: Date.now(), ...data }; ADMIN_MOCK.news.data.unshift(a); return { data: a }; }
        return adminApiCall('/news', { method: 'POST', body: JSON.stringify(data) });
    },
    update: async (id, data) => {
        if (USE_MOCK) { const i = ADMIN_MOCK.news.data.findIndex(a => a.id == id); if (i > -1) ADMIN_MOCK.news.data[i] = { ...ADMIN_MOCK.news.data[i], ...data }; return { data: ADMIN_MOCK.news.data[i] }; }
        return adminApiCall(`/news/${id}`, { method: 'PUT', body: JSON.stringify(data) });
    },
    delete: async (id) => {
        if (USE_MOCK) { ADMIN_MOCK.news.data = ADMIN_MOCK.news.data.filter(a => a.id != id); return null; }
        return adminApiCall(`/news/${id}`, { method: 'DELETE' });
    }
};

const AdminContentAPI = {
    getAll:   async ()      => { if (USE_MOCK) return { data: [] }; return adminApiCall('/content'); },
    create:   async (data)  => { if (USE_MOCK) return { data }; return adminApiCall('/content',       { method: 'POST',   body: JSON.stringify(data) }); },
    update:   async (id, d) => { if (USE_MOCK) return { data: d }; return adminApiCall(`/content/${id}`, { method: 'PUT', body: JSON.stringify(d) }); },
    delete:   async (id)    => { if (USE_MOCK) return null; return adminApiCall(`/content/${id}`,     { method: 'DELETE' }); },
    publish:  async (id)    => { if (USE_MOCK) return { data: { published: true } }; return adminApiCall(`/content/${id}/publish`, { method: 'POST' }); }
};

const AdminContactAPI = {
    getMessages: async () => {
        if (USE_MOCK) return ADMIN_MOCK.messages;
        return adminApiCall('/messages');
    },
    sendReply: async ({ messageId, reply }) => {
        if (USE_MOCK) { const i = ADMIN_MOCK.messages.data.findIndex(m => m.id == messageId); if (i > -1) ADMIN_MOCK.messages.data[i].status = 'replied'; return { data: { sent: true } }; }
        return adminApiCall(`/messages/${messageId}/reply`, { method: 'POST', body: JSON.stringify({ message: reply }) });
    },
    deleteMessage: async (id) => {
        if (USE_MOCK) { ADMIN_MOCK.messages.data = ADMIN_MOCK.messages.data.filter(m => m.id != id); return null; }
        return adminApiCall(`/messages/${id}`, { method: 'DELETE' });
    },
    getDepartments: async () => {
        if (USE_MOCK) return ADMIN_MOCK.departments;
        return adminApiCall('/departments');
    },
    addDepartment: async (data) => {
        if (USE_MOCK) { const d = { id: Date.now(), ...data }; ADMIN_MOCK.departments.data.push(d); return { data: d }; }
        return adminApiCall('/departments', { method: 'POST', body: JSON.stringify(data) });
    },
    updateDepartment: async (id, data) => {
        if (USE_MOCK) { const i = ADMIN_MOCK.departments.data.findIndex(d => d.id == id); if (i > -1) ADMIN_MOCK.departments.data[i] = { ...ADMIN_MOCK.departments.data[i], ...data }; return { data: ADMIN_MOCK.departments.data[i] }; }
        return adminApiCall(`/departments/${id}`, { method: 'PUT', body: JSON.stringify(data) });
    },
    deleteDepartment: async (id) => {
        if (USE_MOCK) { ADMIN_MOCK.departments.data = ADMIN_MOCK.departments.data.filter(d => d.id != id); return null; }
        return adminApiCall(`/departments/${id}`, { method: 'DELETE' });
    }
};

const AdminDashboardAPI = {
    getStats: async () => {
        if (USE_MOCK) return ADMIN_MOCK.dashboard;
        return adminApiCall('/dashboard/stats');
    }
};
