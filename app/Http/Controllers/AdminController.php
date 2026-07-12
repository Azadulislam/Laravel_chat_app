<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'projects' => Project::count(),
            'pending_comments' => \App\Models\Comment::where('status', 'pending')->count(),
            'approved_comments' => \App\Models\Comment::where('status', 'approved')->count(),
        ];

        $comments = \App\Models\Comment::with(['user','project'])->latest()->paginate(25);
        return view('admin.dashboard', compact('stats', 'comments'));
    }

    public function users()
    {
        $users = User::withCount(['projects', 'comments'])->latest()->paginate(25);
        return view('admin.users', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,moderator,super-admin'
        ]);

        $user->update($request->only(['name', 'username', 'email', 'role']));

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

    public function projects()
    {
        $projects = Project::with(['user'])->withCount('comments')->latest()->paginate(25);
        return view('admin.projects', compact('projects'));
    }

    public function editProject(Project $project)
    {
        return view('admin.edit-project', compact('project'));
    }

    public function updateProject(Request $request, Project $project)
    {
        $request->validate([
            'project_url' => 'nullable|url',
            'local_path' => 'nullable|string'
        ]);

        $project->update($request->only(['project_url', 'local_path']));

        return redirect()->route('admin.projects')->with('success', 'Project updated successfully');
    }

    public function deleteProject(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects')->with('success', 'Project deleted successfully');
    }
}
