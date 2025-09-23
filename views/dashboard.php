<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Sneakerness</title>
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
                <a href="/stands" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Stands</a>
                <a href="/#about" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">About</a>
                <a href="/#contact" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Contact</a>
                <a href="/dashboard" class="text-black font-medium border-b-2 border-black">Dashboard</a>
                <button onclick="logout()" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">
                    <i data-feather="log-out" class="w-4 h-4 inline mr-1"></i>
                    Logout
                </button>
            </div>
            <button class="md:hidden text-gray-700 hover:text-black">
                <i data-feather="menu"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 pt-24 bg-white">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">User Dashboard</h1>
            <p class="text-gray-600">Welcome to your personal sneaker events dashboard</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <div class="flex items-center">
                    <div class="p-2 md:p-3 rounded-full bg-blue-100 mr-3 md:mr-4">
                        <i data-feather="calendar" class="w-5 md:w-6 h-5 md:h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-medium text-gray-600">Upcoming Events</p>
                        <p id="upcomingCount" class="text-xl md:text-2xl font-bold text-gray-900">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <div class="flex items-center">
                    <div class="p-2 md:p-3 rounded-full bg-green-100 mr-3 md:mr-4">
                        <i data-feather="check-circle" class="w-5 md:w-6 h-5 md:h-6 text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-medium text-gray-600">Past Events</p>
                        <p id="pastCount" class="text-xl md:text-2xl font-bold text-gray-900">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6 sm:col-span-2 lg:col-span-1">
                <div class="flex items-center">
                    <div class="p-2 md:p-3 rounded-full bg-purple-100 mr-3 md:mr-4">
                        <i data-feather="map-pin" class="w-5 md:w-6 h-5 md:h-6 text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm font-medium text-gray-600">Cities</p>
                        <p id="citiesCount" class="text-xl md:text-2xl font-bold text-gray-900">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Upcoming Events</h2>
            </div>
            <div class="p-6">
                <div id="upcomingEvents" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Events will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Recent Events -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Recent Events</h2>
            </div>
            <div class="p-6">
                <div id="pastEvents" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Events will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Simple logout function
        async function logout() {
            if (!confirm('Are you sure you want to logout?')) {
                return;
            }
            
            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
                
                if (response.ok) {
                    window.location.href = '/login';
                }
            } catch (error) {
                console.error('Logout error:', error);
            }
        }

        // Load dashboard data
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
        });

        async function loadDashboardData() {
            try {
                // Load all events
                const response = await fetch('/api/events');
                const events = await response.json();
                
                if (events && Array.isArray(events)) {
                    const upcomingEvents = events.filter(event => event.status === 'upcoming');
                    const pastEvents = events.filter(event => event.status === 'past');
                    const cities = [...new Set(events.map(event => event.city))];
                    
                    // Update stats
                    document.getElementById('upcomingCount').textContent = upcomingEvents.length;
                    document.getElementById('pastCount').textContent = pastEvents.length;
                    document.getElementById('citiesCount').textContent = cities.length;
                    
                    // Display events
                    displayEvents(upcomingEvents, 'upcomingEvents');
                    displayEvents(pastEvents.slice(0, 6), 'pastEvents'); // Show only 6 recent
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        function displayEvents(events, containerId) {
            const container = document.getElementById(containerId);
            
            if (events.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center col-span-full">No events found.</p>';
                return;
            }
            
            const eventsHTML = events.map(event => `
                <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="relative rounded-lg overflow-hidden mb-3 h-32">
                        <img src="${event.image_url}" alt="${event.title}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-lg mb-1">${event.title}</h3>
                    <p class="text-gray-600 text-sm mb-1">${event.date}</p>
                    <p class="text-gray-500 text-sm mb-2">${event.city}</p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-black">${event.price}</span>
                        <span class="text-xs px-2 py-1 rounded-full ${
                            event.status === 'upcoming' 
                                ? 'bg-green-100 text-green-800' 
                                : 'bg-gray-100 text-gray-800'
                        }">
                            ${event.status === 'upcoming' ? 'Upcoming' : 'Past'}
                        </span>
                    </div>
                </div>
            `).join('');
            
            container.innerHTML = eventsHTML;
        }

        // Logout function
        async function logout() {
            if (!confirm('Are you sure you want to logout?')) {
                return;
            }
            
            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
                
                if (response.ok) {
                    window.location.href = '/login';
                }
            } catch (error) {
                console.error('Logout error:', error);
            }
        }
    </script>
</body>
</html>
