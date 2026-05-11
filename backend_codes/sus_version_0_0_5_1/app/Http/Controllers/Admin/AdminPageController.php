<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPageController extends Controller
{
    public function dashboard()  { return view('admin.dashboard'); }
    public function users()      { return view('admin.users'); }
    public function timetable()  { return view('admin.timetable'); }
    public function content()    { return view('admin.content'); }
    public function grading()    { return view('admin.grading'); }
    public function news()       { return view('admin.news'); }
    public function attendance() { return view('admin.attendance'); }
    public function teachers()   { return view('admin.teachers'); }
    public function contact()    { return view('admin.contact'); }
    public function help()       { return view('admin.help'); }
    public function profile()    { return view('admin.profile'); }
}