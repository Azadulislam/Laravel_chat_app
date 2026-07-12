@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-sm border border-slate-200 overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50/50">
            <nav class="flex">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-4 {{ request()->routeIs('admin.dashboard') ? 'bg-white border-b-2 border-blue-500 text-blue-600' : 'text-slate-600 hover:bg-slate-100' }} font-medium">
                    <span class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
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
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-2">Edit Project</h2>
                    <p class="text-slate-500">Update project details</p>
                </div>
                <a href="{{ route('admin.projects') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-sm font-medium transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span>Back to Projects</span>
                </a>
            </div>

            <div class="bg-gradient-to-br from-slate-50 to-white rounded-sm border border-slate-200 p-8">
                <form method="POST" action="{{ route('admin.projects.update', $project) }}" class="max-w-2xl space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Remote URL</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <input type="url" name="project_url" value="{{ old('project_url', $project->project_url) }}" class="w-full border border-slate-200 rounded-sm pl-12 pr-4 py-3.5 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="https://example.com" />
                            </div>
                            @error('project_url')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Local Path</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="local_path" value="{{ old('local_path', $project->local_path) }}" class="w-full border border-slate-200 rounded-sm pl-12 pr-4 py-3.5 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="D:\panibazar\rajshahi\dist" />
                            </div>
                            @error('local_path')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-sm">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-amber-800">
                                <strong class="font-semibold">Note:</strong> Only fill in one of the fields. If both are provided, local path will take priority.
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-4">
                        <a href="{{ route('admin.projects') }}" class="px-6 py-3 border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-sm font-semibold transition-all">Cancel</a>
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
