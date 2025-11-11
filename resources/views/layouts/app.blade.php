<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel UKK Sarpras') }}</title>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite('resources/css/app.css')
    
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        }
        .font-display {
            font-family: 'Poppins', 'Inter', sans-serif;
        }
        
        /* Responsive Table */
        @media (max-width: 768px) {
            table {
                font-size: 0.875rem;
            }
            th, td {
                padding: 0.5rem !important;
            }
        }
        
        /* Responsive Cards */
        @media (max-width: 640px) {
            .stat-card {
                min-width: 100%;
            }
        }
        
        /* Modal Responsive */
        @media (max-width: 640px) {
            .modal-content {
                margin: 1rem;
                max-width: calc(100vw - 2rem);
            }
        }
        
        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Smooth transitions for sidebar notifications */
        #notificationDropdownSidebar {
            transition: all 0.3s ease-in-out;
        }
        
        /* Custom scrollbar for notification list */
        #notificationListSidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        #notificationListSidebar::-webkit-scrollbar-track {
            background: #f3e8ff;
        }
        
        #notificationListSidebar::-webkit-scrollbar-thumb {
            background: #a855f7;
            border-radius: 2px;
        }
        
        #notificationListSidebar::-webkit-scrollbar-thumb:hover {
            background: #9333ea;
        }
    </style>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden"> 

    {{-- Mobile Menu Overlay --}}
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden" onclick="toggleMobileMenu()"></div>

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Wrapper konten --}}
    <div class="flex flex-col w-full h-screen lg:ml-64">

        {{-- Navbar --}}
        <header class="bg-white shadow px-4 sm:px-6 py-3 sm:py-4 flex justify-between items-center flex-shrink-0 z-20">
            <div class="flex items-center gap-3">
                {{-- Mobile Menu Button --}}
                <button onclick="toggleMobileMenu()" class="lg:hidden text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <h1 class="text-base sm:text-xl font-bold text-gray-700">
                    Dashboard {{ ucfirst(auth()->user()->role) }}
                </h1>
            </div>
        </header>

        {{-- Konten scrollable --}}
        <main class="flex-1 overflow-y-auto">
            <div class="min-h-full p-4 sm:p-6">
                @yield('content')
            </div>
            
            {{-- Footer --}}
            <footer class="bg-white/50 backdrop-blur-sm text-center py-3 text-xs sm:text-sm text-gray-500 border-t border-gray-200 mt-8">
                &copy; {{ date('Y') }} Aplikasi Sarpras UKK
            </footer>
        </main>
    </div>

    {{-- Mobile Menu Toggle Script --}}
    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Close menu when clicking outside on mobile
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebar')?.classList.remove('-translate-x-full');
                document.getElementById('mobile-overlay')?.classList.add('hidden');
            }
        });

        // ========== NOTIFICATION SYSTEM (SIDEBAR) ==========
        const notificationBellSidebar = document.getElementById('notificationBellSidebar');
        const notificationDropdownSidebar = document.getElementById('notificationDropdownSidebar');
        const notificationListSidebar = document.getElementById('notificationListSidebar');
        const notificationCountSidebar = document.getElementById('notificationCountSidebar');
        const loadingNotificationsSidebar = document.getElementById('loadingNotificationsSidebar');
        const emptyNotificationsSidebar = document.getElementById('emptyNotificationsSidebar');
        const notificationChevron = document.getElementById('notificationChevron');

        let isDropdownOpenSidebar = false;

        // Check if elements exist (only for user role)
        if (notificationBellSidebar) {
            // Toggle dropdown
            notificationBellSidebar.addEventListener('click', function(e) {
                e.stopPropagation();
                isDropdownOpenSidebar = !isDropdownOpenSidebar;
                
                if (isDropdownOpenSidebar) {
                    notificationDropdownSidebar.classList.remove('hidden');
                    notificationChevron.style.transform = 'rotate(180deg)';
                    loadNotificationsSidebar();
                } else {
                    notificationDropdownSidebar.classList.add('hidden');
                    notificationChevron.style.transform = 'rotate(0deg)';
                }
            });

            // Load notifications
            function loadNotificationsSidebar() {
                if (!loadingNotificationsSidebar || !emptyNotificationsSidebar || !notificationListSidebar) return;
                
                loadingNotificationsSidebar.classList.remove('hidden');
                emptyNotificationsSidebar.classList.add('hidden');
                
                // Remove existing notification items
                const existingNotifications = notificationListSidebar.querySelectorAll('.notification-item');
                existingNotifications.forEach(item => item.remove());

                fetch('{{ route("notifikasi.getUnread") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    loadingNotificationsSidebar.classList.add('hidden');
                    
                    if (data.notifikasi && data.notifikasi.length > 0) {
                        emptyNotificationsSidebar.classList.add('hidden');
                        
                        // Update badge count
                        updateNotificationCountSidebar(data.notifikasi.length);
                        
                        // Display notifications (max 5 for sidebar)
                        data.notifikasi.slice(0, 5).forEach(notif => {
                            const notifElement = createNotificationElementSidebar(notif);
                            notificationListSidebar.appendChild(notifElement);
                        });
                    } else {
                        emptyNotificationsSidebar.classList.remove('hidden');
                        updateNotificationCountSidebar(0);
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    loadingNotificationsSidebar.classList.add('hidden');
                    emptyNotificationsSidebar.classList.remove('hidden');
                });
            }

            // Create notification element for sidebar
            function createNotificationElementSidebar(notif) {
                const div = document.createElement('div');
                div.className = 'notification-item border-b border-purple-100 hover:bg-white transition-colors duration-150';
                
                const bgColor = notif.is_read ? 'bg-white' : 'bg-purple-50';
                
                div.innerHTML = `
                    <div class="p-3 ${bgColor}">
                        <div class="flex items-start gap-2">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900 mb-0.5 truncate">${notif.judul}</p>
                                <p class="text-[11px] text-gray-600 line-clamp-2 mb-1">${notif.isi}</p>
                                <p class="text-[10px] text-gray-400">${formatDate(notif.created_at)}</p>
                            </div>
                        </div>
                        ${!notif.is_read ? `
                            <button onclick="markAsReadSidebar(${notif.id_notifikasi}, event)" 
                                    class="mt-2 w-full text-center text-[11px] font-semibold text-purple-600 hover:text-purple-700 py-1 hover:bg-purple-100 rounded transition">
                                Tandai Dibaca
                            </button>
                        ` : ''}
                    </div>
                `;
                
                return div;
            }

            // Mark notification as read (sidebar)
            window.markAsReadSidebar = function(notifId, event) {
                event.stopPropagation();
                
                fetch('{{ route("notifikasi.markRead") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id_notifikasi: notifId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadNotificationsSidebar(); // Reload notifications
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                });
            }

            // Update notification count badge (sidebar)
            function updateNotificationCountSidebar(count) {
                if (!notificationCountSidebar) return;
                
                if (count > 0) {
                    notificationCountSidebar.textContent = count > 99 ? '99+' : count;
                    notificationCountSidebar.classList.remove('hidden');
                } else {
                    notificationCountSidebar.classList.add('hidden');
                }
            }

            // Format date
            function formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diff = Math.floor((now - date) / 1000); // difference in seconds
                
                if (diff < 60) return 'Baru saja';
                if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
                if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
                if (diff < 604800) return Math.floor(diff / 86400) + ' hari lalu';
                
                return date.toLocaleDateString('id-ID', { 
                    day: 'numeric', 
                    month: 'short', 
                    year: 'numeric' 
                });
            }

            // Load notification count on page load
            function loadNotificationCountSidebar() {
                fetch('{{ route("notifikasi.getUnread") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.notifikasi) {
                        updateNotificationCountSidebar(data.notifikasi.length);
                    }
                })
                .catch(error => {
                    console.error('Error loading notification count:', error);
                });
            }

            // Load count on page load
            document.addEventListener('DOMContentLoaded', function() {
                loadNotificationCountSidebar();
                
                // Refresh count every 30 seconds
                setInterval(loadNotificationCountSidebar, 30000);
            });
        }

        // Auto-refresh CSRF token setiap 30 menit untuk mencegah expired
        setInterval(function() {
            fetch('/refresh-csrf', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update semua CSRF token di halaman
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.token;
                });
                // Update meta tag CSRF
                const metaCsrf = document.querySelector('meta[name="csrf-token"]');
                if (metaCsrf && data.token) {
                    metaCsrf.setAttribute('content', data.token);
                }
                console.log('CSRF token refreshed');
            })
            .catch(error => {
                console.error('Failed to refresh CSRF token:', error);
            });
        }, 30 * 60 * 1000); // 30 menit

        // Handle session expired
        window.addEventListener('beforeunload', function() {
            // Clear any temporary data if needed
        });
    </script>

</body>
</html>
