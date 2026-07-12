@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-sm border border-slate-200 p-4">
        <div class="flex items-center space-x-3 mb-2">
            <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-sm flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Register your Project</h2>
            </div>
        </div>

        <form method="POST" action="{{ route('projects.store') }}" class="space-y-5">
            @csrf
            <div class="flex gap-5 items-end">
                <div class="flex-1 ">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Remote URL</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <input type="url" name="project_url" placeholder="https://example.com" class="w-full border border-slate-200 text-md rounded-sm pl-12 pr-4 py-2 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-slate-50" />
                    </div>
                </div>
                <div class="flex justify-center d-block">
                    <button type="submit" class="btn-primary px-8 py-2 text-white  rounded-sm text-md border-0 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Add Project</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-sm border border-slate-200 p-4">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-sm flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">All Projects</h2>
            </div>
            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-sm font-medium rounded-full">{{ $projects->count() }} projects</span>
        </div>

        @if($projects->isEmpty())
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-2">No projects yet</h3>
                <p class="text-slate-500">Create your first project to get started!</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($projects as $project)
                    <div class="group border border-slate-200 rounded-sm px-4 py-3 hover:border-slate-300 transition-all bg-gradient-to-r from-white to-slate-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-sm flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <a href="{{ route('projects.proxy', $project) }}" class="text-xl font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                                        {{ Str::limit($project->local_path ?: $project->project_url, 60) }}
                                    </a>
                                    <div class="mt-1 flex items-center space-x-4 text-sm text-slate-500">
                                        <span class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span>Created by {{ $project->user->username ?? $project->user->name }}</span>
                                        </span>
                                        <span class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $project->created_at->diffForHumans() }}</span>
                                        </span>
                                        <span class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            <span>{{ $project->comments_count }} comments</span>
                                        </span>
                                    </div>
                                    @if($project->local_path)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                                            Local Project
                                        </span>
                                    @endif
                                    @if($project->folder_name)
                                        <div class="text-xs text-slate-400 mt-1">Folder: {{ $project->folder_name }}</div>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('projects.proxy', $project) }}" class="btn-primary px-6 py-2 text-md text-white rounded-sm flex items-center space-x-2 group-hover:scale-105 transition-transform">
                                <span>Review</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
