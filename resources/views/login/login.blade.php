<!DOCTYPE html>
<!--
Template Name: Icewall - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Icewall admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Icewall Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>Login - Midone - Tailwind HTML Admin Template</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="" class="-intro-x flex items-center pt-5">
                        <img alt="Midone - HTML Admin Template" class="w-6" src="dist/images/logo.svg">
                        <span class="text-white text-lg ml-3"> Icewall </span> 
                    </a>
                    <div class="my-auto">
                        <img alt="Midone - HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="dist/images/illustration.svg">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            A few more clicks to 
                            <br>
                            sign in to your account.
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">Manage all your e-commerce accounts in one place</div>
                    </div>
                </div>
                <!-- END: Login Info -->
                <!-- BEGIN: Login Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Sign In
                        </h2>
                        <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place</div>
                        <div class="intro-x mt-8">
                            <form id="login-form" method="POST" action="{{ route('login.check') }}">
                                @csrf
                                <input type="email" id="email" name="email" class="intro-x login__input form-control py-3 px-4 block" placeholder="Email" required>
                                <div id="error-email" class="login__input-error text-danger mt-2"></div>
                                <input type="password" id="password" name="password" class="intro-x login__input form-control py-3 px-4 block mt-4" placeholder="Password" required>
                                <div id="error-password" class="login__input-error text-danger mt-2"></div>
                            </form>
                        </div>
                        <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input id="remember-me" type="checkbox" class="form-check-input border mr-2">
                                <label class="cursor-pointer select-none" for="remember-me">Remember me</label>
                            </div>
                            <a href="">Forgot Password?</a> 
                        </div>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button id="btn-login" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Login</button>
                            <!-- <button class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top">Register</button> -->
                        </div>
                        <!-- <div class="intro-x mt-10 xl:mt-24 text-slate-600 dark:text-slate-500 text-center xl:text-left"> By signin up, you agree to our <a class="text-primary dark:text-slate-200" href="">Terms and Conditions</a> & <a class="text-primary dark:text-slate-200" href="">Privacy Policy</a> </div> -->
                    </div>
                </div>
                <!-- END: Login Form -->
            </div>
        </div>
        @include('_partials.scripts')
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loginForm = document.getElementById('login-form');
                const btnLogin = document.getElementById('btn-login');
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');
                const errorEmail = document.getElementById('error-email');
                const errorPassword = document.getElementById('error-password');

                // Clear previous errors
                function clearErrors() {
                    errorEmail.innerHTML = '';
                    errorPassword.innerHTML = '';
                    emailInput.classList.remove('border-danger');
                    passwordInput.classList.remove('border-danger');
                }

                // Handle login form submission
                async function handleLogin() {
                    clearErrors();
                    
                    const email = emailInput.value.trim();
                    const password = passwordInput.value.trim();

                    // Basic validation
                    if (!email || !password) {
                        if (!email) {
                            errorEmail.innerHTML = 'Email is required';
                            emailInput.classList.add('border-danger');
                        }
                        if (!password) {
                            errorPassword.innerHTML = 'Password is required';
                            passwordInput.classList.add('border-danger');
                        }
                        return;
                    }

                    // Show loading state
                    btnLogin.innerHTML = 'Logging in...';
                    btnLogin.disabled = true;

                    try {
                        // Get CSRF token from meta tag
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        const response = await fetch('{{ route("login.check") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ email, password })
                        });

                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);

                        if (response.ok) {
                            const data = await response.json();
                            console.log('Success response:', data);
                            
                            // Login successful
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                window.location.href = '/';
                            }
                        } else {
                            // Try to get error message from response
                            let errorMessage = 'Login failed. Please check your credentials.';
                            
                            try {
                                const errorData = await response.json();
                                if (errorData.message) {
                                    errorMessage = errorData.message;
                                }
                            } catch (e) {
                                console.log('Could not parse error response');
                            }
                            
                            errorPassword.innerHTML = errorMessage;
                            passwordInput.classList.add('border-danger');
                        }
                    } catch (error) {
                        console.error('Login error:', error);
                        errorPassword.innerHTML = 'Network error. Please check your connection and try again.';
                        passwordInput.classList.add('border-danger');
                    } finally {
                        // Reset button state
                        btnLogin.innerHTML = 'Login';
                        btnLogin.disabled = false;
                    }
                }

                // Event listeners
                btnLogin.addEventListener('click', handleLogin);
                
                loginForm.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        handleLogin();
                    }
                });
            });
        </script>
    </body>
</html>