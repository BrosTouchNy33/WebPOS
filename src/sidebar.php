<!-- Sidebar component -->
<div id="sidebar" class="min-h-screen fixed flex flex-col top-0 left-0 w-64 dark:bg-gray-800 h-full border-r shadow-lg z-50 transition-all duration-300 ease-in-out md:translate-x-0">
    <div class="flex items-center justify-between h-16 border-b dark:border-gray-700 px-6">
        <div class="font-medium text-gray-800 dark:text-white text-lg">Admin Panel</div>
        <!-- Close button for mobile -->
        <button id="sidebar-close" class="md:hidden text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <div class="overflow-y-auto overflow-x-hidden flex-grow">
        <ul class="flex flex-col py-4 space-y-1">
            <li class="px-5">
                <div class="flex flex-row items-center h-8">
                    <div class="text-sm font-medium tracking-wide text-gray-500 dark:text-gray-400 uppercase">Menu</div>
                </div>
            </li>
            <li>
                <a href="dashboard.php" class="relative flex flex-row items-center h-11 px-6 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white border-l-4 border-transparent hover:border-indigo-500 transition-colors duration-200">
                    <span class="inline-flex justify-center items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </span>
                    <span class="ml-2 text-sm tracking-wide truncate">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="add_category.php" class="relative flex flex-row items-center h-11 px-6 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white border-l-4 border-transparent hover:border-indigo-500 transition-colors duration-200">
                    <span class="inline-flex justify-center items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </span>
                    <span class="ml-2 text-sm tracking-wide truncate">Add Category</span>
                </a>
            </li>
            <li>
                <a href="order_product.php" class="relative flex flex-row items-center h-11 px-6 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white border-l-4 border-transparent hover:border-indigo-500 transition-colors duration-200">
                    <span class="inline-flex justify-center items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </span>
                    <span class="ml-2 text-sm tracking-wide truncate">Make Order</span>
                </a>
            </li>
            <li>
                <a href="add_product.php" class="relative flex flex-row items-center h-11 px-6 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white border-l-4 border-transparent hover:border-indigo-500 transition-colors duration-200">
                    <span class="inline-flex justify-center items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                    <span class="ml-2 text-sm tracking-wide truncate">Add Product</span>
                </a>
            </li>
            <li>
                <a href="view_product.php" class="relative flex flex-row items-center h-11 px-6 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white border-l-4 border-transparent hover:border-indigo-500 transition-colors duration-200">
                    <span class="inline-flex justify-center items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </span>
                    <span class="ml-2 text-sm tracking-wide truncate">View Products</span>
                </a>
            </li>
            <!-- Theme Toggle Button -->
            <li>
                <button id="theme-toggle" class="relative w-full flex flex-row items-center h-11 px-6 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white border-l-4 border-transparent transition-colors duration-200">
        <span class="inline-flex justify-center items-center">
            <!-- Light Mode Icon -->
            <svg id="theme-toggle-light-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <!-- Dark Mode Icon -->
            <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
        </span>
                    <span class="ml-2 text-sm tracking-wide truncate">Toggle Theme</span>
                </button>
            </li>
            <li class="px-5 pt-2">
                <div class="flex flex-row items-center h-8">
                    <div class="text-sm font-medium tracking-wide text-gray-500 dark:text-gray-400 uppercase">Account</div>
                </div>
            </li>
            <li>
                <button id="logout-btn" class="relative w-full flex flex-row items-center h-11 px-6 focus:outline-none hover:bg-red-50 dark:hover:bg-red-900/30 text-gray-600 dark:text-gray-200 hover:text-red-700 dark:hover:text-red-400 border-l-4 border-transparent hover:border-red-500 transition-colors duration-200">
                    <span class="inline-flex justify-center items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </span>
                    <span class="ml-2 text-sm tracking-wide truncate">Logout</span>
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- Hamburger menu button for mobile -->
<button id="menu-toggle" class="md:hidden fixed top-4 left-4 z-40 p-2 rounded-md bg-white dark:bg-gray-800 shadow-md text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white focus:outline-none">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<!-- Backdrop for mobile -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 hidden md:hidden"></div>

<!-- Logout Confirmation Modal -->
<div id="logout-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full mx-4 overflow-hidden shadow-xl transform transition-all">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirm Logout</h3>
                <button id="modal-close" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to logout? Any unsaved changes will be lost.</p>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button id="cancel-logout" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none">
                    Cancel
                </button>
                <a href="../auth/logout.php" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none">
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Script for sidebar toggle and modal functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Elements
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menu-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const backdrop = document.getElementById('sidebar-backdrop');
        const logoutBtn = document.getElementById('logout-btn');
        const logoutModal = document.getElementById('logout-modal');
        const modalClose = document.getElementById('modal-close');
        const cancelLogout = document.getElementById('cancel-logout');

        // Initial state - hide sidebar on mobile
        if (window.innerWidth < 768) {
            sidebar.classList.add('-translate-x-full');
        }

        // Theme detection (optional)
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }

        // Toggle sidebar on mobile
        menuToggle?.addEventListener('click', function() {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden', 'md:overflow-auto'); // Prevent scrolling when sidebar is open
        });

        // Close sidebar on mobile
        sidebarClose?.addEventListener('click', closeSidebar);
        backdrop?.addEventListener('click', closeSidebar);

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden', 'md:overflow-auto');
        }

        // Logout modal functionality
        logoutBtn?.addEventListener('click', function() {
            logoutModal.classList.remove('hidden');
        });

        // Close modal
        modalClose?.addEventListener('click', closeModal);
        cancelLogout?.addEventListener('click', closeModal);

        function closeModal() {
            logoutModal.classList.add('hidden');
        }

        // Close modal when clicking outside
        logoutModal?.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                closeModal();
            }
        });

        // Keyboard accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!logoutModal.classList.contains('hidden')) {
                    closeModal();
                } else if (window.innerWidth < 768 && !sidebar.classList.contains('-translate-x-full')) {
                    closeSidebar();
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            } else if (!backdrop.classList.contains('hidden')) {
                // Keep sidebar open on resize if it was already open
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');

        if (themeToggleBtn) {
            // Check for saved theme preference
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                themeToggleLightIcon.classList.add('hidden');
                themeToggleDarkIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                themeToggleDarkIcon.classList.add('hidden');
                themeToggleLightIcon.classList.remove('hidden');
            }

            // Toggle button click handler
            themeToggleBtn.addEventListener('click', function() {
                // Toggle icons
                themeToggleLightIcon.classList.toggle('hidden');
                themeToggleDarkIcon.classList.toggle('hidden');

                // Toggle theme
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            });
        }
    });


</script>