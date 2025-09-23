<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Events Overview | Sneakerness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="/" class="text-2xl font-bold text-black tracking-tighter">SNEAKERNESS</a>
                <span class="text-gray-500">|</span>
                <span class="text-gray-700 font-medium">Admin Panel</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="/" class="text-gray-600 hover:text-black transition-colors">
                    <i data-feather="home" class="w-5 h-5"></i>
                </a>
                <span class="text-gray-400">Admin</span>
                <button onclick="logout()" class="text-gray-600 hover:text-black transition-colors">
                    <i data-feather="log-out" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Events Overview</h1>
            <p class="text-gray-600">Manage all sneaker events</p>
        </div>

        <!-- Success/Error Messages -->
        <div id="messageContainer" class="mb-6"></div>

        <!-- Add Event Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Add New Event</h2>
            <form id="addEventForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" id="title" name="title" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                    <input type="text" id="city" name="city" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="text" id="date" name="date" required placeholder="e.g. November 12-13, 2025"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                    <input type="text" id="price" name="price" required placeholder="e.g. €25"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                    <input type="text" id="location" name="location" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image URL *</label>
                    <input type="url" id="image_url" name="image_url" required placeholder="https://..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea id="description" name="description" required rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        <option value="upcoming">Upcoming</option>
                        <option value="past">Past</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-900 transition-colors font-medium">
                        Add Event
                    </button>
                </div>
            </form>
        </div>

        <!-- Events List -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">All Events</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="eventsTable" class="bg-white divide-y divide-gray-200">
                        <!-- Events will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
    <script>
        // Initialize Feather Icons
        feather.replace();
    </script>
</body>
</html>
