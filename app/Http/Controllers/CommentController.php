<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Project $project, Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:2000',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'parent_id' => 'nullable|exists:comments,id',
            'element_selector' => 'nullable|string',
            'element_xpath' => 'nullable|string',
            'offset_x' => 'nullable|numeric',
            'offset_y' => 'nullable|numeric',
        ]);

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $comment = Comment::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'text' => $request->text,
            'x' => $request->x,
            'y' => $request->y,
            'element_selector' => $request->element_selector,
            'element_xpath' => $request->element_xpath,
            'offset_x' => $request->offset_x,
            'offset_y' => $request->offset_y,
            'status' => 'pending',
        ]);

        // increment pending counter
        $project->increment('pending_comments_count');

        return response()->json(['comment' => $comment], 201);
    }

    public function approve(Comment $comment)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }
        $comment->update(['status' => 'approved']);
        if ($comment->project && $comment->project->pending_comments_count > 0) {
            $comment->project->decrement('pending_comments_count');
        }
        return back();
    }

    public function reject(Comment $comment)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }
        $comment->update(['status' => 'rejected']);
        if ($comment->project && $comment->project->pending_comments_count > 0) {
            $comment->project->decrement('pending_comments_count');
        }
        return back();
    }

    public function destroy(Comment $comment)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }
        $comment->delete();
        return back();
    }
}
