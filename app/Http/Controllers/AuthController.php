<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Login Page
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | Show Register Page
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | Register Customer
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'required'
        ]);

        $customerRole = Role::where('role_name', 'customer')->first();

        $user = User::create([
            'role_id' => $customerRole->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => true
        ]);

        Auth::login($user);

        return redirect('/products');
    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            UserLog::create([
                'user_id' => Auth::id(),
                'action' => 'login'
            ]);

            return redirect('/products');
        }

        return back()->withErrors([
            'email' => 'Login gagal'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        UserLog::create([
            'user_id' => Auth::id(),
            'action' => 'logout'
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}