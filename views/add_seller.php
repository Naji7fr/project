<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verkoper Toevoegen | Sneakerness</title>
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
                <a href="/admin" class="text-gray-600 hover:text-black transition-colors">
                    <i data-feather="calendar" class="w-5 h-5"></i>
                </a>
                <a href="/sellers" class="text-gray-600 hover:text-black transition-colors">
                    <i data-feather="users" class="w-5 h-5"></i>
                </a>
                <span class="text-black font-medium">Verkoper Toevoegen</span>
                <button onclick="logout()" class="text-gray-600 hover:text-black transition-colors">
                    <i data-feather="log-out" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="/sellers" class="text-gray-600 hover:text-black transition-colors mr-4">
                    <i data-feather="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Verkoper Toevoegen</h1>
            </div>
            <p class="text-gray-600">Voeg een nieuwe verkoper toe aan het systeem</p>
        </div>

        <!-- Success/Error Messages -->
        <div id="messageContainer" class="mb-6"></div>

        <!-- Add Seller Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form id="addSellerForm" class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Basisgegevens</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Naam *
                            </label>
                            <input type="text" id="name" name="name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="Voer de naam in">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                E-mailadres *
                            </label>
                            <input type="email" id="email" name="email" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="verkoper@example.com">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Telefoonnummer
                            </label>
                            <input type="tel" id="phone" name="phone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="+31 6 12345678">
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-2">
                                Bedrijfsnaam
                            </label>
                            <input type="text" id="company" name="company" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="Bedrijfsnaam">
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Adresgegevens</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Adres
                            </label>
                            <input type="text" id="address" name="address" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="Straatnaam en huisnummer">
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                Stad
                            </label>
                            <input type="text" id="city" name="city" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="Amsterdam">
                        </div>
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Postcode
                            </label>
                            <input type="text" id="postal_code" name="postal_code" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="1234 AB">
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Land
                            </label>
                            <select id="country" name="country" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black">
                                <option value="">Selecteer land</option>
                                <option value="Nederland" selected>Nederland</option>
                                <option value="België">België</option>
                                <option value="Duitsland">Duitsland</option>
                                <option value="Frankrijk">Frankrijk</option>
                                <option value="Verenigd Koninkrijk">Verenigd Koninkrijk</option>
                                <option value="Spanje">Spanje</option>
                                <option value="Italië">Italië</option>
                                <option value="Overig">Overig</option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select id="status" name="status" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-black">
                                <option value="active" selected>Actief</option>
                                <option value="inactive">Inactief</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="/sellers" class="px-6 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Annuleren
                    </a>
                    <button type="submit" id="submitBtn" 
                            class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">Verkoper Toevoegen</span>
                        <span id="submitLoader" class="hidden">
                            <i data-feather="loader" class="w-4 h-4 inline animate-spin mr-2"></i>
                            Toevoegen...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
            
            // Form submission
            document.getElementById('addSellerForm').addEventListener('submit', handleSubmit);
        });

        // Handle form submission
        async function handleSubmit(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoader = document.getElementById('submitLoader');
            
            // Disable form and show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoader.classList.remove('hidden');
            
            try {
                // Collect form data
                const formData = new FormData(e.target);
                const data = Object.fromEntries(formData.entries());
                
                // Remove empty values
                Object.keys(data).forEach(key => {
                    if (data[key] === '') {
                        delete data[key];
                    }
                });
                
                const response = await fetch('/api/sellers', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showMessage(result.message, 'success');
                    // Reset form
                    e.target.reset();
                    // Redirect to sellers overview after short delay
                    setTimeout(() => {
                        window.location.href = '/sellers';
                    }, 2000);
                } else {
                    if (result.errors) {
                        // Show validation errors
                        showMessage(result.errors.join('<br>'), 'error');
                    } else {
                        showMessage(result.message || 'Fout bij het toevoegen van verkoper', 'error');
                    }
                }
            } catch (error) {
                console.error('Error adding seller:', error);
                showMessage('Er is een fout opgetreden. Probeer het opnieuw.', 'error');
            } finally {
                // Re-enable form
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoader.classList.add('hidden');
                feather.replace();
            }
        }

        // Logout function
        async function logout() {
            try {
                await fetch('/api/logout', { method: 'POST' });
                window.location.href = '/login';
            } catch (error) {
                console.error('Logout error:', error);
                window.location.href = '/login';
            }
        }

        // Utility functions
        function showMessage(message, type) {
            const container = document.getElementById('messageContainer');
            const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            
            container.innerHTML = `
                <div class="${alertClass} border px-4 py-3 rounded animate-fade-in">
                    ${message}
                </div>
            `;
            
            if (type === 'success') {
                setTimeout(() => {
                    container.innerHTML = '';
                }, 3000);
            }
        }
    </script>
</body>
</html>
