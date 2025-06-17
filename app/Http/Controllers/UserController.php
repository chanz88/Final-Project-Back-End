<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('books.index');
        } else {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
    }

    function register(Request $request){
        $request->validate([
            'name' => 'required|min:3|max:40',
            'email' => 'required|email',
            'password' => 'required|min:6|max:12',
            'phone' => 'required|starts_with:08'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        return redirect()->route('books.index');
    }

    function logout(){
        Auth::logout();

        return redirect()->route('login');
    }
}