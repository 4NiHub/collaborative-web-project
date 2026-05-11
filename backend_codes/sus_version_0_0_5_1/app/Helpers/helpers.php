<?php

// Prevent redeclaration errors during artisan commands or multiple includes
if (!function_exists('formatStudentId')) {
    function formatStudentId($id) {
        return '2024' . str_pad($id, 5, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('formatStuId')) {
    function formatStuId($id) {
        return 'STU' . str_pad($id, 3, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('calculateGPA')) {
    function calculateGPA($studentId) {
        $data = DB::table('grades')
            ->join('subjects', 'grades.subject_id', '=', 'subjects.subject_id')
            ->where('grades.student_id', $studentId)
            ->select(DB::raw('SUM(grades.points * subjects.credits) as weighted, SUM(subjects.credits) as total_credits'))
            ->first();

        return $data && $data->total_credits 
            ? round($data->weighted / $data->total_credits, 2) 
            : 3.7;
    }
}

if (!function_exists('getStudentRating')) {
    function getStudentRating($studentId) {
        $gpas = DB::table('grades')
            ->join('subjects', 'grades.subject_id', '=', 'subjects.subject_id')
            ->select('grades.student_id', DB::raw('SUM(points*credits)/SUM(credits) as gpa'))
            ->groupBy('student_id')
            ->pluck('gpa', 'student_id')
            ->toArray();

        if (!isset($gpas[$studentId])) {
            return 'N/A';
        }

        $myGpa = $gpas[$studentId];
        $below = 0;
        foreach ($gpas as $g) {
            if ($g < $myGpa) $below++;
        }

        $pct = count($gpas) ? $below / count($gpas) : 0;
        return 'Top ' . round((1 - $pct) * 100) . '%';
    }
}

if (!function_exists('getImprovementTarget')) {
    function getImprovementTarget($studentId) {
        $worst = DB::table('grades')
            ->where('student_id', $studentId)
            ->orderBy('percentage')
            ->first();

        return $worst 
            ? "Subject ID {$worst->subject_id} (Current: {$worst->percentage}%). Priority: High." 
            : 'No data';
    }
}

if (!function_exists('getAnalyticsJSON')) {
    function getAnalyticsJSON($studentId) {
        return [
            'gpa'               => calculateGPA($studentId),
            'rating'            => getStudentRating($studentId),
            'improvement_target'=> getImprovementTarget($studentId),
            'status'            => 'Analysis Complete'
        ];
    }
}

if (!function_exists('calculateAttendancePercentage')) {
    function calculateAttendancePercentage($studentId) {
        $total = DB::table('attendance')
            ->join('students', 'attendance.student_id', '=', 'students.student_id')
            ->join('timetable', 'attendance.session_id', '=', 'timetable.session_id')
            ->join('subjects_groups_bridge_table as sgb', 'timetable.subject_group_id', '=', 'sgb.subject_group_id')
            ->where('attendance.student_id', $studentId)
            ->whereColumn('students.group_id', 'sgb.group_id')
            ->count();

        if ($total === 0) {
            return 0;
        }

        $present = DB::table('attendance')
            ->join('students', 'attendance.student_id', '=', 'students.student_id')
            ->join('timetable', 'attendance.session_id', '=', 'timetable.session_id')
            ->join('subjects_groups_bridge_table as sgb', 'timetable.subject_group_id', '=', 'sgb.subject_group_id')
            ->where('attendance.student_id', $studentId)
            ->whereColumn('students.group_id', 'sgb.group_id')
            ->where('is_present', true)
            ->count();

        return (int) round(($present / $total) * 100);
    }
}

if (!function_exists('buildModuleResources')) {
    function buildModuleResources() {
        $materialsPath = public_path('materials');

        if (!File::exists($materialsPath)) {
            return [];
        }

        $titleMap = [
            'lec-slides-1.pdf'   => 'Lecture Slides Week 1',
            'lec-slides-2.pdf'   => 'Lecture Slides Week 2',
            'lab-sheet-1.docx'   => 'Workshop Brief 1',
            'sample-code.zip'    => 'Sample Code',
        ];

        $orderMap = [
            'lec-slides-1.pdf'   => 1,
            'lec-slides-2.pdf'   => 2,
            'lab-sheet-1.docx'   => 3,
            'sample-code.zip'    => 4,
        ];

        return collect(File::files($materialsPath))
            ->sortBy(function ($file) use ($orderMap) {
                return $orderMap[$file->getFilename()] ?? 999;
            })
            ->values()
            ->map(function ($file, $index) use ($titleMap) {
                $filename = $file->getFilename();
                $basename = pathinfo($filename, PATHINFO_FILENAME);
                $title = $titleMap[$filename] ?? ucwords(str_replace(['-', '_'], ' ', $basename));

                if (preg_match('/^lec-slides-(\d+)$/i', $basename, $matches)) {
                    $title = 'Lecture Slides Week ' . $matches[1];
                } elseif (preg_match('/^(lab-sheet|workshop-brief)-(\d+)$/i', $basename, $matches)) {
                    $title = 'Workshop Brief ' . $matches[2];
                }

                return [
                    'id'    => 'R' . ($index + 1),
                    'title' => $title,
                    'type'  => strtolower($file->getExtension() ?: 'file'),
                    'url'   => asset('materials/' . $filename),
                ];
            })
            ->all();
    }
}
