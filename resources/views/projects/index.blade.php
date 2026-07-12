@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Projects</h2>

    <form method="POST" action="{{ route('projects.store') }}" class="flex space-x-2">
        @csrf
        <input name="project_url" placeholder="https://example.com" class="flex-1 border px-3 py-2" />
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Add</button>
        @foreach($projects as $p)
            <a href="{{ route('projects.proxy', $p) }}" class="block bg-gray-50 p-4 rounded shadow-sm">
                <div class="font-semibold">{{ $p->project_url }}</div>
                <div class="text-sm text-gray-600">by {{ $p->user->username ?? $p->user->name }}</div>
            </a>
                <div class="font-semibold">{{ $p->project_url }}</div>
    </div>

    <div class="mt-6">{{ $projects->links() }}</div>
</div>
@endsection
