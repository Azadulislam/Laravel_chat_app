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
            <h2 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-8">Admin Dashboard</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-blue-700 font-semibold mb-1">Users</div>
                            <div class="text-4xl font-extrabold text-blue-900">{{ $stats['users'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-purple-700 font-semibold mb-1">Projects</div>
                            <div class="text-4xl font-extrabold text-purple-900">{{ $stats['projects'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-purple-500 rounded-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-amber-700 font-semibold mb-1">Pending Comments</div>
                            <div class="text-4xl font-extrabold text-amber-900">{{ $stats['pending_comments'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-amber-500 rounded-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-emerald-700 font-semibold mb-1">Approved Comments</div>
                            <div class="text-4xl font-extrabold text-emerald-900">{{ $stats['approved_comments'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500 rounded-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-800">All Comments</h3>
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 text-sm font-medium rounded-full">{{ $comments->count() }} comments</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Project</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Text</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($comments as $comment)
                            <tr class="table-row">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">#{{ $comment->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                            {{ substr($comment->user->username ?? $comment->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-slate-800">{{ $comment->user->username ?? $comment->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a class="text-blue-600 hover:text-blue-800 font-medium text-sm" href="{{ route('projects.proxy', $comment->project) }}">
                                        {{ Str::limit($comment->project->local_path ?: $comment->project->project_url, 35) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">{{ Str::limit($comment->text, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($comment->status === 'approved') bg-emerald-100 text-emerald-800
                                        @elseif($comment->status === 'pending') bg-amber-100 text-amber-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($comment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $comment->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                    @if($comment->status !== 'approved')
                                        <form method="POST" action="{{ route('comments.approve', $comment) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-emerald-600 hover:text-emerald-800 transition-colors font-medium">Approve</button>
                                        </form>
                                    @endif
                                    @if($comment->status !== 'rejected')
                                        <form method="POST" action="{{ route('comments.reject', $comment) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-amber-600 hover:text-amber-800 transition-colors font-medium">Reject</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors font-medium">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-slate-200">
                    {{ $comments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
