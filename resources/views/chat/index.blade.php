@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-120px)] flex bg-white rounded-sm border border-slate-200 overflow-hidden">
    <!-- Left Sidebar -->
    <div class="w-80 border-r border-slate-200 flex flex-col">
        <div class="p-4 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800">Messages</h2>
        </div>
        <div class="p-3 border-b border-slate-200 space-y-2">
            <input type="text" id="search-input" placeholder="Search users or conversations..." class="w-full border border-slate-200 rounded-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="flex space-x-2">
                <button id="new-chat-btn" class="flex-1 px-3 py-2 text-sm bg-blue-500 text-white rounded-sm hover:bg-blue-600 transition-colors">
                    New Chat
                </button>
                <button id="new-group-btn" class="flex-1 px-3 py-2 text-sm bg-slate-500 text-white rounded-sm hover:bg-slate-600 transition-colors">
                    New Group
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto" id="conversation-list">
            <!-- All conversations are stored here for search -->
            @php
                $allConversations = [];
                foreach($conversations as $conversation) {
                    $isGroup = $conversation->type === 'group';
                    $displayName = $isGroup ? ($conversation->group->name ?? 'Group') : ($conversation->participants->where('id', '!=', auth()->id())->first()?->username ?? $conversation->participants->where('id', '!=', auth()->id())->first()?->name ?? 'User');
                    $unreadCount = $conversation->getUnreadCountForUser(auth()->user());
                    $allConversations[] = [
                        'id' => $conversation->id,
                        'isGroup' => $isGroup,
                        'displayName' => $displayName,
                        'latestMessage' => $conversation->latestMessage?->content,
                        'latestMessageTime' => $conversation->latestMessage?->created_at?->diffForHumans(),
                        'unreadCount' => $unreadCount
                    ];
                }
            @endphp
            <div id="original-conversations" class="hidden" data-conversations='@json($allConversations)'></div>
            
            <div id="rendered-conversations">
                @foreach($conversations as $conversation)
                    @php
                        $isGroup = $conversation->type === 'group';
                        $displayName = $isGroup ? ($conversation->group->name ?? 'Group') : ($conversation->participants->where('id', '!=', auth()->id())->first()?->username ?? $conversation->participants->where('id', '!=', auth()->id())->first()?->name ?? 'User');
                        $unreadCount = $conversation->getUnreadCountForUser(auth()->user());
                    @endphp
                    <a href="{{ route('chat.show', $conversation) }}" class="block p-3 hover:bg-slate-50 transition-colors border-b border-slate-100">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                {{ $isGroup ? 'G' : substr($displayName, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-slate-800 truncate">
                                        {{ $displayName }}
                                    </h3>
                                    @if($conversation->latestMessage)
                                        <span class="text-xs text-slate-400">{{ $conversation->latestMessage->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-slate-500 truncate">
                                        {{ $conversation->latestMessage ? $conversation->latestMessage->content : 'Start a conversation' }}
                                    </p>
                                    @if($unreadCount > 0)
                                        <span class="bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Center Panel -->
    <div class="flex-1 flex flex-col">
        <div class="p-4 border-b border-slate-200 flex items-center justify-center text-slate-400">
            <p>Select a conversation to start chatting</p>
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div id="new-chat-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-sm p-6 w-96 max-w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">Start New Chat</h3>
            <button id="close-new-chat" class="text-slate-500 hover:text-slate-700">&times;</button>
        </div>
        <input type="text" id="user-search-input" placeholder="Search users..." class="w-full border border-slate-200 rounded-sm px-3 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <div id="user-search-results" class="max-h-60 overflow-y-auto space-y-2"></div>
    </div>
</div>

<!-- New Group Modal -->
<div id="new-group-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-sm p-6 w-96 max-w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">Create New Group</h3>
            <button id="close-new-group" class="text-slate-500 hover:text-slate-700">&times;</button>
        </div>
        <input type="text" id="group-name-input" placeholder="Group name" class="w-full border border-slate-200 rounded-sm px-3 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="text" id="group-user-search-input" placeholder="Search users to add..." class="w-full border border-slate-200 rounded-sm px-3 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <div id="selected-users" class="flex flex-wrap gap-2 mb-3"></div>
        <div id="group-user-search-results" class="max-h-40 overflow-y-auto space-y-2 mb-4"></div>
        <button id="create-group-btn" class="w-full px-4 py-2 bg-blue-500 text-white rounded-sm hover:bg-blue-600 transition-colors">Create Group</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('search-input');
    const originalConversationsEl = document.getElementById('original-conversations');
    const renderedConversations = document.getElementById('rendered-conversations');
    
    if (searchInput && originalConversationsEl && renderedConversations) {
        let allConversations = JSON.parse(originalConversationsEl.dataset.conversations);
        
        function renderConversations(conversations) {
            if (conversations.length === 0) {
                renderedConversations.innerHTML = '<p class="p-3 text-sm text-slate-500">No conversations found</p>';
                return;
            }
            
            renderedConversations.innerHTML = conversations.map(conv => `
                <a href="/chat/conversations/${conv.id}" class="block p-3 hover:bg-slate-50 transition-colors border-b border-slate-100">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                            ${conv.isGroup ? 'G' : conv.displayName.substr(0, 1)}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-slate-800 truncate">${conv.displayName}</h3>
                                ${conv.latestMessageTime ? `<span class="text-xs text-slate-400">${conv.latestMessageTime}</span>` : ''}
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-slate-500 truncate">${conv.latestMessage || 'Start a conversation'}</p>
                                ${conv.unreadCount > 0 ? `<span class="bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">${conv.unreadCount}</span>` : ''}
                            </div>
                        </div>
                    </div>
                </a>
            `).join('');
        }
        
        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = searchInput.value.toLowerCase().trim();
                
                if (query === '') {
                    renderConversations(allConversations);
                    return;
                }
                
                const filtered = allConversations.filter(conv => 
                    conv.displayName.toLowerCase().includes(query) ||
                    (conv.latestMessage && conv.latestMessage.toLowerCase().includes(query))
                );
                
                renderConversations(filtered);
            }, 300);
        });
    }
    
    // New Chat Modal
    const newChatBtn = document.getElementById('new-chat-btn');
    const newChatModal = document.getElementById('new-chat-modal');
    const closeNewChat = document.getElementById('close-new-chat');
    const userSearchInput = document.getElementById('user-search-input');
    const userSearchResults = document.getElementById('user-search-results');

    if (newChatBtn && newChatModal && closeNewChat && userSearchInput && userSearchResults) {
        newChatBtn.addEventListener('click', () => {
            newChatModal.classList.remove('hidden');
            newChatModal.classList.add('flex');
        });

        closeNewChat.addEventListener('click', () => {
            newChatModal.classList.add('hidden');
            newChatModal.classList.remove('flex');
        });

        newChatModal.addEventListener('click', (e) => {
            if (e.target === newChatModal) {
                newChatModal.classList.add('hidden');
                newChatModal.classList.remove('flex');
            }
        });

        let userSearchTimeout;
        userSearchInput.addEventListener('input', () => {
            clearTimeout(userSearchTimeout);
            userSearchTimeout = setTimeout(() => {
                searchUsers(userSearchInput.value, userSearchResults, startConversation);
            }, 300);
        });

        function searchUsers(query, resultsContainer, onSelect) {
            fetch(`/chat/users/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(users => {
                    resultsContainer.innerHTML = users.map(user => `
                        <div class="p-3 hover:bg-slate-100 cursor-pointer flex items-center space-x-3" data-user-id="${user.id}">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                ${(user.username || user.name).substr(0, 1)}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">${user.username || user.name}</p>
                                <p class="text-sm text-slate-500">${user.email}</p>
                            </div>
                        </div>
                    `).join('');

                    resultsContainer.querySelectorAll('[data-user-id]').forEach(el => {
                        el.addEventListener('click', () => onSelect(el.dataset.userId));
                    });
                });
        }

        function startConversation(userId) {
            fetch('/chat/conversations', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ user_id: userId }),
            })
            .then(response => response.json())
            .then(conversation => {
                window.location.href = `/chat/conversations/${conversation.id}`;
            });
        }
    }

    // New Group Modal
    const newGroupBtn = document.getElementById('new-group-btn');
    const newGroupModal = document.getElementById('new-group-modal');
    const closeNewGroup = document.getElementById('close-new-group');
    const groupNameInput = document.getElementById('group-name-input');
    const groupUserSearchInput = document.getElementById('group-user-search-input');
    const groupUserSearchResults = document.getElementById('group-user-search-results');
    const selectedUsers = document.getElementById('selected-users');
    const createGroupBtn = document.getElementById('create-group-btn');

    if (newGroupBtn && newGroupModal && closeNewGroup && groupNameInput && groupUserSearchInput && groupUserSearchResults && selectedUsers && createGroupBtn) {
        let selectedUserIds = [];
        let selectedUsersData = [];

        newGroupBtn.addEventListener('click', () => {
            newGroupModal.classList.remove('hidden');
            newGroupModal.classList.add('flex');
        });

        closeNewGroup.addEventListener('click', () => {
            newGroupModal.classList.add('hidden');
            newGroupModal.classList.remove('flex');
        });

        newGroupModal.addEventListener('click', (e) => {
            if (e.target === newGroupModal) {
                newGroupModal.classList.add('hidden');
                newGroupModal.classList.remove('flex');
            }
        });

        let groupUserSearchTimeout;
        groupUserSearchInput.addEventListener('input', () => {
            clearTimeout(groupUserSearchTimeout);
            groupUserSearchTimeout = setTimeout(() => {
                searchGroupUsers(groupUserSearchInput.value);
            }, 300);
        });

        function searchGroupUsers(query) {
            fetch(`/chat/users/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(users => {
                    const filteredUsers = users.filter(u => !selectedUserIds.includes(u.id));
                    groupUserSearchResults.innerHTML = filteredUsers.map(user => `
                        <div class="p-3 hover:bg-slate-100 cursor-pointer flex items-center space-x-3" data-user-id="${user.id}" data-user='${JSON.stringify(user)}'>
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                ${(user.username || user.name).substr(0, 1)}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">${user.username || user.name}</p>
                                <p class="text-sm text-slate-500">${user.email}</p>
                            </div>
                        </div>
                    `).join('');

                    groupUserSearchResults.querySelectorAll('[data-user-id]').forEach(el => {
                        el.addEventListener('click', () => addUserToGroup(el.dataset.userId, JSON.parse(el.dataset.user)));
                    });
                });
        }

        function addUserToGroup(userId, user) {
            selectedUserIds.push(parseInt(userId));
            selectedUsersData.push(user);
            updateSelectedUsers();
            searchGroupUsers(groupUserSearchInput.value);
        }

        function removeUserFromGroup(userId) {
            selectedUserIds = selectedUserIds.filter(id => id != userId);
            selectedUsersData = selectedUsersData.filter(u => u.id != userId);
            updateSelectedUsers();
            searchGroupUsers(groupUserSearchInput.value);
        }

        function updateSelectedUsers() {
            selectedUsers.innerHTML = selectedUsersData.map(user => `
                <div class="flex items-center space-x-2 bg-slate-100 px-3 py-1 rounded-full">
                    <span class="text-sm text-slate-800">${user.username || user.name}</span>
                    <button type="button" class="text-slate-500 hover:text-slate-700" data-remove-user="${user.id}">&times;</button>
                </div>
            `).join('');

            selectedUsers.querySelectorAll('[data-remove-user]').forEach(btn => {
                btn.addEventListener('click', () => removeUserFromGroup(btn.dataset.removeUser));
            });
        }

        createGroupBtn.addEventListener('click', () => {
            const groupName = groupNameInput.value.trim();
            if (!groupName || selectedUserIds.length === 0) return;

            fetch('/chat/groups', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    name: groupName,
                    user_ids: selectedUserIds 
                }),
            })
            .then(response => response.json())
            .then(conversation => {
                window.location.href = `/chat/conversations/${conversation.id}`;
            });
        });
    }
});
</script>
@endsection
