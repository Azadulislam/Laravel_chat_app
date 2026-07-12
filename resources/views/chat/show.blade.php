@extends('layouts.app')

@section('content')
@php
    $isGroup = $conversation->type === 'group';
    $displayName = $isGroup ? ($conversation->group->name ?? 'Group') : ($conversation->participants->where('id', '!=', auth()->id())->first()?->username ?? $conversation->participants->where('id', '!=', auth()->id())->first()?->name ?? 'User');
    $currentUser = auth()->user();
    $isGroupAdmin = $isGroup && $conversation->group && $conversation->group->isUserAdmin($currentUser);
@endphp

<div class="h-[calc(100vh-120px)] flex bg-white rounded-sm border border-slate-200 overflow-hidden relative" id="chat-container" data-conversation-id="{{ $conversation->id }}">
    <!-- Left Sidebar -->
    <div class="w-80 bg-[var(--message-header-bg-color)] border-r border-slate-200 flex flex-col">
        <div class="p-4 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800">Messages</h2>
        </div>
        <div class="flex-1 overflow-y-auto">
            @foreach(auth()->user()->conversations()->with(['latestMessage.user', 'participants', 'group'])->get() as $conv)
                @php
                    $convIsGroup = $conv->type === 'group';
                    $convDisplayName = $convIsGroup ? ($conv->group->name ?? 'Group') : ($conv->participants->where('id', '!=', auth()->id())->first()?->username ?? $conv->participants->where('id', '!=', auth()->id())->first()?->name ?? 'User');
                    $convUnreadCount = $conv->getUnreadCountForUser(auth()->user());
                @endphp
                <a href="{{ route('chat.show', $conv) }}" class="block p-3 hover:bg-slate-50 transition-colors border-b border-slate-100 {{ $conv->id === $conversation->id ? 'bg-blue-50 border-l-4 border-l-blue-500' : '' }}">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                            {{ $convIsGroup ? 'G' : substr($convDisplayName, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-slate-800 truncate">
                                    {{ $convDisplayName }}
                                </h3>
                                @if($conv->latestMessage)
                                    <span class="text-xs text-slate-400">{{ $conv->latestMessage->created_at->diffForHumans() }}</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-slate-500 truncate">
                                    {{ $conv->latestMessage ? $conv->latestMessage->content : 'Start a conversation' }}
                                </p>
                                @if($convUnreadCount > 0)
                                    <span class="bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">{{ $convUnreadCount }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Center Panel - Messages -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-[var(--message-header-bg-color)]">
            <div class="flex flex-1 items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ $isGroup ? 'G' : substr($displayName, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">{{ $displayName }}</h3>
                    <p class="text-sm text-slate-500" id="typing-indicator"></p>
                </div>
            </div>
            @if($isGroup)
                <button id="open-group-info" class="p-2 text-slate-500 hover:text-slate-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>
            @endif
            <a href="{{ route('chat.index') }}" class="text-lg bg-red-200 text-black hover:text-red-500 leading-1 px-1 py-2 rounded inline-block">&times;</a>
        </div>

        <!-- Messages List -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messages-container">
            @foreach($conversation->messages->sortBy('created_at') as $message)
                <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] {{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white rounded-t-lg rounded-bl-lg' : 'bg-slate-100 text-slate-800 rounded-t-lg rounded-br-lg' }} px-4 py-2">
                        @if(!$isGroup || $message->user_id !== auth()->id())
                            <p class="text-xs {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-slate-500' }} mb-1">{{ $message->user->username ?? $message->user->name }}</p>
                        @endif
                        @if($message->content)
                            <p>{{ $message->content }}</p>
                        @endif
                        @php
                            $allAttachments = [];
                            if ($message->attachment_path) {
                                $allAttachments[] = [
                                    'path' => $message->attachment_path,
                                    'name' => $message->attachment_name,
                                    'type' => $message->attachment_type,
                                ];
                            }
                            if ($message->attachments) {
                                $allAttachments = array_merge($allAttachments, $message->attachments);
                            }
                        @endphp
                        @if(count($allAttachments) > 0)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($allAttachments as $attachment)
                                    @if(str_starts_with($attachment['type'], 'image/'))
                                        <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $attachment['path']) }}" alt="{{ $attachment['name'] }}" class="max-w-[200px] max-h-[200px] rounded border border-white/30">
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank" class="flex items-center space-x-2 text-sm underline">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ $attachment['name'] }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <p class="text-xs {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-slate-400' }} mt-1 text-right">{{ $message->created_at->format('H:i') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message Input -->
        <div class="p-4 border-t border-slate-200">
            <!-- File Preview Container -->
            <div id="file-preview-container" class="mb-3 flex flex-wrap gap-2"></div>
            
            <form id="message-form" class="flex items-end space-x-3">
                <input type="file" id="attachment-input" class="hidden" accept="image/*,.pdf,.doc,.docx,.txt" multiple>
                <button type="button" id="attachment-btn" class="p-2 text-slate-500 hover:text-slate-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                </button>
                <input type="text" id="message-input" placeholder="Type a message..." class="flex-1 border border-slate-200 rounded-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" id="send-button" class="btn-primary p-3 border-0 text-white rounded-sm flex items-center justify-center">
                    <svg id="send-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path>
                    </svg>
                    <svg id="send-loading" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Right Sidebar - Group Info -->
    @if($isGroup && $conversation->group)
        <!-- Overlay -->
        <div id="group-info-overlay" class="absolute inset-0 bg-black/20 hidden z-10"></div>
        
        <!-- Drawer -->
        <div id="group-info-sidebar" class="absolute overflow-y-auto right-0 top-0 bottom-0 w-80 bg-white border-l border-slate-200 flex flex-col z-20 transform translate-x-full transition-transform duration-300">
            <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Group Info</h3>
                <div class="flex items-center space-x-2">
                    
                    <button id="close-group-info" class="text-slate-500 hover:text-slate-700">&times;</button>
                </div>
            </div>
            
            <div class="p-4 border-b border-slate-200 flex-1">
                <div class="flex justify-between">
                    <h4 class="text-sm font-semibold text-slate-500 mb-2">Members</h4>
                    @if($isGroupAdmin)
                        <button id="open-add-member-modal" class="text-blue-500 hover:text-blue-700 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    @endif
                </div>
                <div id="group-members-list" class="space-y-2">
                    @foreach($conversation->group->members as $member)
                        <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-sm" data-user-id="{{ $member->id }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ substr($member->username ?? $member->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $member->username ?? $member->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $member->pivot->role }}</p>
                                </div>
                            </div>
                            @if($isGroupAdmin && $member->id !== $currentUser->id)
                                <button class="remove-member-btn text-red-500 hover:text-red-700 text-sm">Remove</button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    
    <!-- Add Member Modal -->
    @if($isGroup && $conversation->group && $isGroupAdmin)
        <div id="add-member-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-sm p-6 w-96 max-w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Add Members</h3>
                    <button id="close-add-member-modal" class="text-slate-500 hover:text-slate-700">&times;</button>
                </div>
                <input type="text" id="add-member-search" placeholder="Search users..." class="w-full border border-slate-200 rounded-sm px-3 py-2 mb-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div id="add-member-results" class="max-h-64 overflow-y-auto space-y-2"></div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('chat-container');
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const attachmentInput = document.getElementById('attachment-input');
    const attachmentBtn = document.getElementById('attachment-btn');
    const typingIndicator = document.getElementById('typing-indicator');

    if (chatContainer) {
        const conversationId = chatContainer.dataset.conversationId;

        // Scroll to bottom
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Helper function to generate message HTML
        function createMessageHtml(message) {
            const isMe = message.user.id === {{ auth()->id() }};
            
            // Collect all attachments
            let allAttachments = [];
            if (message.attachment_path) {
                allAttachments.push({
                    path: message.attachment_path,
                    name: message.attachment_name,
                    type: message.attachment_type,
                });
            }
            if (message.attachments && message.attachments.length > 0) {
                allAttachments = [...allAttachments, ...message.attachments];
            }
            
            // Generate attachments HTML
            let attachmentsHtml = '';
            if (allAttachments.length > 0) {
                attachmentsHtml = '<div class="mt-2 flex flex-wrap gap-2">';
                allAttachments.forEach(attachment => {
                    if (attachment.type && attachment.type.startsWith('image/')) {
                        attachmentsHtml += `
                            <a href="/storage/${attachment.path}" target="_blank">
                                <img src="/storage/${attachment.path}" alt="${attachment.name}" class="max-w-[200px] max-h-[200px] rounded border ${isMe ? 'border-white/30' : 'border-slate-300'}">
                            </a>
                        `;
                    } else {
                        attachmentsHtml += `
                            <a href="/storage/${attachment.path}" target="_blank" class="flex items-center space-x-2 text-sm underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>${attachment.name}</span>
                            </a>
                        `;
                    }
                });
                attachmentsHtml += '</div>';
            }
            
            return `
                <div class="flex ${isMe ? 'justify-end' : 'justify-start'}">
                    <div class="max-w-[70%] ${isMe ? 'bg-blue-500 text-white rounded-t-lg rounded-bl-lg' : 'bg-slate-100 text-slate-800 rounded-t-lg rounded-br-lg'} px-4 py-2">
                        ${(!{{ $isGroup ? 'true' : 'false' }} || !isMe) ? `<p class="text-xs ${isMe ? 'text-blue-100' : 'text-slate-500'} mb-1">${message.user.username || message.user.name}</p>` : ''}
                        ${message.content ? `<p>${message.content}</p>` : ''}
                        ${attachmentsHtml}
                        <p class="text-xs ${isMe ? 'text-blue-100' : 'text-slate-400'} mt-1 text-right">${new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                    </div>
                </div>
            `;
        }
        
        // Listen for new messages
        window.Echo.private(`conversation.${conversationId}`)
            .listen('.MessageSent', (e) => {
                const messageHtml = createMessageHtml(e.message);
                messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            })
            .listen('.TypingStarted', (e) => {
                if (e.user.id !== {{ auth()->id() }}) {
                    typingIndicator.textContent = `${e.user.username || e.user.name} is typing...`;
                }
            })
            .listen('.TypingStopped', (e) => {
                if (e.user.id !== {{ auth()->id() }}) {
                    typingIndicator.textContent = '';
                }
            });

        // Typing indicator
        if (messageInput) {
            let typingTimeout;
            let startTypingTimeout;
            let isTypingSent = false;

            messageInput.addEventListener('input', function() {
                // If we haven't notified the server that typing started yet,
                // set a timeout to do so after 1500ms (debounce the start)
                if (!isTypingSent && !startTypingTimeout) {
                    startTypingTimeout = setTimeout(() => {
                        fetch(`/chat/conversations/${conversationId}/typing`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ typing: true }),
                        });
                        isTypingSent = true;
                        startTypingTimeout = null;
                    }, 1500);
                }

                // Clear any existing "stop typing" timeout
                clearTimeout(typingTimeout);

                // Set a new timeout to notify the server that typing stopped after 3000ms of inactivity
                typingTimeout = setTimeout(() => {
                    if (isTypingSent) {
                        fetch(`/chat/conversations/${conversationId}/typing`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ typing: false }),
                        });
                        isTypingSent = false;
                    } else {
                        // If they stopped before the 1500ms start debounce finished
                        clearTimeout(startTypingTimeout);
                        startTypingTimeout = null;
                    }
                }, 3000);
            });
        }

        // Selected files array
        let selectedFiles = [];

        // Render file previews
        function renderFilePreviews() {
            const container = document.getElementById('file-preview-container');
            container.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                const preview = document.createElement('div');
                preview.className = 'relative';
                
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'w-20 h-20 object-cover rounded border border-slate-200';
                    preview.appendChild(img);
                } else {
                    const fileIcon = document.createElement('div');
                    fileIcon.className = 'w-20 h-20 flex items-center justify-center bg-slate-100 rounded border border-slate-200';
                    fileIcon.innerHTML = `
                        <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    `;
                    preview.appendChild(fileIcon);
                }
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors';
                removeBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                `;
                removeBtn.addEventListener('click', () => {
                    selectedFiles.splice(index, 1);
                    renderFilePreviews();
                });
                preview.appendChild(removeBtn);
                
                const fileName = document.createElement('p');
                fileName.className = 'text-xs text-slate-600 mt-1 truncate w-20';
                fileName.textContent = file.name;
                preview.appendChild(fileName);
                
                container.appendChild(preview);
            });
        }

        // Attachment button
        if (attachmentBtn && attachmentInput) {
            attachmentBtn.addEventListener('click', function() {
                attachmentInput.click();
            });
            
            attachmentInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    selectedFiles = [...selectedFiles, ...Array.from(this.files)];
                    renderFilePreviews();
                    this.value = ''; // Reset input to allow re-selecting same file
                }
            });
        }

        // Send message
        if (messageForm && messageInput && attachmentInput) {
            const sendButton = document.getElementById('send-button');
            const sendIcon = document.getElementById('send-icon');
            const sendLoading = document.getElementById('send-loading');
            const filePreviewContainer = document.getElementById('file-preview-container');
            
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Set loading state
                sendButton.disabled = true;
                sendButton.classList.add('opacity-75', 'cursor-not-allowed');
                sendIcon.classList.add('hidden');
                sendLoading.classList.remove('hidden');
                
                const formData = new FormData();
                formData.append('content', messageInput.value);
                selectedFiles.forEach(file => {
                    formData.append('attachments[]', file);
                });

                fetch(`/chat/conversations/${conversationId}/messages`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    // Add message to UI immediately                   
                    messageInput.value = '';
                    selectedFiles = [];
                    filePreviewContainer.innerHTML = '';
                })
                .finally(() => {
                    // Reset button state
                    sendButton.disabled = false;
                    sendButton.classList.remove('opacity-75', 'cursor-not-allowed');
                    sendIcon.classList.remove('hidden');
                    sendLoading.classList.add('hidden');
                });
            });
        }
    }

    // Group Info Sidebar
    const openGroupInfoBtn = document.getElementById('open-group-info');
    const closeGroupInfoBtn = document.getElementById('close-group-info');
    const groupInfoSidebar = document.getElementById('group-info-sidebar');
    const groupInfoOverlay = document.getElementById('group-info-overlay');

    if (openGroupInfoBtn && groupInfoSidebar && closeGroupInfoBtn && groupInfoOverlay) {
        openGroupInfoBtn.addEventListener('click', () => {
            groupInfoOverlay.classList.remove('hidden');
            setTimeout(() => {
                groupInfoSidebar.classList.remove('translate-x-full');
            }, 10);
        });

        const closeGroupInfo = () => {
            groupInfoSidebar.classList.add('translate-x-full');
            setTimeout(() => {
                groupInfoOverlay.classList.add('hidden');
            }, 300);
        };

        closeGroupInfoBtn.addEventListener('click', closeGroupInfo);
        groupInfoOverlay.addEventListener('click', closeGroupInfo);
    }

    // Add Member Modal
    const openAddMemberModalBtn = document.getElementById('open-add-member-modal');
    const closeAddMemberModalBtn = document.getElementById('close-add-member-modal');
    const addMemberModal = document.getElementById('add-member-modal');

    if (openAddMemberModalBtn && closeAddMemberModalBtn && addMemberModal) {
        openAddMemberModalBtn.addEventListener('click', () => {
            addMemberModal.classList.remove('hidden');
            addMemberModal.classList.add('flex');
        });

        const closeAddMemberModal = () => {
            addMemberModal.classList.add('hidden');
            addMemberModal.classList.remove('flex');
        };

        closeAddMemberModalBtn.addEventListener('click', closeAddMemberModal);
        addMemberModal.addEventListener('click', (e) => {
            if (e.target === addMemberModal) {
                closeAddMemberModal();
            }
        });

        // Add members
        const addMemberSearch = document.getElementById('add-member-search');
        const addMemberResults = document.getElementById('add-member-results');
        
        if (addMemberSearch && addMemberResults) {
            const currentMemberIds = @json($conversation->group ? $conversation->group->members->pluck('id') : []);

            let addMemberTimeout;
            addMemberSearch.addEventListener('input', () => {
                clearTimeout(addMemberTimeout);
                addMemberTimeout = setTimeout(() => {
                    searchUsersToAdd(addMemberSearch.value);
                }, 300);
            });

            function searchUsersToAdd(query) {
                fetch(`/chat/users/search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(users => {
                        const filteredUsers = users.filter(u => !currentMemberIds.includes(u.id));
                        addMemberResults.innerHTML = filteredUsers.map(user => `
                            <div class="p-2 hover:bg-slate-100 cursor-pointer flex items-center space-x-3" data-user-id="${user.id}">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    ${(user.username || user.name).substr(0, 1)}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">${user.username || user.name}</p>
                                    <p class="text-xs text-slate-500">${user.email}</p>
                                </div>
                            </div>
                        `).join('');

                        addMemberResults.querySelectorAll('[data-user-id]').forEach(el => {
                            el.addEventListener('click', () => addMember(el.dataset.userId, users.find(u => u.id == el.dataset.userId)));
                        });
                    });
            }

            function addMember(userId, user) {
                fetch(`/chat/groups/{{ $conversation->group ? $conversation->group->id : '' }}/members`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ user_id: userId }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentMemberIds.push(parseInt(userId));
                        addMemberSearch.value = '';
                        addMemberResults.innerHTML = '';
                        closeAddMemberModal();
                        // Refresh page to show new member
                        window.location.reload();
                    }
                });
            }
        }

        // Remove members
        document.querySelectorAll('.remove-member-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const memberElement = this.closest('[data-user-id]');
                const userId = memberElement.dataset.userId;

                if (confirm('Are you sure you want to remove this member?')) {
                    fetch(`/chat/groups/{{ $conversation->group ? $conversation->group->id : '' }}/members/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            memberElement.remove();
                        }
                    });
                }
            });
        });
    }
});
</script>
@endsection
