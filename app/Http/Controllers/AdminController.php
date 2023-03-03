<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\Teacher;
use App\Models\ClassRegister;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    function create()
    {
        return view('admin.create');
    }
    function addAdmin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'name'     => 'required',
            'address'  => 'required',
            'phone'    => 'required',
        ]);

        $data = $request->all();
        try {
            $user = User::create([
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'powers'   => true,
            ]);
            Admin::create([
                'name'     =>  $data['name'],
                'address'  =>  $data['address'],
                'phone'    =>  $data['phone'],
                'id_users' =>  $user->id,
            ]);
        } catch (ModelNotFoundException $exception) {   //
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect('admin')->with('success', 'Đăng ký tài khoản thành công');
    }
    function listTeachers()
    {
        $teachers = Teacher::all();
        return view('admin.listTeacher', compact('teachers'));
    }
    function detailTeacher($id)
    {
        $teacher = Teacher::findOrFail($id);

        $teachers = DB::table('teachers')
            ->where('teachers.id', '=', $id)
            ->join('classrooms', 'teachers.id', '=', 'classrooms.id_teachers')
            ->select('teachers.*', 'classrooms.id_teachers', 'classrooms.name as name_class')
            ->get();
        return view('admin.detailTeacher', compact('teacher', 'teachers'));
    }
    function createClass()
    {
        $teachers = Teacher::all();
        $levels = Level::all();
        return view('admin.createClass', compact('teachers', 'levels'));
    }
    function addClass(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'opening_day'   => 'required',
            'id_levels'     => 'required',
            'id_teachers'   => 'required',
            'total'         => 'required',
            'actual_number' => 'required',
        ]);

        $data = $request->all();
        try {
            Classroom::create([
                'name'          =>  $data['name'],
                'opening_day'   =>  $data['opening_day'],
                'id_levels'     =>  $data['id_levels'],
                'id_teachers'   =>  $data['id_teachers'],
                'level_id'      =>  $data['id_teachers'],
                'total'         =>  $data['total'],
                'actual_number' =>  $data['actual_number'],
            ]);
        } catch (ModelNotFoundException $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect('admin')->with('success', 'Thêm lớp thành công');
    }
    function listClass()
    {
        $classrooms = Classroom::join('teachers', 'classrooms.id_teachers', '=', 'teachers.id')
            ->select('classrooms.*', 'teachers.name as teacher_name')
            ->get();
        return view('admin.listClass', compact('classrooms'))->with(['index' => 1]);
    }
    function attendanceInfor($id)
    {
        $classroom = Classroom::where('classrooms.id', '=', $id)
            ->first();

        $edit = Attendance::where('attendances.id_classrooms', '=', $id)
            ->get();
        if (count($edit) > 0) {
            return redirect('admin/listClass')->with('success', 'Lớp học đã điểm danh');
        }
        $students = Classroom::where('classrooms.id', '=', $id)
            ->join('class_registers', 'classrooms.id', '=', 'class_registers.id_classrooms')
            ->join('students', 'class_registers.id_students', '=', 'students.id')
            ->select('classrooms.*', 'class_registers.id_students', 'students.name as name_student')
            ->get();
        return view('admin.attendanceInfor', compact('classroom', 'students'))->with(['index' => 1]);
    }

    function attendance(Request $request, $id)
    {
        $students = Classroom::where('classrooms.id', '=', $id)
            ->join('class_registers', 'classrooms.id', '=', 'class_registers.id_classrooms')
            ->join('students', 'class_registers.id_students', '=', 'students.id')
            ->select('classrooms.*', 'class_registers.id_students', 'students.name as name_student')
            ->get();
        $data = $request->all();
        foreach ($students as $student) {
            //mỗi records là 1 mảng
            //tạo mảng chứa các records
            //insert từng dòng cho mảng
            $attendances = [
                'id_classrooms' => $id,
                'id_students'   => $student->id_students,
                'status'        => $data['status'][$student->id_students],
                'note'          => $data['note'][$student->id_students],
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ];
            DB::table('attendances')->insert($attendances);

            //$attendance = new Attendance($datasave);
            //$attendance->save();
        }
        return redirect('admin/listClass')->with('success', 'Điểm danh thành công');
    }
}
