<?php

namespace App\Http\Controllers;

use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Events\TypingStarted;
use App\Events\TypingStopped;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (config('chat.auto_admin_groups') && $user->isAdmin()) {
            $conversations = Conversation::with(['latestMessage.user', 'participants', 'group'])
                ->get()
                ->sortByDesc(function ($conversation) {
                    return $conversation->latestMessage?->created_at ?? $conversation->created_at;
                });
        } else {
            $conversations = $user->conversations()
                ->with(['latestMessage.user', 'participants', 'group'])
                ->get()
                ->sortByDesc(function ($conversation) {
                    return $conversation->latestMessage?->created_at ?? $conversation->created_at;
                });
        }

        return view('chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();
        $this->authorize('view', $conversation);

        $conversation->load(['messages.user', 'participants', 'group']);
        
        // Mark as read
        $conversation->participants()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);

        return view('chat.show', compact('conversation'));
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        $this->authorize('view', $conversation);

        $request->validate([
            'content' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $messageData = [
            'user_id' => $user->id,
            'content' => $request->content,
        ];

        $attachments = [];
        
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'type' => $file->getClientMimeType(),
                ];
            }
        }

        if (!empty($attachments)) {
            $messageData['attachments'] = $attachments;
        }

        $message = $conversation->messages()->create($messageData);
        $message->load('user');

        broadcast(new MessageSent($message));

        return response()->json($message);
    }

    public function startConversation(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $otherUser = User::findOrFail($request->user_id);

        // Check if conversation already exists
        $existingConversation = $user->conversations()
            ->where('type', 'private')
            ->whereHas('participants', function ($query) use ($otherUser) {
                $query->where('user_id', $otherUser->id);
            })
            ->first();

        if ($existingConversation) {
            return response()->json($existingConversation);
        }

        $conversation = Conversation::create(['type' => 'private']);
        $conversation->participants()->attach([$user->id, $otherUser->id]);

        return response()->json($conversation);
    }

    public function typing(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        $this->authorize('view', $conversation);

        $isTyping = $request->input('typing', false);

        if ($isTyping) {
            broadcast(new TypingStarted($user, $conversation->id))->toOthers();
        } else {
            broadcast(new TypingStopped($user, $conversation->id))->toOthers();
        }

        return response()->json(['success' => true]);
    }

    public function markAsRead(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        $this->authorize('view', $conversation);

        $conversation->participants()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);

        $latestMessage = $conversation->latestMessage;
        if ($latestMessage) {
            broadcast(new MessageRead($user, $conversation->id, $latestMessage->id))->toOthers();
        }

        return response()->json(['success' => true]);
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('query', '');
        $user = Auth::user();

        $users = User::where('id', '!=', $user->id)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('username', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%");
            })
            ->get();

        return response()->json($users);
    }

    public function getUnreadCount()
    {
        $user = Auth::user();
        return response()->json(['count' => $user->total_unread_messages]);
    }

    public function createGroup(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'created_by' => $user->id,
        ]);

        // Add creator and selected users as members
        $memberIds = array_merge([$user->id], $request->user_ids);
        $group->members()->attach($memberIds, ['role' => 'member']);
        $group->members()->updateExistingPivot($user->id, ['role' => 'admin']);

        // Create conversation for the group
        $conversation = Conversation::create([
            'type' => 'group',
            'group_id' => $group->id,
        ]);
        $conversation->participants()->attach($memberIds);

        return response()->json($conversation->load('group', 'participants'));
    }

    public function addGroupMember(Request $request, Group $group)
    {
        $user = Auth::user();
        // Check if user is admin of the group
        if (!$group->isUserAdmin($user)) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $newMember = User::findOrFail($request->user_id);
        
        // Check if user is already a member
        if ($group->members()->where('user_id', $newMember->id)->exists()) {
            return response()->json(['message' => 'User is already a member'], 400);
        }

        $group->members()->attach($newMember->id, ['role' => 'member']);
        
        // Also add to conversation
        $conversation = $group->conversation;
        if ($conversation) {
            $conversation->participants()->attach($newMember->id);
        }

        return response()->json(['success' => true, 'user' => $newMember]);
    }

    public function removeGroupMember(Group $group, User $user)
    {
        $currentUser = Auth::user();
        // Check if user is admin of the group
        if (!$group->isUserAdmin($currentUser)) {
            abort(403, 'Unauthorized');
        }

        // Don't allow removing self
        if ($user->id === $currentUser->id) {
            abort(400, 'Cannot remove yourself');
        }

        // Don't allow removing other site admins if auto admin is enabled
        if (config('chat.auto_admin_groups') && $user->isAdmin()) {
            abort(403, 'Cannot remove site admins');
        }

        $group->members()->detach($user->id);
        
        // Also remove from conversation
        $conversation = $group->conversation;
        if ($conversation) {
            $conversation->participants()->detach($user->id);
        }

        return response()->json(['success' => true]);
    }
}
