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
        // Debug: Log all request data
        Log::info('Login attempt - Full request data', [
            'all_data' => $request->all(),
            'email' => $request->input('email'),
            'password' => $request->has('password') ? '[HIDDEN]' : 'NOT_FOUND',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'x_requested_with' => $request->header('X-Requested-With'),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        // Validate the request - handle both JSON and form data
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        $email = $validated['email'];
        $password = $validated['password'];
        $remember = $request->boolean('remember');

        // Check if user exists
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            Log::warning('Login failed: User not found', ['email' => $email]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        // Attempt authentication
        if (!Auth::attempt([
            'email' => $email,
            'password' => $password
        ], $remember)) {
            Log::warning('Login failed: Invalid credentials', ['email' => $email]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        // Log successful login
        Log::info('Login successful', [
            'user_id' => Auth::id(),
            'email' => $email
        ]);

        return response()->json([
            'success' => true,
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

    /**
     * Debug method to show existing users (temporary - remove in production)
     */
    public function debugUsers()
    {
        $users = \App\Models\User::all(['id', 'name', 'email']);
        return response()->json([
            'message' => 'Existing users in database',
            'users' => $users,
            'note' => 'Use these credentials to login. Password is likely "password" for seeded users.'
        ]);
    }
}
