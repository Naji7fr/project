<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Sneakerness</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        black: "#000",
                        white: "#fff",
                        gray: {
                            50: "#f9fafb",
                            100: "#f3f4f6",
                            200: "#e5e7eb",
                            300: "#d1d5db",
                            400: "#9ca3af",
                            500: "#6b7280",
                            600: "#4b5563",
                            700: "#374151",
                            800: "#1f2937",
                            900: "#111827"
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="api.js"></script>
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
                <a href="/" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Home</a>
                <a href="/events" class="text-black font-bold transition-colors duration-300">Events</a>
                <a href="/stands" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Stands</a>
                <a href="#about" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">About</a>
                <a href="#contact" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Contact</a>
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

    <!-- Events Hero Section -->
    <section class="relative pt-32 pb-16 overflow-hidden bg-white">
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 tracking-tight">
                <span class="text-black">SNEAKER EVENTS</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-600 max-w-2xl mx-auto">
                Discover upcoming sneaker conventions and past events worldwide
            </p>
        </div>
    </section>

    <!-- Events Section -->
    <section id="events" class="py-16 container mx-auto px-4 relative bg-white">
        <!-- Tabs -->
        <div class="flex justify-center mb-12">
            <div class="bg-white p-1 rounded-full border border-gray-200 inline-flex">
                <button class="tab-btn active px-6 py-2 font-medium rounded-full bg-black text-white" data-tab="upcoming">Upcoming</button>
                <button class="tab-btn px-6 py-2 font-medium text-gray-700 rounded-full hover:text-black transition" data-tab="past">Past Events</button>
            </div>
        </div>
        <!-- Upcoming Events Content -->
        <div id="upcoming" class="tab-content active">
            <div class="text-center py-12">
                <p class="text-gray-500">Loading events...</p>
            </div>
        </div>
        <!-- Past Events Content -->
        <div id="past" class="tab-content">
            <div class="text-center py-12">
                <p class="text-gray-500">Loading past events...</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black border-t border-gray-200 py-12 text-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <div class="mb-6 md:mb-0">
                    <h3 class="text-2xl font-bold text-white tracking-tighter">SNEAKERNESS</h3>
                    <p class="text-gray-400">The ultimate sneaker convention</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <i data-feather="instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <i data-feather="twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <i data-feather="facebook"></i>
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="font-bold text-white mb-4">Events</h4>
                    <ul class="space-y-2">
                        <li><a href="/events" class="text-gray-400 hover:text-white transition">Upcoming</a></li>
                        <li><a href="/events" class="text-gray-400 hover:text-white transition">Past Events</a></li>
                        <li><a href="/stands" class="text-gray-400 hover:text-white transition">Exhibitors</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4">About</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Our Story</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Team</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Partners</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">FAQs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4">Join Us</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Exhibit</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Sponsor</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Volunteer</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-8 text-center text-gray-400">
                <p>&copy; 2023 Sneakerness. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Event Detail Modal -->
    <div id="eventModal" class="modal fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 p-4" style="display: none;">
        <div class="bg-white rounded-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto border border-gray-200 relative text-black">
            <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-black z-50">
                <i data-feather="x" class="w-6 h-6"></i>
            </button>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 id="modalTitle" class="text-2xl font-bold">Berlin</h3>
                </div>
                <div class="relative rounded-xl overflow-hidden mb-6 h-48">
                    <img id="modalImage" src="http://static.photos/retail/1200x630/1" alt="Event Image" class="w-full h-full object-cover">
                </div>
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="font-bold mb-2 text-black">Date & Time</h4>
                        <p id="modalDate" class="text-gray-700">November 12-13, 2023</p>
                        <p class="text-gray-700">10:00 AM - 7:00 PM</p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-2 text-black">Location</h4>
                        <p id="modalLocation" class="text-gray-700">Station Berlin, Luckenwalder Str. 4-6, 10963 Berlin</p>
                    </div>
                </div>
                <div class="mb-6">
                    <h4 class="font-bold mb-2 text-black">Description</h4>
                    <p id="modalDescription" class="text-gray-700">
                        The biggest sneaker event in Germany returns to Berlin with exclusive drops and special guests. 
                        Featuring over 150 exhibitors, live customization stations, panel discussions with industry leaders, 
                        and rare sneaker auctions. Don't miss the chance to buy, sell, or trade with fellow sneaker enthusiasts.
                    </p>
                </div>
                <div class="mb-6">
                    <h4 class="font-bold mb-2 text-black">Ticket Options</h4>
                    <div class="space-y-4">
                        <div id="ticketSection">
                            <!-- Ticket options will be populated by JavaScript based on event status -->
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold mb-2 text-black">Highlights</h4>
                    <ul class="list-disc pl-5 text-gray-700 space-y-1">
                        <li>Exclusive sneaker releases</li>
                        <li>Live customization by renowned artists</li>
                        <li>Panel discussions with industry leaders</li>
                        <li>Sneaker authentication service</li>
                        <li>Raffles and giveaways</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Initialize Feather Icons
        feather.replace();

        // Tab functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                tabBtns.forEach(btn => {
                    btn.classList.remove('active', 'bg-black', 'text-white');
                    btn.classList.add('text-gray-700');
                });
                tabContents.forEach(content => content.classList.remove('active'));
                btn.classList.add('active', 'bg-black', 'text-white');
                btn.classList.remove('text-gray-700');
                const tabId = btn.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Modal functionality is handled in api.js
    </script>
</body>
</html>
