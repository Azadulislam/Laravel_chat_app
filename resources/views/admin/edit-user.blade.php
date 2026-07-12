@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-sm border border-slate-200 overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50/50">
            <nav class="flex">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-4 {{ request()->routeIs('admin.dashboard') ? 'bg-white border-b-2 border-blue-500 text-blue-600' : 'text-slate-600 hover:bg-slate-100' }} font-medium">
                    <span class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </span>
                </a>
                <a href="{{ route('admin.users') }}" class="px-6 py-4 {{ request()->routeIs('admin.users*') ? 'bg-white border-b-2 border-blue-500 text-blue-600' : 'text-slate-600 hover:bg-slate-100' }} font-medium">
                    <span class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>Users</span>
                    </span>
                </a>
                <a href="{{ route('admin.projects') }}" class="px-6 py-4 {{ request()->routeIs('admin.projects*') ? 'bg-white border-b-2 border-blue-500 text-blue-600' : 'text-slate-600 hover:bg-slate-100' }} font-medium">
                    <span class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span>Projects</span>
                    </span>
                </a>
            </nav>
        </div>

        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-2">Edit User</h2>
                    <p class="text-slate-500">Update user details and permissions</p>
                </div>
                <a href="{{ route('admin.users') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-sm font-medium transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span>Back to Users</span>
                </a>
            </div>

            <div class="bg-gradient-to-br from-slate-50 to-white rounded-sm border border-slate-200 p-8">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-2xl space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-slate-200 rounded-sm px-4 py-3.5 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required />
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full border border-slate-200 rounded-sm px-4 py-3.5 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                            @error('username')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-slate-200 rounded-sm pl-12 pr-4 py-3.5 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required />
                            </div>
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Role</label>
                            <div class="grid grid-cols-3 gap-4">
                                <label class="flex items-center space-x-3 p-4 border border-slate-200 rounded-sm cursor-pointer hover:bg-slate-50 transition-all {{ old('role', $user->role) === 'user' ? 'ring-2 ring-blue-500 bg-blue-50 border-blue-300' : '' }}">
                                    <input type="radio" name="role" value="user" {{ old('role', $user->role) === 'user' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500" />
                                    <div>
                                        <div class="font-semibold text-slate-800">User</div>
                                        <div class="text-xs text-slate-500">Can create & review projects</div>
                                    </div>
                                </label>
                                <label class="flex items-center space-x-3 p-4 border border-slate-200 rounded-sm cursor-pointer hover:bg-slate-50 transition-all {{ old('role', $user->role) === 'moderator' ? 'ring-2 ring-blue-500 bg-blue-50 border-blue-300' : '' }}">
                                    <input type="radio" name="role" value="moderator" {{ old('role', $user->role) === 'moderator' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500" />
                                    <div>
                                        <div class="font-semibold text-slate-800">Moderator</div>
                                        <div class="text-xs text-slate-500">Can moderate comments</div>
                                    </div>
                                </label>
                                <label class="flex items-center space-x-3 p-4 border border-slate-200 rounded-sm cursor-pointer hover:bg-slate-50 transition-all {{ old('role', $user->role) === 'super-admin' ? 'ring-2 ring-blue-500 bg-blue-50 border-blue-300' : '' }}">
                                    <input type="radio" name="role" value="super-admin" {{ old('role', $user->role) === 'super-admin' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500" />
                                    <div>
                                        <div class="font-semibold text-slate-800">Super Admin</div>
                                        <div class="text-xs text-slate-500">Full access to everything</div>
                                    </div>
                                </label>
                            </div>
                            @error('role')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-4">
                        <a href="{{ route('admin.users') }}" class="px-6 py-3 border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-sm font-semibold transition-all">Cancel</a>
                        <button type="submit" class="btn-primary px-8 py-3 text-white font-semibold rounded-sm flex items-center space-x-2">    
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
