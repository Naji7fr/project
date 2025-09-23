<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stands Overview | Sneakerness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen bg-white text-black">
    <!-- Navigation -->
    <nav class="bg-white fixed w-full z-50 shadow-lg border-b border-gray-200">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-black tracking-tighter">SNEAKERNESS</a>
            <div class="hidden md:flex space-x-8">
                <a href="/#events" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Events</a>
                <a href="/stands" class="text-black font-medium border-b-2 border-black">Stands</a>
                <a href="/#about" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">About</a>
                <a href="/#contact" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Contact</a>
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="/admin" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Admin</a>
                    <?php else: ?>
                        <a href="/dashboard" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Dashboard</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/login" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Login</a>
                <?php endif; ?>
            </div>
            <button class="md:hidden text-gray-700 hover:text-black">
                <i data-feather="menu"></i>
            </button>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 pt-24 bg-white">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Stands Overview</h1>
            <p class="text-gray-600">Discover all exhibitors and their offerings at Sneakerness</p>
        </div>

        <!-- Error Message Container -->
        <div id="errorContainer" class="mb-6 hidden">
            <div class="bg-red-100 border-l-4 border-red-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i data-feather="alert-circle" class="w-5 h-5 text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Server Error</h3>
                        <p id="errorMessage" class="text-sm text-red-700 mt-1">
                            Stands niet beschikbaar. Er is een serverfout opgetreden.
                        </p>
                        <div class="mt-3">
                            <button onclick="retryLoadStands()" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition-colors">
                                Try Again
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingContainer" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-black"></div>
            <p class="mt-4 text-gray-600">Loading stands...</p>
        </div>

        <!-- Category Filter -->
        <div id="categoryFilter" class="mb-8 hidden">
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter by Category</h3>
                <div class="flex flex-wrap gap-2 md:gap-3">
                    <button onclick="filterByCategory('')" class="category-btn active px-3 md:px-4 py-2 rounded-full bg-black text-white text-xs md:text-sm font-medium transition-colors hover:bg-gray-800">
                        <i data-feather="grid" class="w-3 md:w-4 h-3 md:h-4 inline mr-1"></i>
                        All Categories
                    </button>
                    <!-- Categories will be populated by JavaScript -->
                </div>
                
                <!-- Quick Stats -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-sm text-gray-600">
                        <span>Total Stands: <span id="totalStands" class="font-medium text-gray-900">-</span></span>
                        <span>Showing: <span id="showingStands" class="font-medium text-gray-900">-</span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stands Grid -->
        <div id="standsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
            <!-- Stands will be populated by JavaScript -->
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="text-center py-12 hidden">
            <i data-feather="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No stands available</h3>
            <p class="text-gray-600">There are currently no stands to display.</p>
        </div>

        <!-- Test Error Button (for demo purposes) -->
        <div class="mt-8 text-center">
            <button onclick="simulateError()" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition-colors">
                Simulate Server Error (Demo)
            </button>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Simple mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.md\\:hidden button');
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    alert('Mobile menu clicked - add functionality if needed');
                });
            }
        });

        let currentCategory = '';
        let allStands = [];

        // Load stands when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadStands();
        });

        async function loadStands(simulateError = false) {
            showLoading();
            hideError();
            
            try {
                const url = simulateError ? '/api/stands?simulate_error=true' : '/api/stands';
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const data = await response.json();
                
                // Check if response contains error
                if (data.error) {
                    throw new Error(data.message || data.error);
                }
                
                if (Array.isArray(data) && data.length > 0) {
                    allStands = data;
                    displayStands(data);
                    loadCategories(data);
                    updateStats(data.length, data.length);
                    showContent();
                } else {
                    showEmptyState();
                }
                
            } catch (error) {
                console.error('Error loading stands:', error);
                showError(error.message || 'Stands niet beschikbaar');
            }
        }

        function showLoading() {
            document.getElementById('loadingContainer').classList.remove('hidden');
            document.getElementById('standsGrid').classList.add('hidden');
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('categoryFilter').classList.add('hidden');
        }

        function showContent() {
            document.getElementById('loadingContainer').classList.add('hidden');
            document.getElementById('standsGrid').classList.remove('hidden');
            document.getElementById('categoryFilter').classList.remove('hidden');
        }

        function showEmptyState() {
            document.getElementById('loadingContainer').classList.add('hidden');
            document.getElementById('standsGrid').classList.add('hidden');
            document.getElementById('emptyState').classList.remove('hidden');
            feather.replace();
        }

        function showError(message) {
            document.getElementById('loadingContainer').classList.add('hidden');
            document.getElementById('standsGrid').classList.add('hidden');
            document.getElementById('categoryFilter').classList.add('hidden');
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorContainer').classList.remove('hidden');
            feather.replace();
        }

        function hideError() {
            document.getElementById('errorContainer').classList.add('hidden');
        }

        function displayStands(stands) {
            const grid = document.getElementById('standsGrid');
            
            const standsHTML = stands.map(stand => `
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <img src="${stand.logo_url}" alt="${stand.company}" class="w-12 h-12 object-contain mr-4" onerror="this.src='https://via.placeholder.com/48x48/E5E7EB/9CA3AF?text=${stand.company.charAt(0)}'">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">${stand.name}</h3>
                                <p class="text-sm text-gray-600">${stand.company}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full mb-2">
                                ${stand.category}
                            </span>
                            <p class="text-gray-700 text-sm line-clamp-3">${stand.description}</p>
                        </div>
                        
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i data-feather="map-pin" class="w-4 h-4 mr-2"></i>
                                <span>${stand.location} - Booth ${stand.booth_number}</span>
                            </div>
                            <div class="flex items-center">
                                <i data-feather="mail" class="w-4 h-4 mr-2"></i>
                                <a href="mailto:${stand.contact_email}" class="text-blue-600 hover:underline">${stand.contact_email}</a>
                            </div>
                            <div class="flex items-center">
                                <i data-feather="phone" class="w-4 h-4 mr-2"></i>
                                <span>${stand.contact_phone}</span>
                            </div>
                            ${stand.website ? `
                                <div class="flex items-center">
                                    <i data-feather="globe" class="w-4 h-4 mr-2"></i>
                                    <a href="${stand.website}" target="_blank" class="text-blue-600 hover:underline">Visit Website</a>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
            
            grid.innerHTML = standsHTML;
            feather.replace();
        }

        function loadCategories(stands) {
            const categories = [...new Set(stands.map(stand => stand.category))].sort();
            const filterContainer = document.querySelector('#categoryFilter .flex');
            
            // Category icons mapping
            const categoryIcons = {
                'Basketball Shoes': 'target',
                'Lifestyle Sneakers': 'trending-up',
                'Running Shoes': 'zap',
                'Canvas Sneakers': 'layers',
                'Skate Shoes': 'activity',
                'Authentication': 'shield-check',
                'Customization': 'edit-3',
                'Vintage': 'clock',
                'Maintenance': 'tool',
                'Premium': 'star',
                'Trading': 'repeat'
            };
            
            // Keep the "All Categories" button and add category buttons
            const categoryButtons = categories.map(category => {
                const icon = categoryIcons[category] || 'tag';
                return `
                    <button onclick="filterByCategory('${category}')" class="category-btn px-3 md:px-4 py-2 rounded-full bg-gray-200 text-gray-700 text-xs md:text-sm font-medium hover:bg-gray-300 transition-colors">
                        <i data-feather="${icon}" class="w-3 md:w-4 h-3 md:h-4 inline mr-1"></i>
                        <span class="hidden sm:inline">${category}</span>
                        <span class="sm:hidden">${category.split(' ')[0]}</span>
                    </button>
                `;
            }).join('');
            
            // Add category buttons after the "All Categories" button
            filterContainer.innerHTML = filterContainer.innerHTML + categoryButtons;
            feather.replace();
        }

        function filterByCategory(category) {
            currentCategory = category;
            
            // Update button states
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-black', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            event.target.classList.add('active', 'bg-black', 'text-white');
            event.target.classList.remove('bg-gray-200', 'text-gray-700');
            
            // Filter stands locally instead of reloading
            let filteredStands = allStands;
            if (category) {
                filteredStands = allStands.filter(stand => stand.category === category);
            }
            
            displayStands(filteredStands);
            updateStats(allStands.length, filteredStands.length);
        }

        function updateStats(total, showing) {
            document.getElementById('totalStands').textContent = total;
            document.getElementById('showingStands').textContent = showing;
        }

        function retryLoadStands() {
            loadStands();
        }

        function simulateError() {
            loadStands(true);
        }
    </script>
</body>
</html>
