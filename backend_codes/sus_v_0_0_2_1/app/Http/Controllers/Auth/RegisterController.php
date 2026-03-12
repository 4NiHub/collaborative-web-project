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
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'role'     => 'required|in:student,teacher'
        ]);

        Log::info('Basic validation passed');

        // Manual uniqueness check (no fancy rule)
        if (DB::table('v_2.users')->where('email', $request->email)->exists()) {
            Log::warning('Email already taken: ' . $request->email);
            return back()->withErrors(['email' => 'This email is already registered.']);
        }

        DB::beginTransaction();

        try {
            Log::info('Inserting user...');

            $user = User::create([
                'role_id'       => ($request->role === 'student') ? 5 : 6,
                'email'         => $request->email,
                'password_hash' => Hash::make($request->password),
                'creation_time' => now(),
            ]);

            Log::info('User inserted', ['id' => $user->user_id]);

            if ($request->role === 'student') {
                Log::info('Inserting student...');

                $group = Group::firstOrCreate(
                    ['group_name' => 'CS-2026-New'],
                    ['group_level' => 1, 'is_active' => true, 'creation_date' => now()]
                );

                Student::create([
                    'user_id'      => $user->user_id,
                    'name'         => $request->name,
                    'surname'      => $request->surname,
                    'entry_year'   => date('Y'),
                    'group_id'     => $group->group_id,
                    'phone_number' => '+0000000000'
                ]);

                Log::info('Student inserted');
            } else {
                Log::info('Inserting staff...');

                DB::table('v_2.staff')->insert([
                    'user_id'       => $user->user_id,
                    'name'          => $request->name,
                    'surname'       => $request->surname,
                    'email'         => $request->email,
                    'phone_number'  => '+0000000000',
                    'job_position'  => 'Lecturer'
                ]);

                Log::info('Staff inserted');
            }

            DB::commit();
            Log::info('Registration COMMITTED successfully');

            return redirect()->route('login')
                ->with('success', 'Registration successful! Please log in.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('REGISTRATION FAILED', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'data'    => $request->all()
            ]);

            return back()->withErrors(['error' => 'Failed to register: ' . $e->getMessage()]);
        }
    }

    // ← MOVE THIS INSIDE THE CLASS (before the final })
    private function generateFakeStudentData($user)
    {
        // This creates realistic fake data for testing
        DB::table('v_2.news')->insert([
            'title' => 'Welcome ' . $user->email,
            'body' => 'Your account has been created. Welcome to SUS Portal!',
            'creation_time' => now()
        ]);
    }
}