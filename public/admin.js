class AdminAPI {
    static baseURL = '/api/events';

    static async fetchAllEvents() {
        try {
            const response = await fetch(this.baseURL);
            return await response.json();
        } catch (error) {
            console.error('Error fetching events:', error);
            return [];
        }
    }

    static async createEvent(eventData) {
        try {
            const response = await fetch(this.baseURL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(eventData)
            });
            return await response.json();
        } catch (error) {
            console.error('Error creating event:', error);
            return null;
        }
    }

    static async deleteEvent(id) {
        try {
            const response = await fetch(`${this.baseURL}/${id}`, {
                method: 'DELETE'
            });
            return await response.json();
        } catch (error) {
            console.error('Error deleting event:', error);
            return null;
        }
    }

    static async checkDuplicateEvent(title, date) {
        try {
            const events = await this.fetchAllEvents();
            return events.some(event => 
                event.title.toLowerCase() === title.toLowerCase() && 
                event.date === date
            );
        } catch (error) {
            console.error('Error checking duplicate:', error);
            return false;
        }
    }

}

// Load events when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadAllEvents();
    setupEventForm();
});

async function loadAllEvents() {
    try {
        const events = await AdminAPI.fetchAllEvents();
        const tableBody = document.getElementById('eventsTable');
        
        if (events.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No events found
                    </td>
                </tr>
            `;
            return;
        }
        
        const eventsHTML = events.map(event => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <img class="h-12 w-12 rounded-lg object-cover mr-4" src="${event.image_url}" alt="${event.title}">
                        <div>
                            <div class="text-sm font-medium text-gray-900">${event.title}</div>
                            <div class="text-sm text-gray-500">${event.city}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">${event.date}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${event.location}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                        event.status === 'upcoming' 
                            ? 'bg-green-100 text-green-800' 
                            : 'bg-gray-100 text-gray-800'
                    }">
                        ${event.status === 'upcoming' ? 'Upcoming' : 'Past'}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">${event.price}</td>
                <td class="px-6 py-4 text-sm font-medium">
                    <button onclick="deleteEvent(${event.id})" 
                            class="text-red-600 hover:text-red-900 transition-colors">
                        Delete
                    </button>
                </td>
            </tr>
        `).join('');
        
        tableBody.innerHTML = eventsHTML;
    } catch (error) {
        console.error('Error loading events:', error);
        const tableBody = document.getElementById('eventsTable');
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-red-500">
                    Error loading events. Please refresh the page.
                </td>
            </tr>
        `;
        showMessage('Failed to load events. Please try again.', 'error');
    }
}

// CLIENT-SIDE VALIDATION IS HERE
function validateEventForm(eventData) {
    const errors = {};
    let isValid = true;

    // Simple required field validation
    if (!eventData.title || !eventData.title.trim()) {
        errors.title = 'Title is required';
        isValid = false;
    }

    if (!eventData.city || !eventData.city.trim()) {
        errors.city = 'City is required';
        isValid = false;
    }

    if (!eventData.date || !eventData.date.trim()) {
        errors.date = 'Date is required';
        isValid = false;
    }

    if (!eventData.location || !eventData.location.trim()) {
        errors.location = 'Location is required';
        isValid = false;
    }

    if (!eventData.description || !eventData.description.trim()) {
        errors.description = 'Description is required';
        isValid = false;
    }

    if (!eventData.price || !eventData.price.trim()) {
        errors.price = 'Price is required';
        isValid = false;
    }

    if (!eventData.image_url || !eventData.image_url.trim()) {
        errors.image_url = 'Image URL is required';
        isValid = false;
    }

    return { isValid, errors };
}

/**
 * Display validation errors on form
 * 
 * @param {Object} errors - Validation errors object
 */
function displayValidationErrors(errors) {
    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-300');
    });

    // Display new errors
    Object.keys(errors).forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            // Add error styling
            input.classList.remove('border-gray-300');
            input.classList.add('border-red-500');

            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-red-500 text-sm mt-1';
            errorDiv.textContent = errors[field];
            input.parentNode.appendChild(errorDiv);
        }
    });
}

/**
 * Sanitize input data to prevent XSS
 * 
 * @param {Object} data - Input data to sanitize
 * @returns {Object} - Sanitized data
 */
function sanitizeFormData(data) {
    const sanitized = {};
    
    Object.keys(data).forEach(key => {
        if (typeof data[key] === 'string') {
            // Basic XSS prevention
            sanitized[key] = data[key]
                .trim()
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#x27;')
                .replace(/\//g, '&#x2F;');
        } else {
            sanitized[key] = data[key];
        }
    });
    
    return sanitized;
}

function setupEventForm() {
    const form = document.getElementById('addEventForm');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const formData = new FormData(form);
            const eventData = {
                title: formData.get('title'),
                city: formData.get('city'),
                date: formData.get('date'),
                location: formData.get('location'),
                description: formData.get('description'),
                price: formData.get('price'),
                image_url: formData.get('image_url'),
                status: formData.get('status')
            };
            
            // Sanitize input data
            const sanitizedData = sanitizeFormData(eventData);

            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Creating...';
            submitButton.disabled = true;

            // Note: Duplicate validation is now handled by database constraints
            // This provides better data integrity and simpler code
            
            // Create event
            const result = await AdminAPI.createEvent(sanitizedData);
            console.log('Server response:', result); // Debug log
            
            if (result && result.id) {
                showMessage('Event successfully added!', 'success');
                form.reset();
                loadAllEvents(); // Refresh the table
            } else if (result && result.error) {
                showMessage('Error: ' + result.error, 'error');
            } else {
                showMessage('Event created successfully!', 'success');
                form.reset();
                loadAllEvents(); // Refresh the table anyway
            }
            
            // Restore button state after 2 seconds for better UX
            setTimeout(() => {
                submitButton.textContent = originalText || 'Add Event';
                submitButton.disabled = false;
            }, 2000);
        } catch (error) {
            console.error('Form submission error:', error);
            showMessage('Network error occurred. Please try again.', 'error');
        } finally {
            // Restore button state
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.textContent = originalText || 'Add Event';
                submitButton.disabled = false;
            }
        }
    });
}

async function deleteEvent(id) {
    try {
        if (!confirm('Are you sure you want to delete this event?')) {
            return;
        }
        
        const result = await AdminAPI.deleteEvent(id);
        if (result) {
            showMessage('Event successfully deleted!', 'success');
            loadAllEvents(); // Refresh the table
        } else {
            showMessage('Error deleting event.', 'error');
        }
    } catch (error) {
        console.error('Error deleting event:', error);
        showMessage('Network error occurred while deleting event.', 'error');
    }
}

function showMessage(message, type) {
    const container = document.getElementById('messageContainer');
    const alertClass = type === 'success' 
        ? 'bg-green-100 border-green-400 text-green-700' 
        : 'bg-red-100 border-red-400 text-red-700';
    
    container.innerHTML = `
        <div class="border-l-4 ${alertClass} p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i data-feather="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        </div>
    `;
    
    // Replace icons
    feather.replace();
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        container.innerHTML = '';
    }, 5000);
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
