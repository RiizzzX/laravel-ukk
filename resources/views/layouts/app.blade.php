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
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
        
        /* Badge animation */
        @keyframes bounce-subtle {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        #notificationCountSidebar {
            animation: bounce-subtle 2s infinite;
        }
        
        /* Slide in animation for notifications */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .notification-item {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Hover effect for notification items */
        .notification-item:hover {
            transform: translateX(2px);
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
        <header class="bg-white shadow px-4 sm:px-6 py-3 sm:py-4 flex items-center flex-shrink-0 z-20">
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

        // ========== NOTIFICATION DROPDOWN SYSTEM (SIDEBAR) ==========
        const notificationBellSidebar = document.getElementById('notificationBellSidebar');
        const notificationDropdownSidebar = document.getElementById('notificationDropdownSidebar');
        const notificationListSidebar = document.getElementById('notificationListSidebar');
        const notificationBadgeSidebar = document.getElementById('notificationBadgeSidebar');
        const loadingNotificationsSidebar = document.getElementById('loadingNotificationsSidebar');
        const emptyNotificationsSidebar = document.getElementById('emptyNotificationsSidebar');
        const notificationChevron = document.getElementById('notificationChevron');

        if (notificationBellSidebar) {
            let isDropdownOpen = false;
            let isLoadingNotifications = false;
            let checkInterval = null; // Store interval ID

            // Toggle dropdown
            notificationBellSidebar.addEventListener('click', function(e) {
                e.stopPropagation();
                isDropdownOpen = !isDropdownOpen;
                
                if (isDropdownOpen) {
                    notificationDropdownSidebar.classList.remove('hidden');
                    notificationChevron.style.transform = 'rotate(180deg)';
                    
                    // Stop auto-refresh while dropdown is open
                    if (checkInterval) {
                        clearInterval(checkInterval);
                        checkInterval = null;
                    }
                    
                    loadNotifications();
                    // Mark as read akan dipanggil setelah notifikasi berhasil di-load
                } else {
                    notificationDropdownSidebar.classList.add('hidden');
                    notificationChevron.style.transform = 'rotate(0deg)';
                    
                    // Resume auto-refresh when dropdown is closed
                    if (!checkInterval) {
                        checkInterval = setInterval(checkUnreadNotifications, 30000);
                    }
                }
            });

            // Load notifications
            function loadNotifications() {
                if (isLoadingNotifications) return;
                
                isLoadingNotifications = true;
                loadingNotificationsSidebar.classList.remove('hidden');
                emptyNotificationsSidebar.classList.add('hidden');
                notificationListSidebar.classList.add('hidden');

                fetch('{{ route("notifikasi.getUnread") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    isLoadingNotifications = false;
                    loadingNotificationsSidebar.classList.add('hidden');
                    
                    if (data.notifikasi && data.notifikasi.length > 0) {
                        emptyNotificationsSidebar.classList.add('hidden');
                        notificationListSidebar.classList.remove('hidden');
                        notificationListSidebar.innerHTML = '';
                        
                        // Display max 10 notifications
                        data.notifikasi.slice(0, 10).forEach(notif => {
                            notificationListSidebar.appendChild(createNotificationElement(notif));
                        });
                        
                        // Mark all as read AFTER successfully loading
                        markAllNotificationsAsRead();
                    } else {
                        notificationListSidebar.classList.add('hidden');
                        emptyNotificationsSidebar.classList.remove('hidden');
                        // No notifications, so badge should be 0
                        updateBadgeCount(0);
                    }
                })
                .catch(error => {
                    isLoadingNotifications = false;
                    console.error('Error loading notifications:', error);
                    loadingNotificationsSidebar.classList.add('hidden');
                    emptyNotificationsSidebar.classList.remove('hidden');
                });
            }

            // Create notification element
            function createNotificationElement(notif) {
                const div = document.createElement('div');
                div.className = 'p-3 hover:bg-purple-50 transition-colors cursor-pointer' + (!notif.is_read ? ' bg-purple-25' : '');
                
                div.onclick = function() {
                    if (notif.link) {
                        window.location.href = notif.link;
                    }
                };
                
                // Determine icon and color
                let iconColor = 'text-purple-600';
                let iconPath = 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z';
                
                if (notif.judul.includes('✅') || notif.judul.includes('Disetujui') || notif.judul.includes('Diterima')) {
                    iconColor = 'text-green-600';
                    iconPath = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
                } else if (notif.judul.includes('❌') || notif.judul.includes('Ditolak')) {
                    iconColor = 'text-red-600';
                    iconPath = 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';
                } else if (notif.judul.includes('📋') || notif.judul.includes('Tersedia')) {
                    iconColor = 'text-blue-600';
                    iconPath = 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2';
                }
                
                div.innerHTML = `
                    <div class="flex gap-2">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-900 mb-1 line-clamp-1">${notif.judul}</p>
                            <p class="text-xs text-gray-600 mb-1 line-clamp-2">${notif.isi}</p>
                            <p class="text-xs text-gray-400">${formatTimeAgo(notif.created_at)}</p>
                        </div>
                        ${!notif.is_read ? '<span class="flex-shrink-0 w-2 h-2 bg-purple-600 rounded-full mt-1"></span>' : ''}
                    </div>
                `;
                
                return div;
            }

            // Format time ago
            function formatTimeAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const seconds = Math.floor((now - date) / 1000);
                
                if (seconds < 60) return 'Baru saja';
                if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
                if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
                if (seconds < 604800) return Math.floor(seconds / 86400) + ' hari lalu';
                
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            }

            // Mark all notifications as read
            function markAllNotificationsAsRead() {
                fetch('{{ route("notifikasi.markRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({}) // Tidak perlu parameter, backend auto mark all unread
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Langsung update badge ke 0
                        updateBadgeCount(0);
                        console.log('All notifications marked as read');
                    }
                })
                .catch(error => {
                    console.error('Error marking notifications as read:', error);
                });
            }

            // Update badge count
            function updateBadgeCount(count) {
                if (count > 0) {
                    notificationBadgeSidebar.textContent = count > 99 ? '99+' : count;
                    notificationBadgeSidebar.classList.remove('hidden');
                } else {
                    notificationBadgeSidebar.classList.add('hidden');
                }
            }

            // Check for unread notifications periodically
            function checkUnreadNotifications() {
                fetch('{{ route("notifikasi.getUnread") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const count = data.totalUnread || (data.notifikasi ? data.notifikasi.length : 0);
                    updateBadgeCount(count);
                })
                .catch(error => console.error('Error checking notifications:', error));
            }

            // Initial check and periodic updates
            document.addEventListener('DOMContentLoaded', function() {
                checkUnreadNotifications();
                // Check every 30 seconds (store interval ID)
                checkInterval = setInterval(checkUnreadNotifications, 30000);
            });
        }

        // Auto logout after 30 minutes of inactivity
        let inactivityTimer;
        
        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(function() {
                alert('Sesi Anda telah berakhir karena tidak ada aktifitas. Silakan login kembali.');
                // Redirect ke landing page untuk logout
                window.location.href = '/';
            }, 30 * 60 * 1000); // 30 menit
        }

        // Reset timer on user activity
        document.addEventListener('mousemove', resetInactivityTimer);
        document.addEventListener('keypress', resetInactivityTimer);
        document.addEventListener('click', resetInactivityTimer);
        document.addEventListener('scroll', resetInactivityTimer);
        
        // Initialize timer
        resetInactivityTimer();
    </script>

</body>
</html>
