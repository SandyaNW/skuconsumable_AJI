<?php

namespace App\Http\Controllers;

use auth;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * Display register page.
     * 
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $depts =  Department::all();
        return view('auth.register_agil', compact(['depts']));
    }

    /**
     * Handle account registration request
     * 
     * @param RegisterRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    // public function register(RegisterRequest $request) 
    // {
    //     $user = User::create($request->validated());

    //     auth()->login($user);

    //     return redirect('/')->with('success', "Account successfully registered.");
    // }

    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => [
                        'required',
                        'email',
                        'unique:users,email',
                        // 'email:dns'
                        ],
            'password' => ['required','confirmed','min:5',],
            'name' => ['required','min:5','unique:users'],
            'username' => ['required','min:5','unique:users'],
            'dept_id' => ['required','filled'],
        ]);

        $data['password'] =$data['password'];
      
        $user = User::create($data);
    
        return redirect()->intended('/login')->with('success', 'Registration succesfull! Please login');
    }
}
