const API_BASE_URL = 'https://smart-university.site/api';

// ==================== CORE HELPERS ====================
function getToken()        { return localStorage.getItem('userToken'); }
function saveToken(token)  { localStorage.setItem('userToken', token); }
function deleteToken()     { localStorage.removeItem('userToken'); }
function getRole()         { return localStorage.getItem('userRole'); }   // "student" or "teacher"

// Unified apiCall (used by both roles)
async function apiCall(endpoint, options = {}) {
    const token = getToken();
    // Get CSRF token from the meta tag once
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        // Add CSRF to EVERY call by default
        'X-CSRF-TOKEN': csrfToken, 
        ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        ...options.headers
    };

    if (options.body instanceof FormData) {
        delete headers['Content-Type'];
    } else if (!headers['Content-Type']) {
        headers['Content-Type'] = 'application/json';
    }

    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
        ...options,
        headers
    });

    if (response.status === 401) {
        console.error("STOPPING REDIRECT: A 401 error occurred.");
        // localStorage.clear();
        // Comment out the line below temporarily!
        // window.location.href = '/login?reason=session_expired'; 
        return null; 
    }

    if (response.status === 204) {
        return null;
    }

    const contentType = response.headers.get('Content-Type') || '';
    const isJson = contentType.includes('application/json');

    let data = null;

    if (isJson) {
        data = await response.json();
    } else {
        const text = await response.text();
        data = text.trim() ? text : null;
    }

    if (!response.ok) {
        const message =
            data?.message ||
            data?.error ||
            (typeof data === 'string' ? data : null) ||
            `Server error (${response.status})`;

        throw new Error(message);
    }

    return data;
};

// ==================== AUTH (shared) ====================
const AuthAPI = {
    login: async (email, password) => {
        const response = await apiCall('/auth/login', {
            method: 'POST',
            body: JSON.stringify({ email, password })
        });

        // FIX: The token is inside response.data.token
        if (response && response.data && response.data.token) {
            saveToken(response.data.token); 
            // Save the role from the response (see Laravel fix below)
            localStorage.setItem('userRole', response.data.role || 'student');
            return response.data;
        }
        
        console.error("Login failed: Token missing in response", response);
        throw new Error("Invalid response from server");
    },

    logout: async function() {
        const token = getToken();

        try {
            // Only attempt server-side logout if we have a token
            if (token) {
                await fetch('/api/logout', { // Added /api/ prefix
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });
            }
        } catch (err) {
            console.warn("Server logout failed, cleaning up locally.");
        }

        // Clean up local storage LAST
        deleteToken();
        localStorage.removeItem('userRole');
        window.location.href = '/login';
    }
};

// ==================== STUDENT API (real DB only) ====================
const StudentAPI = {
    getProfile: () => apiCall('/profile'),           // backend returns student data
    getDashboardStats: () => apiCall('/student/dashboard/stats'),
    getTodaySchedule: (date) => apiCall(`/timetable/today${date ? `?date=${date}` : ''}`),
    getWeeklyTimetable: (startDate) => apiCall(`/timetable/week${startDate ? `?startDate=${startDate}` : ''}`),
    getEnrolledModules: () => apiCall('/modules'),
    getTranscript: () => apiCall('/records/transcript'),
    getAttendance: (moduleId, from, to) => {
        const params = new URLSearchParams({ moduleId, from, to }.filter(Boolean));
        return apiCall(`/records/attendance${params.toString() ? '?' + params : ''}`);
    }
};

// ==================== TEACHER API (real DB only) ====================
const TeacherAPI = {
    getProfile: () => apiCall('/profile'),
    getDashboardStats: () => apiCall('/teacher/dashboard/stats'),
    getTodayClasses: () => apiCall('/teacher/timetable/today'),
    getRecentActivity: () => apiCall('/teacher/activity/recent'),
    getModules: () => apiCall('/teacher/modules'),
    getModuleDetails: (moduleId) => apiCall(`/teacher/modules/${moduleId}`),
    getModuleAttendance: async (moduleId) => {return apiCall(`/teacher/modules/${moduleId}/attendance`);},
    getMyWeeklyTimetable: (startDate) => apiCall(`/teacher/timetable/week${startDate ? `?startDate=${startDate}` : ''}`),
    saveAttendance: (moduleId, groupId, date, time, students) => apiCall(`/teacher/modules/${moduleId}/attendance`, {
        method: 'POST',
        body: JSON.stringify({ groupId, date, time, students })
    }),

    // ── ADD THESE TWO LINES (taken from your old student api.js) ────────────────────────────────
    getTeachers: async (department = null, search = null) => {
        const params = new URLSearchParams();
        if (department) params.set('department', department);
        if (search)     params.set('search', search);
        const q = params.toString() ? `?${params}` : '';
        return apiCall(`/teachers${q}`);
    },

    getTeacherProfile: async (teacherId) => {
        return apiCall(`/teachers/${teacherId}`);
    }
};

// ==================== SHARED APIs (work for both roles) ====================
const TimetableAPI = {
    getTodaySchedule: async (date) => {
        const q = date ? `?date=${date}` : '';
        return apiCall(`/timetable/today${q}`);
    },

    getWeeklyTimetable: async (startDate) => {
        const q = startDate ? `?startDate=${startDate}` : '';
        return apiCall(`/timetable/week${q}`);
        // ← Laravel now returns the exact { weekStart, days } shape your renderCalendar expects
    },

    getGroups: async () => {
        return apiCall('/timetable/groups');
    },

    getGroupWeeklyTimetable: async (groupId, startDate) => {
        const q = startDate ? `?startDate=${startDate}` : '';
        return apiCall(`/timetable/group/${groupId}/week${q}`);
    },

    getTeacherWeeklyTimetable: async (teacherId, startDate) => {
        const q = startDate ? `?startDate=${startDate}` : '';
        return apiCall(`/timetable/teacher/${teacherId}/week${q}`);
    },

    getTeachers: async (department = null, search = null) => {
        const params = new URLSearchParams();
        if (department) params.set('department', department);
        if (search)     params.set('search', search);
        const q = params.toString() ? `?${params}` : '';
        return apiCall(`/teachers${q}`);
    },

    getTeacherProfile: async (teacherId) => {
        return apiCall(`/teachers/${teacherId}`);
    },

    getMyWeeklyTimetable: (startDate = null) => {
        const q = startDate ? `?startDate=${startDate}` : '';
        return apiCall(`/teacher/timetable/week${q}`);
    },
};

const ModuleAPI = {
    getEnrolledModules: () => apiCall('/modules'),
    getModuleDetails: (moduleId) => apiCall(`/modules/${moduleId}`)
};

const NewsAPI = {
    getNews: () => apiCall('/news'),

    getNewsById: (id) => apiCall(`/news/${id}`),

    toggleBookmark: async (newsId) => {
        try {
            const res = await apiCall(`/news/${newsId}/bookmark`, {
                method: 'POST'   // backend should toggle (add if missing, remove if exists)
            });
            console.log(`[NewsAPI] Bookmark toggled for ${newsId}:`, res);
            return res;
        } catch (err) {
            console.error(`[NewsAPI] Toggle bookmark failed for ${newsId}:`, err);
            throw err;
        }
    },

    // Optional helpers (add if your UI needs them)
    getBookmarks: async () => apiCall('/news/bookmarks'),
    isBookmarked: async (newsId) => {
        const bookmarks = await apiCall('/news/bookmarks');
        return bookmarks?.data?.some(b => b.id === newsId) || false;
    }
};

const CareerAPI = {
    getJobs: (page = 1) => apiCall(`/career/jobs?page=${page}`),
    getJobDetails: (jobId) => apiCall(`/career/jobs/${jobId}`),
    applyForJob: (jobId, coverLetter = '', cvUrl = '') => apiCall(`/career/jobs/${jobId}/apply`, {
        method: 'POST',
        body: JSON.stringify({ coverLetter, cvUrl })
    }),
    getEvents: () => apiCall('/career/events'),
    registerForEvent: (eventId) => apiCall(`/career/events/${eventId}/register`, { method: 'POST' }),
    cancelEventRegistration: (eventId) => apiCall(`/career/events/${eventId}/register`, { method: 'DELETE' })
};

const ContactAPI = {
    getDepartments: () => apiCall('/contact/departments'),
    submitForm: (formData) => apiCall('/contact/submit', { method: 'POST', body: JSON.stringify(formData) })
};

const RecordsAPI = {
    getTranscript: () => apiCall('/records/transcript'),

    getAttendance: (moduleId, from, to) => {
        const params = new URLSearchParams();

        // Only add params if they have real values
        if (moduleId != null && moduleId !== '') params.set('moduleId', moduleId);
        if (from     != null && from     !== '') params.set('from',     from);
        if (to       != null && to       !== '') params.set('to',       to);

        const query = params.toString() ? `?${params.toString()}` : '';
        return apiCall(`/records/attendance${query}`);
    }
};

// Export everything
window.AuthAPI = AuthAPI;
window.StudentAPI = StudentAPI;
window.TeacherAPI = TeacherAPI;
window.TimetableAPI = TimetableAPI;
window.ModuleAPI = ModuleAPI;
window.NewsAPI = NewsAPI;
window.CareerAPI = CareerAPI;
window.ContactAPI = ContactAPI;
window.RecordsAPI = RecordsAPI;
