<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Hash;
// use Session;
use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    function index()
    {
        return view('users.login');
    }
    function registration()
    {
        return view('users.registration');
    }
    function validateRegistration(Request $request)
    {
        $request->validate([
            'email'         =>   'required|email',
            'password'      =>   'required|min:6',
            'students_name' =>   'required',
            'birthday'      =>   'required',
            'home_town'     =>   'required',
            'address'       =>   'required',
            'phone'         =>   'required',
        ]);

        $data = $request->all();
        try {

            $user = User::create([
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'powers'   => false,
            ]);
            Student::create([
                'name'      =>  $data['students_name'],
                'birthday'  =>  $data['birthday'],
                'home_town' =>  $data['home_town'],
                'address'   =>  $data['address'],
                'level_id'  =>  0,
                'phone'     =>  $data['phone'],
                'id_users'  =>  $user->id,
            ]);
        } catch (ModelNotFoundException $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect('login')->with('success', 'Đăng ký tài khoản thành công');
    }

    function validateLogin(Request $request)
    {
        $request->validate([
            'email' =>  'required',
            'password'  =>  'required'
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) 
        {
            if (Auth::user()->powers == false) {
                return redirect('listClass');
            } else {
                return redirect('admin');
            }
        }
        return redirect('login')->with('error', 'Đăng nhập thất bại!');
    }
    function logout()
    {
        Session::flush();

        Auth::logout();

        return Redirect('login');
    }
}
