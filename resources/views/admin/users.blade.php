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
                <h2 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Manage Users</h2>
                <span class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-semibold rounded-full">{{ $users->count() }} users</span>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-sm flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-emerald-800 font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-sm flex items-center space-x-3">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="text-red-800 font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Projects</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Comments</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($users as $user)
                            <tr class="table-row">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">#{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-sm flex items-center justify-center text-white font-bold text-lg">
                                            {{ substr($user->username ?? $user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                            <div class="text-sm text-slate-500">{{ $user->username }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                        @if($user->role === 'super-admin') bg-purple-100 text-purple-800
                                        @elseif($user->role === 'moderator') bg-blue-100 text-blue-800
                                        @else bg-slate-100 text-slate-800 @endif">
                                        {{ ucfirst(str_replace('-', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-700">{{ $user->projects_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-700">{{ $user->comments_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-4">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 transition-colors">Edit</a>
                                    @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-slate-200">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
