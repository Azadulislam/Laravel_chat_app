<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name', 'Design Review') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-800">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Design Review</span>
                    </a>
                    <div class="flex items-center space-x-1">
                        @auth
                            <a href="{{ route('home') }}" class="nav-link px-4 py-2 text-slate-600 hover:text-slate-900 font-medium rounded-lg hover:bg-slate-100 transition-all">Home</a>
                            @if(auth()->user()->isAdmin())
                                <div class="relative group">
                                    <button class="nav-link px-4 py-2 text-slate-600 hover:text-slate-900 font-medium rounded-lg hover:bg-slate-100 transition-all flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <span>Admin</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div class="absolute right-0 mt-2 w-56 bg-white rounded-sm border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 translate-y-2 z-50">
                                        <div class="p-2">
                                            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-slate-700 hover:bg-slate-50 rounded-lg transition-all">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                                </svg>
                                                <span class="font-medium">Dashboard</span>
                                            </a>
                                            <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 px-4 py-3 text-slate-700 hover:bg-slate-50 rounded-lg transition-all">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                                <span class="font-medium">Users</span>
                                            </a>
                                            <a href="{{ route('admin.projects') }}" class="flex items-center space-x-3 px-4 py-3 text-slate-700 hover:bg-slate-50 rounded-lg transition-all">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                <span class="font-medium">Projects</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-center space-x-3 ml-4 pl-4 border-l border-slate-200">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr(auth()->user()->username ?? auth()->user()->name, 0, 1) }}
                                    </div>
                                    <span class="text-slate-700 font-medium">{{ auth()->user()->username ?? auth()->user()->name }}</span>
                                </div>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-all font-medium">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('login') }}" class="px-4 py-2 text-slate-600 hover:text-slate-900 font-medium rounded-lg hover:bg-slate-100 transition-all">Login</a>
                                <a href="{{ route('register') }}" class="btn-primary px-4 py-2 text-white font-medium rounded-lg">Get Started</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (strlen(trim($__env->yieldContent('content'))))
                    @yield('content')
                @else
                    {{ $slot }}
                @endif
            </div>
        </main>

        <!-- Floating Chat Button and Drawer -->
        @auth
            @unless(request()->routeIs('chat.index') || request()->routeIs('chat.show'))
                <div class="fixed bottom-6 right-6 z-50" x-data="{ open: false }">
                    <!-- Drawer -->
                    <div x-show="open" 
                         x-transition:enter="transform transition ease-in-out duration-300"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in-out duration-300"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         class="fixed bottom-20 right-6 w-96 h-[500px] bg-white rounded-lg shadow-xl border border-slate-200 flex flex-col">
                        <!-- Drawer Header -->
                        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-800">Messages</h3>
                            <button @click="open = false" class="text-slate-500 hover:text-slate-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Drawer Content -->
                        <div class="flex-1 overflow-y-auto p-4" id="floating-conversation-list">
                            <!-- Conversations will be loaded here -->
                            <div class="text-center py-8 text-slate-400" id="loading-chats">
                                <svg class="w-8 h-8 mx-auto mb-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Loading...
                            </div>
                        </div>

                        <!-- Drawer Footer -->
                        <div class="p-4 border-t border-slate-200">
                            <a href="{{ route('chat.index') }}" class="btn-primary w-full text-center py-2 px-4 text-white rounded-lg">View All Messages</a>
                        </div>
                    </div>

                    <!-- Floating Button -->
                    <button @click="open = !open" 
                            class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full shadow-lg flex items-center justify-center text-white hover:scale-105 transition-transform relative">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center" id="unread-badge" style="display: none;">0</span>
                    </button>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Load unread count
                        function loadUnreadCount() {
                            fetch('/chat/unread-count')
                                .then(response => response.json())
                                .then(data => {
                                    const badge = document.getElementById('unread-badge');
                                    if (badge) {
                                        if (data.count > 0) {
                                            badge.textContent = data.count;
                                            badge.style.display = 'flex';
                                        } else {
                                            badge.style.display = 'none';
                                        }
                                    }
                                });
                        }

                        // Load conversations for floating drawer
                        function loadConversations() {
                            fetch('/chat')
                                .then(response => response.text())
                                .then(html => {
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    const conversationList = doc.getElementById('conversation-list');
                                    const floatingList = document.getElementById('floating-conversation-list');
                                    if (conversationList && floatingList) {
                                        floatingList.innerHTML = conversationList.innerHTML;
                                    }
                                });
                        }

                        loadUnreadCount();
                        loadConversations();

                        // Refresh every 30 seconds
                        setInterval(loadUnreadCount, 30000);
                    });
                </script>
            @endunless
        @endauth
    </div>
</body>
</html>
