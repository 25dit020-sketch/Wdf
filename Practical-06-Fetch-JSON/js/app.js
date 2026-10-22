// Practical 6: Asynchronous Data Loading with Fetch API & JSON
// StudentHub Portal - Beginner Friendly JavaScript

// State variables
var currentTab = "events"; // options: 'events', 'students', 'faqs'
var allData = [];          // full raw array from JSON
var filteredData = [];     // array after applying search and filter
var currentPage = 1;
var itemsPerPage = 4;      // items to show per page

// Helper to get element by ID
function $(id) {
    return document.getElementById(id);
}

// 1. Function to Load Data from JSON using Fetch API
function loadData(tabName) {
    currentTab = tabName;
    currentPage = 1;
    $("searchInput").value = "";
    $("statusMessage").innerText = "Loading data from server...";
    $("statusMessage").style.display = "block";
    $("dataContainer").innerHTML = "";

    var jsonPath = "data/" + tabName + ".json";

    fetch(jsonPath)
        .then(function(response) {
            if (!response.ok) {
                throw new Error("HTTP error! Status: " + response.status);
            }
            return response.json();
        })
        .then(function(data) {
            allData = data;
            filteredData = data;
            $("statusMessage").style.display = "none";
            updateCategoryDropdown();
            renderData();
        })
        .catch(function(error) {
            console.error("Fetch Error:", error);
            $("statusMessage").innerText = "Failed to load data. Please check local server / file path.";
            $("statusMessage").style.color = "red";
        });
}

// 2. Dynamically Populate Category / Department Filter Dropdown
function updateCategoryDropdown() {
    var select = $("categorySelect");
    select.innerHTML = '<option value="ALL">All Categories / Depts</option>';

    // Collect unique categories or departments
    var uniqueKeys = [];
    for (var i = 0; i < allData.length; i++) {
        var key = allData[i].category || allData[i].department;
        if (key && uniqueKeys.indexOf(key) === -1) {
            uniqueKeys.push(key);
        }
    }

    // Append options
    for (var j = 0; j < uniqueKeys.length; j++) {
        var opt = document.createElement("option");
        opt.value = uniqueKeys[j];
        opt.innerText = uniqueKeys[j];
        select.appendChild(opt);
    }
}

// 3. Filter Data by Search Keyword and Category Dropdown
function applyFilters() {
    var keyword = $("searchInput").value.toLowerCase().trim();
    var selectedCategory = $("categorySelect").value;

    filteredData = allData.filter(function(item) {
        var matchKeyword = false;
        var matchCategory = false;

        // Check search keyword
        if (keyword === "") {
            matchKeyword = true;
        } else {
            if (item.title && item.title.toLowerCase().indexOf(keyword) !== -1) matchKeyword = true;
            if (item.name && item.name.toLowerCase().indexOf(keyword) !== -1) matchKeyword = true;
            if (item.question && item.question.toLowerCase().indexOf(keyword) !== -1) matchKeyword = true;
            if (item.description && item.description.toLowerCase().indexOf(keyword) !== -1) matchKeyword = true;
        }

        // Check category or department
        var itemCat = item.category || item.department;
        if (selectedCategory === "ALL" || itemCat === selectedCategory) {
            matchCategory = true;
        }

        return matchKeyword && matchCategory;
    });

    currentPage = 1; // reset to first page after filtering
    renderData();
}

// 4. Render Data Cards to the DOM with Pagination
function renderData() {
    var container = $("dataContainer");
    container.innerHTML = "";

    if (filteredData.length === 0) {
        container.innerHTML = '<p style="text-align:center; width:100%; color:#777;">No records found matching your criteria.</p>';
        updatePagination(0);
        return;
    }

    // Pagination slice
    var startIndex = (currentPage - 1) * itemsPerPage;
    var endIndex = startIndex + itemsPerPage;
    var pageItems = filteredData.slice(startIndex, endIndex);

    // Build HTML based on current tab
    for (var i = 0; i < pageItems.length; i++) {
        var item = pageItems[i];
        var card = document.createElement("div");

        if (currentTab === "events") {
            card.className = "card";
            card.innerHTML = 
                '<h3>' + item.title + '</h3>' +
                '<span class="card-badge">' + item.category + '</span>' +
                '<p>' + item.description + '</p>' +
                '<p><span class="highlight">Venue:</span> ' + item.venue + '</p>' +
                '<div class="card-footer">' +
                    '<span>Date: ' + item.date + '</span> | <span>Seats: ' + item.seats + '</span>' +
                '</div>';
        } else if (currentTab === "students") {
            card.className = "card";
            card.innerHTML = 
                '<h3>' + item.name + '</h3>' +
                '<span class="card-badge">Dept: ' + item.department + '</span>' +
                '<p><span class="highlight">Roll No:</span> ' + item.roll_no + '</p>' +
                '<p><span class="highlight">Email:</span> ' + item.email + '</p>' +
                '<div class="card-footer">' +
                    '<span>CGPA: <strong>' + item.cgpa + '</strong></span>' +
                '</div>';
        } else if (currentTab === "faqs") {
            card.className = "faq-item";
            card.innerHTML = 
                '<div class="faq-question">Q: ' + item.question + ' <span class="card-badge" style="float:right;">' + item.category + '</span></div>' +
                '<div class="faq-answer">' + item.answer + '</div>';
        }

        container.appendChild(card);
    }

    updatePagination(filteredData.length);
}

// 5. Update Pagination Controls
function updatePagination(totalCount) {
    var totalPages = Math.ceil(totalCount / itemsPerPage) || 1;
    $("pageIndicator").innerText = "Page " + currentPage + " of " + totalPages + " (" + totalCount + " items)";

    $("prevBtn").disabled = (currentPage === 1);
    $("nextBtn").disabled = (currentPage >= totalPages);
}

// Event Listeners on DOM Load
window.addEventListener("DOMContentLoaded", function() {
    // Load initial tab
    loadData("events");

    // Tab buttons
    var tabButtons = document.querySelectorAll(".tab-btn");
    tabButtons.forEach(function(btn) {
        btn.addEventListener("click", function() {
            tabButtons.forEach(function(b) { b.classList.remove("active"); });
            this.classList.add("active");
            var tab = this.getAttribute("data-tab");
            loadData(tab);
        });
    });

    // Search and Filter events
    $("searchInput").addEventListener("input", applyFilters);
    $("categorySelect").addEventListener("change", applyFilters);

    // Pagination events
    $("prevBtn").addEventListener("click", function() {
        if (currentPage > 1) {
            currentPage--;
            renderData();
        }
    });

    $("nextBtn").addEventListener("click", function() {
        var totalPages = Math.ceil(filteredData.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            renderData();
        }
    });
});
