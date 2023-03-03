<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\Level;
use App\Models\Student;
use App\Models\ClassInfor;
use App\Models\ClassRegister;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    function index()
    {
        $data = array();
        if (Auth::check()) {
            $classroom = Classroom::all();
            //session()->flash('success', Student::where('id', '=', Auth::id())->get("name")->first());
            return view('classrooms.listClass', compact('classroom'));
        }
        return redirect('login')->with('success', 'Đăng nhập thành công');
    }
    function detail($id)
    {
        $classroom = DB::table('classrooms')
            ->where('classrooms.id', '=', $id)
            ->join('levels', 'classrooms.id_levels', '=', 'levels.id')
            ->join('teachers', 'classrooms.id_teachers', '=', 'teachers.id')
            ->select('classrooms.*', 'levels.name as name_level', 'levels.amount', 'teachers.name as name_teacher', 'teachers.id as id_teachers')
            ->first();
        return view('classrooms.detail', compact('classroom'));
    }
    function classRegister(Request $request, $id)
    {
        $classroom = DB::table('classrooms')
        ->where('classrooms.id', '=', $id)
            ->Join('levels', 'classrooms.id_levels', '=', 'levels.id')
            ->join('teachers', 'classrooms.id_teachers', '=', 'teachers.id')
            ->select('classrooms.*', 'levels.name as name_level', 'levels.amount', 'teachers.name as name_teacher', 'teachers.id as id_teachers')
            ->first();
        $student=DB::table('students')
        ->where('students.id_users', '=', Auth::id())
        ->select('students.*')
        ->first();

        ClassInfor::create([
            'id_teachers' => $classroom->id_teachers,
            'total_money' => $classroom->amount,
        ]);
        ClassRegister::create([
            'id_students'   => $student->id,
            'id_classrooms' => $classroom->id,
        ]);
        $actual_number = $classroom->actual_number + 1;
        DB::update("update classrooms set actual_number = ? where id = ?", [$actual_number, $classroom->id]); 
        return Redirect('list_class')->with('success', 'Đăng ký lớp học thành công');
    }
}
