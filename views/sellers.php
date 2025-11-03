<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verkopers Overzicht | Sneakerness</title>
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
                <span class="text-black font-medium">Verkopers</span>
                <button onclick="logout()" class="text-gray-600 hover:text-black transition-colors">
                    <i data-feather="log-out" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Verkopers Overzicht</h1>
            <p class="text-gray-600">Beheer alle verkopers en hun informatie</p>
        </div>

        <!-- Success/Error Messages -->
        <div id="messageContainer" class="mb-6"></div>

        <!-- Action Bar -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        <option value="">Alle statussen</option>
                        <option value="active">Actief</option>
                        <option value="inactive">Inactief</option>
                    </select>
                    <button onclick="loadSellers()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <i data-feather="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                        Vernieuwen
                    </button>
                </div>
                <a href="/sellers/add" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i data-feather="plus" class="w-4 h-4 inline mr-2"></i>
                    Verkoper Toevoegen
                </a>
            </div>
        </div>

        <!-- Sellers Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mailadres</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bedrijf</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aangemaakt</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                        </tr>
                    </thead>
                    <tbody id="sellersTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Sellers will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Empty State -->
            <div id="emptyState" class="text-center py-12 hidden">
                <div class="text-gray-400 mb-4">
                    <i data-feather="users" class="w-16 h-16 mx-auto"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Geen verkopers beschikbaar</h3>
                <p class="text-gray-500 mb-6">Er zijn nog geen verkopers geregistreerd in het systeem. Begin door je eerste verkoper toe te voegen.</p>
                <a href="/sellers/add" class="inline-block px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i data-feather="plus" class="w-4 h-4 inline mr-2"></i>
                    Eerste Verkoper Toevoegen
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Seller Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden overflow-y-auto">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 my-8 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Verkoper Bewerken</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <div id="editMessageContainer" class="mb-4"></div>
            
            <form id="editSellerForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Naam *</label>
                        <input type="text" id="editName" name="name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">E-mailadres *</label>
                        <input type="email" id="editEmail" name="email" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telefoon</label>
                        <input type="tel" id="editPhone" name="phone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bedrijf</label>
                        <input type="text" id="editCompany" name="company" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adres</label>
                        <input type="text" id="editAddress" name="address" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stad</label>
                        <input type="text" id="editCity" name="city" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Postcode</label>
                        <input type="text" id="editPostalCode" name="postal_code" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Land</label>
                        <select id="editCountry" name="country" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            <option value="">Selecteer land</option>
                            <option value="Nederland">Nederland</option>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="editStatus" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            <option value="active">Actief</option>
                            <option value="inactive">Inactief</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Annuleren
                    </button>
                    <button type="submit" id="updateSellerBtn" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                        Bijwerken
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Verkoper Verwijderen</h3>
            <p class="text-gray-600 mb-6">Weet je zeker dat je deze verkoper wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.</p>
            <div class="flex justify-end space-x-4">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                    Annuleren
                </button>
                <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Verwijderen
                </button>
            </div>
        </div>
    </div>

    <script>
        let sellers = [];
        let deleteId = null;
        let editId = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
            loadSellers();
            
            // Status filter change
            document.getElementById('statusFilter').addEventListener('change', loadSellers);
            
            // Edit form submission
            document.getElementById('editSellerForm').addEventListener('submit', handleEditSubmit);
        });

        // Load sellers from API
        async function loadSellers() {
            try {
                const status = document.getElementById('statusFilter').value;
                const url = status ? `/api/sellers?status=${status}` : '/api/sellers';
                
                const response = await fetch(url);
                const data = await response.json();
                
                if (response.ok) {
                    sellers = data;
                    renderSellers();
                } else {
                    showMessage(data.message || 'Fout bij het laden van verkopers', 'error');
                }
            } catch (error) {
                console.error('Error loading sellers:', error);
                showMessage('Fout bij het laden van verkopers', 'error');
            }
        }

        // Render sellers table
        function renderSellers() {
            const tbody = document.getElementById('sellersTableBody');
            const emptyState = document.getElementById('emptyState');
            
            if (sellers.length === 0) {
                tbody.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }
            
            emptyState.classList.add('hidden');
            
            tbody.innerHTML = sellers.map(seller => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">${escapeHtml(seller.name)}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-gray-900">${escapeHtml(seller.email)}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-gray-900">${escapeHtml(seller.company || '-')}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${seller.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${seller.status === 'active' ? 'Actief' : 'Inactief'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${formatDate(seller.created_at)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="editSeller(${seller.id})" 
                                    class="text-green-600 hover:text-green-900 transition-colors" 
                                    title="Bewerken">
                                <i data-feather="edit-2" class="w-4 h-4"></i>
                            </button>
                            <button onclick="toggleStatus(${seller.id}, '${seller.status}')" 
                                    class="text-blue-600 hover:text-blue-900 transition-colors" 
                                    title="${seller.status === 'active' ? 'Deactiveren' : 'Activeren'}">
                                <i data-feather="${seller.status === 'active' ? 'eye-off' : 'eye'}" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteSeller(${seller.id})" 
                                    class="text-red-600 hover:text-red-900 transition-colors" 
                                    title="Verwijderen">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
            
            feather.replace();
        }

        // Toggle seller status
        async function toggleStatus(id, currentStatus) {
            try {
                const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                
                const response = await fetch(`/api/sellers/${id}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    showMessage(data.message, 'success');
                    loadSellers();
                } else {
                    showMessage(data.message || 'Fout bij het bijwerken van status', 'error');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                showMessage('Fout bij het bijwerken van status', 'error');
            }
        }

        // Edit seller
        async function editSeller(id) {
            try {
                const response = await fetch(`/api/sellers/${id}`);
                const data = await response.json();
                
                if (response.ok) {
                    editId = id;
                    
                    // Populate form fields
                    document.getElementById('editName').value = data.name || '';
                    document.getElementById('editEmail').value = data.email || '';
                    document.getElementById('editPhone').value = data.phone || '';
                    document.getElementById('editCompany').value = data.company || '';
                    document.getElementById('editAddress').value = data.address || '';
                    document.getElementById('editCity').value = data.city || '';
                    document.getElementById('editPostalCode').value = data.postal_code || '';
                    document.getElementById('editCountry').value = data.country || '';
                    document.getElementById('editStatus').value = data.status || 'active';
                    
                    // Show modal
                    document.getElementById('editModal').classList.remove('hidden');
                    document.getElementById('editMessageContainer').innerHTML = '';
                } else {
                    showMessage(data.message || 'Fout bij het laden van verkoper', 'error');
                }
            } catch (error) {
                console.error('Error loading seller:', error);
                showMessage('Fout bij het laden van verkoper', 'error');
            }
        }

        // Close edit modal
        function closeEditModal() {
            editId = null;
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editSellerForm').reset();
            document.getElementById('editMessageContainer').innerHTML = '';
        }

        // Handle edit form submission
        async function handleEditSubmit(e) {
            e.preventDefault();
            
            if (!editId) return;
            
            const updateBtn = document.getElementById('updateSellerBtn');
            const originalText = updateBtn.textContent;
            
            // Show loading state
            updateBtn.disabled = true;
            updateBtn.textContent = 'Bijwerken...';
            
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
                
                const response = await fetch(`/api/sellers/${editId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showEditMessage(result.message, 'success');
                    loadSellers(); // Refresh the table
                    
                    // Close modal after short delay
                    setTimeout(() => {
                        closeEditModal();
                    }, 1500);
                } else {
                    if (result.errors) {
                        showEditMessage(result.errors.join('<br>'), 'error');
                    } else {
                        showEditMessage(result.message || 'Fout bij het bijwerken van verkoper', 'error');
                    }
                }
            } catch (error) {
                console.error('Error updating seller:', error);
                showEditMessage('Er is een fout opgetreden. Probeer het opnieuw.', 'error');
            } finally {
                // Restore button
                updateBtn.disabled = false;
                updateBtn.textContent = originalText;
            }
        }

        // Show message in edit modal
        function showEditMessage(message, type) {
            const container = document.getElementById('editMessageContainer');
            const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            
            container.innerHTML = `
                <div class="${alertClass} border px-4 py-3 rounded animate-fade-in">
                    ${message}
                </div>
            `;
        }

        // Delete seller
        function deleteSeller(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // Close delete modal
        function closeDeleteModal() {
            // Show message that deletion was cancelled
            if (deleteId) {
                showMessage('Verwijdering geannuleerd. De verkoper blijft behouden in het systeem.', 'info');
            }
            deleteId = null;
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Confirm delete
        document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
            if (!deleteId) return;
            
            try {
                const response = await fetch(`/api/sellers/${deleteId}`, {
                    method: 'DELETE'
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    showMessage(data.message, 'success');
                    loadSellers();
                } else {
                    showMessage(data.message || 'Fout bij het verwijderen van verkoper', 'error');
                }
            } catch (error) {
                console.error('Error deleting seller:', error);
                showMessage('Fout bij het verwijderen van verkoper', 'error');
            }
            
            closeDeleteModal();
        });

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
            let alertClass = 'bg-red-100 border-red-400 text-red-700'; // default error
            
            if (type === 'success') {
                alertClass = 'bg-green-100 border-green-400 text-green-700';
            } else if (type === 'info') {
                alertClass = 'bg-blue-100 border-blue-400 text-blue-700';
            }
            
            container.innerHTML = `
                <div class="${alertClass} border px-4 py-3 rounded animate-fade-in">
                    ${escapeHtml(message)}
                </div>
            `;
            
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('nl-NL', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    </script>
</body>
</html>
