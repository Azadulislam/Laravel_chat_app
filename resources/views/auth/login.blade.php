@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="text-center mb-4">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Welcome Back
            </h2>
            <p class="mt-3 text-lg text-slate-600">
                Sign in to your account
            </p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8 card-shadow">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input 
                            id="username" 
                            type="text" 
                            class="w-full pl-12 pr-4 py-2 border border-slate-200 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50 @error('username') border-red-500 focus:ring-red-500 focus:border-red-500 bg-red-50 @enderror" 
                            name="username" 
                            value="{{ old('username') }}" 
                            required 
                            autocomplete="username" 
                            autofocus
                            placeholder="Enter your username"
                        >
                    </div>

                    @error('username')
                        <p class="mt-2 text-sm text-red-600 font-medium">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            id="password" 
                            type="password" 
                            class="w-full pl-12 pr-4 py-2 border border-slate-200 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50 @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 bg-red-50 @enderror" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="••••••••"
                        >
                    </div>

                    @error('password')
                        <p class="mt-2 text-sm text-red-600 font-medium">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <input 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded" 
                            type="checkbox" 
                            name="remember" 
                            id="remember" 
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label class="ml-2 block text-sm text-slate-600 font-medium" for="remember">
                            Remember Me
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a 
                            class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors" 
                            href="{{ route('password.request') }}"
                        >
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <button 
                    type="submit" 
                    class="btn-primary w-full py-2 px-4 text-white font-semibold rounded-sm text-lg"
                >
                    Sign In
                </button>
            </form>
            
            <div class="mt-8 pt-6 border-t border-slate-200 text-center">
                <p class="text-slate-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        Create Account
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
