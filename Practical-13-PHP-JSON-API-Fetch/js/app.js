// Practical 13: Single Page CRUD Application using Fetch API & REST JSON
// Beginner-friendly JavaScript

var allEvents = [];
var apiUrl = "api/events.php";

// 1. Show Toast notification
function showToast(message, type) {
    var toast = document.getElementById("toastMessage");
    toast.innerText = message;
    toast.className = (type === "success") ? "toast-success" : "toast-danger";
    toast.style.display = "block";

    setTimeout(function() {
        toast.style.display = "none";
    }, 3500);
}

// 2. Load Events from REST API (GET)
function loadEvents() {
    var container = document.getElementById("eventsContainer");
    container.innerHTML = "<p style='color:#777; width:100%; text-align:center;'>Loading campus events...</p>";

    fetch(apiUrl)
        .then(function(res) { return res.json(); })
        .then(function(response) {
            if (response.status === "success") {
                allEvents = response.data;
                document.getElementById("eventCount").innerText = "Total Events: " + response.count;
                renderEvents(allEvents);
            } else {
                showToast("Failed to load events: " + response.message, "danger");
            }
        })
        .catch(function(err) {
            console.error(err);
            showToast("Server connection error while loading events.", "danger");
        });
}

// 3. Render Event Cards to the DOM
function renderEvents(events) {
    var container = document.getElementById("eventsContainer");
    container.innerHTML = "";

    if (events.length === 0) {
        container.innerHTML = "<p style='color:#777; width:100%; text-align:center;'>No campus events found.</p>";
        return;
    }

    events.forEach(function(event) {
        var card = document.createElement("div");
        card.className = "card";
        card.innerHTML = 
            '<h3>' + escapeHtml(event.title) + '</h3>' +
            '<span class="card-badge">' + escapeHtml(event.category) + '</span>' +
            '<p class="card-desc">' + escapeHtml(event.description) + '</p>' +
            '<p><strong>Date:</strong> ' + escapeHtml(event.event_date) + '</p>' +
            '<p><strong>Venue:</strong> ' + escapeHtml(event.venue) + '</p>' +
            '<p><strong>Seats:</strong> ' + escapeHtml(event.max_seats) + '</p>' +
            '<div class="card-actions">' +
                '<button class="btn btn-edit" onclick="editEvent(' + event.id + ')">Edit</button>' +
                '<button class="btn btn-delete" onclick="deleteEvent(' + event.id + ')">Delete</button>' +
            '</div>';
        container.appendChild(card);
    });
}

// Simple HTML escaping helper
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// 4. Modal Open/Close Controls
function openModal(isEdit, data) {
    var modal = document.getElementById("eventModal");
    var title = document.getElementById("modalTitle");
    
    if (isEdit && data) {
        title.innerText = "Edit Campus Event";
        document.getElementById("eventId").value = data.id;
        document.getElementById("eventTitle").value = data.title;
        document.getElementById("eventCategory").value = data.category;
        document.getElementById("eventDesc").value = data.description;
        document.getElementById("eventDate").value = data.event_date;
        document.getElementById("eventSeats").value = data.max_seats;
        document.getElementById("eventVenue").value = data.venue;
    } else {
        title.innerText = "Add Campus Event";
        document.getElementById("eventForm").reset();
        document.getElementById("eventId").value = "";
    }

    modal.style.display = "flex";
}

function closeModal() {
    document.getElementById("eventModal").style.display = "none";
}

// 5. Open Modal for Edit (GET Single Event by ID)
window.editEvent = function(id) {
    fetch(apiUrl + "?id=" + id)
        .then(function(res) { return res.json(); })
        .then(function(response) {
            if (response.status === "success") {
                openModal(true, response.data);
            } else {
                showToast("Failed to load event details.", "danger");
            }
        })
        .catch(function(err) {
            showToast("Network error fetching event details.", "danger");
        });
};

// 6. Delete Event via API (DELETE)
window.deleteEvent = function(id) {
    if (!confirm("Are you sure you want to delete this event? This action cannot be undone.")) {
        return;
    }

    fetch(apiUrl + "?id=" + id, {
        method: "DELETE"
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
        if (response.status === "success") {
            showToast("Event deleted successfully!", "success");
            loadEvents();
        } else {
            showToast("Delete failed: " + response.message, "danger");
        }
    })
    .catch(function(err) {
        showToast("Network error deleting event.", "danger");
    });
};

// DOM Event Listeners
window.addEventListener("DOMContentLoaded", function() {
    loadEvents();

    // Open Add Modal
    document.getElementById("btnOpenAddModal").addEventListener("click", function() {
        openModal(false);
    });

    // Close Modal Button
    document.getElementById("btnCancelModal").addEventListener("click", closeModal);

    // Close Modal on clicking dark background
    document.getElementById("eventModal").addEventListener("click", function(e) {
        if (e.target === this) closeModal();
    });

    // Handle Form Submit (POST for Create, PUT for Update)
    document.getElementById("eventForm").addEventListener("submit", function(e) {
        e.preventDefault();

        var eventId = document.getElementById("eventId").value;
        var payload = {
            title: document.getElementById("eventTitle").value.trim(),
            category: document.getElementById("eventCategory").value,
            description: document.getElementById("eventDesc").value.trim(),
            event_date: document.getElementById("eventDate").value,
            max_seats: parseInt(document.getElementById("eventSeats").value, 10),
            venue: document.getElementById("eventVenue").value.trim()
        };

        var method = "POST";
        if (eventId) {
            method = "PUT";
            payload.id = parseInt(eventId, 10);
        }

        fetch(apiUrl, {
            method: method,
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        })
        .then(function(res) { return res.json(); })
        .then(function(response) {
            if (response.status === "success") {
                closeModal();
                showToast(response.message, "success");
                loadEvents();
            } else {
                showToast(response.message, "danger");
            }
        })
        .catch(function(err) {
            showToast("Failed to save event. Check console for details.", "danger");
        });
    });

    // Live search input
    document.getElementById("searchInput").addEventListener("input", function(e) {
        var query = e.target.value.toLowerCase().trim();
        var filtered = allEvents.filter(function(item) {
            return item.title.toLowerCase().indexOf(query) !== -1 ||
                   item.category.toLowerCase().indexOf(query) !== -1 ||
                   item.description.toLowerCase().indexOf(query) !== -1;
        });
        renderEvents(filtered);
    });
});
