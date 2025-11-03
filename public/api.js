class EventsAPI {
    static baseURL = '/api/events';

    static async fetchEvents(status = null) {
        try {
            const url = status ? `${this.baseURL}?status=${status}` : this.baseURL;
            const response = await fetch(url);
            return await response.json();
        } catch (error) {
            console.error('Error fetching events:', error);
            return [];
        }
    }

    static async fetchEventById(id) {
        try {
            const response = await fetch(`${this.baseURL}/${id}`);
            return await response.json();
        } catch (error) {
            console.error('Error fetching event:', error);
            return null;
        }
    }

    static async fetchEventByCity(city) {
        try {
            const response = await fetch(`${this.baseURL}/city/${city}`);
            return await response.json();
        } catch (error) {
            console.error('Error fetching event by city:', error);
            return null;
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

    static async updateEvent(id, eventData) {
        try {
            const response = await fetch(`${this.baseURL}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(eventData)
            });
            return await response.json();
        } catch (error) {
            console.error('Error updating event:', error);
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
}

// Load events when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadEvents('upcoming');
    loadEvents('past');
    
    // Initialize modal close functionality
    initializeModal();
});

function initializeModal() {
    const modal = document.getElementById('eventModal');
    const closeModalBtn = document.getElementById('closeModal');
    
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        });
    }
    
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
    }
}

async function loadEvents(status) {
    console.log(`Loading ${status} events...`);
    const events = await EventsAPI.fetchEvents(status);
    console.log(`Received events:`, events);
    const container = document.getElementById(status);
    
    if (!container) {
        console.error(`Container not found for status: ${status}`);
        return;
    }
    
    if (!events || events.length === 0) {
        container.innerHTML = '<div class="text-center py-12"><p class="text-gray-500">No events found.</p></div>';
        return;
    }
    
    const eventsHTML = events.map(event => `
        <div class="event-card bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-all duration-300 cursor-pointer" onclick="openEventModal(${event.id})">
            <div class="relative rounded-xl overflow-hidden mb-4 h-48">
                <img src="${event.image_url}" alt="${event.title}" class="w-full h-full object-cover">
            </div>
            <h3 class="text-xl font-bold mb-2 text-black">${event.title}</h3>
            <p class="text-gray-600 mb-2">${event.date}</p>
            <p class="text-gray-500 text-sm mb-4">${event.location}</p>
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-black">${event.price}</span>
                <button class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition" onclick="event.stopPropagation(); openEventModal(${event.id})">
                    View Details
                </button>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = `<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">${eventsHTML}</div>`;
}

async function openEventModal(eventId) {
    const event = await EventsAPI.fetchEventById(eventId);
    if (!event) return;
    
    document.getElementById('modalTitle').textContent = event.title;
    document.getElementById('modalDate').textContent = event.date;
    document.getElementById('modalLocation').textContent = event.location;
    document.getElementById('modalDescription').textContent = event.description;
    document.getElementById('modalImage').src = event.image_url;
    
    // Update ticket section based on event status
    const ticketSection = document.getElementById('ticketSection');
    if (event.status === 'past') {
        ticketSection.innerHTML = `
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <p class="text-gray-600 font-medium">This event has already ended</p>
                <p class="text-sm text-gray-500 mt-2">Ticket price was: <span class="font-bold">${event.price}</span></p>
            </div>
        `;
    } else {
        ticketSection.innerHTML = `
            <div class="flex justify-between items-center border-b border-gray-200 pb-4">
                <div>
                    <p class="font-medium text-black">General Admission</p>
                    <p class="text-sm text-gray-500">1-day access to the event</p>
                </div>
                <div class="flex items-center">
                    <span class="font-bold mr-4 text-black">${event.price}</span>
                    <button class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition">
                        Buy Now
                    </button>
                </div>
            </div>
            <div class="flex justify-between items-center border-b border-gray-200 pb-4">
                <div>
                    <p class="font-medium text-black">VIP Pass</p>
                    <p class="text-sm text-gray-500">2-day access + early entry + goodie bag</p>
                </div>
                <div class="flex items-center">
                    <span class="font-bold mr-4 text-black">€75</span>
                    <button class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition">
                        Buy Now
                    </button>
                </div>
            </div>
        `;
    }
    
    const modal = document.getElementById('eventModal');
    modal.style.display = 'flex';
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}
