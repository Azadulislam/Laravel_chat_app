@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="text-center mb-4">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Create Account
            </h2>
            <p class="mt-3 text-lg text-slate-600">
                Join us and get started today
            </p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8 card-shadow">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                        Full Name
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input 
                            id="name" 
                            type="text" 
                            class="w-full pl-12 pr-4 py-2 border border-slate-200 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50 @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 bg-red-50 @enderror" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autocomplete="name" 
                            autofocus
                            placeholder="John Doe"
                        >
                    </div>

                    @error('name')
                        <p class="mt-2 text-sm text-red-600 font-medium">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

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
                            placeholder="johndoe"
                        >
                    </div>

                    @error('username')
                        <p class="mt-2 text-sm text-red-600 font-medium">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input 
                            id="email" 
                            type="email" 
                            class="w-full pl-12 pr-4 py-2 border border-slate-200 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50 @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 bg-red-50 @enderror" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email"
                            placeholder="you@example.com"
                        >
                    </div>

                    @error('email')
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
                            autocomplete="new-password"
                            placeholder="••••••••"
                        >
                    </div>

                    @error('password')
                        <p class="mt-2 text-sm text-red-600 font-medium">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label for="password-confirm" class="block text-sm font-semibold text-slate-700 mb-2">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <input 
                            id="password-confirm" 
                            type="password" 
                            class="w-full pl-12 pr-4 py-2 border border-slate-200 rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="btn-primary w-full py-2 px-4 text-white font-semibold rounded-sm text-lg"
                >
                    Create Account
                </button>
            </form>
            
            <div class="mt-8 pt-6 border-t border-slate-200 text-center">
                <p class="text-slate-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        Sign In
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
