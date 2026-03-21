<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Database\Seeders\FullDemoDataSeeder;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

   public function register(Request $request)
    {
        Log::info('Registration attempt started', $request->all());
    
        $validated = $request->validate([
            'name'     => 'required|string|max:200',
            'surname'  => 'required|string|max:200',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:student,teacher'
        ]);
    
        DB::beginTransaction();
    
        try {
            $roleId = ($request->role === 'student') ? 1 : 2;
    
            $user = User::create([
                'role_id'       => $roleId,
                'email'         => $request->email,
                'password_hash' => Hash::make($request->password),
                'creation_time' => now(),
            ]);
    
            // Refresh to get the auto-generated user_id
            $user->refresh();
    
            if ($request->role === 'student') {
                $groupId = rand(1, 2);
                $group = Group::findOrFail($groupId);
    
                $phone = '+44 7' . rand(100,999) . ' ' . rand(100,999) . ' ' . rand(100,999);
    
                $student = Student::create([
                    'user_id'              => $user->user_id,
                    'name'                 => $request->name,
                    'surname'              => $request->surname,
                    'entry_year'           => date('Y'),
                    'group_id'             => $group->group_id,
                    'phone_number'         => $phone,
                    'credits_completed'    => rand(14, 30),
                    'attendance_percentage' => 0,
                ]);
    
                // ── IMPORTANT DEBUG LINE ──
                Log::info('About to run generateDemoStudentData', [
                    'student_id' => $student->student_id,
                    'group_id'   => $group->group_id
                ]);
    
                app(FullDemoDataSeeder::class)->generateDemoStudentData(
                    $student->student_id,
                    $group->group_id
                );
    
                Log::info("New student {$student->student_id} in Group {$group->group_id} with full demo data");
            } else {
                // Teacher – exactly the same creation + assignment flow
                $mentorId = DB::table('mentors')->insertGetId([
                    'user_id' => $user->user_id,
                    'name' => $request->name,
                    'surname' => $request->surname,
                    'email' => $request->email,
                    'phone_number' => '+44 7' . rand(100,999) . ' ' . rand(100,999) . ' ' . rand(100,999),
                    'department' => \Illuminate\Support\Arr::random(['Computer Science', 'Mathematics', 'Software Engineering', 'Cyber Security']),
                    'bio' => 'Expert in ' . \Illuminate\Support\Arr::random(['algorithms', 'cybersecurity', 'web development', 'data science']) . ' with over 10 years experience.',
                    'office_location' => 'Block ' . chr(rand(65,68)) . ', Room ' . rand(100,300),
                    'office_hours' => 'Mon 14:00–16:00, Wed 10:00–12:00',
                    'nationality' => 'British',
                    'languages' => 'English',
                    'profile_data' => json_encode([
                        'experience' => [[ 'title' => 'Senior Lecturer', 'org' => 'SUS', 'period' => '2020 - Present', 'desc' => 'Core module delivery' ]],
                        'education' => [[ 'degree' => 'PhD Computer Science', 'school' => 'University of Manchester', 'period' => '2015' ]]
                    ])
                ], 'mentor_id');
    
                $availableSubjects = DB::table('subjects')
                    ->whereNull('mentor_id')
                    ->pluck('subject_id')
                    ->toArray();
    
                if (empty($availableSubjects)) {
                    $availableSubjects = DB::table('subjects')->pluck('subject_id')->toArray();
                }
    
                shuffle($availableSubjects);
                $randomSubjects = array_slice($availableSubjects, 0, rand(2,4));
    
                foreach ($randomSubjects as $subjId) {
                    DB::table('subjects')->where('subject_id', $subjId)->update(['mentor_id' => $mentorId]);
                }
            }
    
            DB::commit();
    
            return redirect()->route('login')
                ->with('success', 'Account created! Login to see your populated dashboard 🎉');
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            // ── IMPROVED LOGGING ──
            Log::error('Registration completely failed', [
                'email'      => $request->email ?? 'unknown',
                'role'       => $request->role ?? 'unknown',
                'error'      => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'trace'      => $e->getTraceAsString(),
            ]);
    
            // Show the real error to the user
            return back()
                ->withErrors(['registration' => 'Registration failed: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
