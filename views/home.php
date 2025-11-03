<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneakerness - Home</title>
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
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
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
                <a href="/" class="text-black font-bold transition-colors duration-300">Home</a>
                <a href="/events" class="text-gray-700 hover:text-black transition-colors duration-300 font-medium">Events</a>
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

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 overflow-hidden gradient-bg">
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 tracking-tight">
                <span class="text-black">SNEAKERNESS</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-600 max-w-3xl mx-auto">
                Welcome to the world's premier sneaker convention platform. Discover events, explore stands, and connect with the global sneaker community.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                <a href="/events" class="inline-block bg-black text-white px-8 py-4 rounded-full font-bold hover:bg-gray-900 transition-all duration-300 transform hover:scale-105">
                    Browse Events
                </a>
                <a href="/stands" class="inline-block border-2 border-black text-black px-8 py-4 rounded-full font-bold hover:bg-black hover:text-white transition-all duration-300">
                    Explore Stands
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200" data-aos="fade-up">
                    <div class="text-black mb-4">
                        <i data-feather="calendar" class="w-12 h-12 mx-auto"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Global Events</h3>
                    <p class="text-gray-600">Discover sneaker conventions worldwide with exclusive drops and rare finds.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-black mb-4">
                        <i data-feather="shopping-bag" class="w-12 h-12 mx-auto"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Premium Stands</h3>
                    <p class="text-gray-600">Connect with top exhibitors and discover the latest in sneaker culture.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-black mb-4">
                        <i data-feather="users" class="w-12 h-12 mx-auto"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Community</h3>
                    <p class="text-gray-600">Join thousands of sneaker enthusiasts and collectors from around the world.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Events Preview -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4 text-black">Featured Events</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Don't miss out on the hottest sneaker events happening around the world</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" data-aos="fade-up">
                    <div class="h-48 bg-gradient-to-r from-gray-200 to-gray-300"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Berlin Convention</h3>
                        <p class="text-gray-600 mb-3">November 15-16, 2024</p>
                        <p class="text-gray-700 text-sm">The biggest sneaker event in Germany with exclusive releases and special guests.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="h-48 bg-gradient-to-r from-gray-300 to-gray-400"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Amsterdam Expo</h3>
                        <p class="text-gray-600 mb-3">December 8-9, 2024</p>
                        <p class="text-gray-700 text-sm">Premium sneaker exhibition featuring rare collectibles and limited editions.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                    <div class="h-48 bg-gradient-to-r from-gray-400 to-gray-500"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Paris Summit</h3>
                        <p class="text-gray-600 mb-3">January 20-21, 2025</p>
                        <p class="text-gray-700 text-sm">Exclusive French sneaker culture event with top-tier brands and artists.</p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="/events" class="inline-block bg-black text-white px-8 py-3 rounded-full font-bold hover:bg-gray-900 transition-all duration-300 transform hover:scale-105">
                    View All Events
                </a>
            </div>
        </div>
    </section>

    <!-- Stay Updated Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8 text-black">
                STAY UPDATED
            </h2>
            <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl border border-gray-200 shadow-xl">
                <div class="flex flex-col md:flex-row gap-4">
                    <input 
                        type="email" 
                        placeholder="Enter your email" 
                        class="flex-1 px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black text-black placeholder-gray-500"
                    >
                    <button 
                        class="bg-black text-white py-3 px-8 rounded-lg font-bold hover:bg-gray-900 transition transform hover:scale-105"
                    >
                        Subscribe
                    </button>
                </div>
                <p class="text-center text-gray-500 mt-4 text-sm">
                    Get exclusive access to early ticket sales and special drops
                </p>
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


    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Initialize Feather Icons
        feather.replace();
    </script>
</body>
</html>
