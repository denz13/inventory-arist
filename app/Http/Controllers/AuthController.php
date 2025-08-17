<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController
{
    /**
     * Show specified view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginView()
    {
        return view('login.login', [
            'layout' => 'login'
        ]);
    }

    /**
     * Authenticate login user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Log the login attempt
        Log::info('Login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if user exists
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user) {
            Log::warning('Login failed: User not found', ['email' => $request->email]);
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        // Attempt authentication
        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->boolean('remember'))) {
            Log::warning('Login failed: Invalid credentials', ['email' => $request->email]);
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        // Log successful login
        Log::info('Login successful', [
            'user_id' => Auth::id(),
            'email' => $request->email
        ]);

        return response()->json([
            'message' => 'Login successful',
            'redirect' => route('home')
        ]);
    }

    /**
     * Logout user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $userId = Auth::id();
        $email = Auth::user()?->email;
        
        Auth::logout();
        
        Log::info('User logged out', [
            'user_id' => $userId,
            'email' => $email
        ]);
        
        return redirect('login');
    }
}
