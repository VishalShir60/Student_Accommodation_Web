/**
 * Student Accommodation Web Application
 * Core JavaScript & AJAX module for filtering, property details, and shortlist toggles.
 * (Enhanced with Nashik & Famous College Badges)
 */

document.addEventListener('DOMContentLoaded', () => {
    // Initialize components if present on page
    if (document.getElementById('property-grid')) {
        initPropertyExplorer();
    }
    
    // Auth navbar status check
    updateShortlistNavBadge();
});

/**
 * Initializes Property Listing Page filter listeners & dynamic AJAX fetching
 */
function initPropertyExplorer() {
    const cityFilter = document.getElementById('filter-city');
    const genderFilter = document.getElementById('filter-gender');
    const priceFilter = document.getElementById('filter-price');
    const priceOutput = document.getElementById('price-output');
    const searchFilter = document.getElementById('filter-search');
    const resetBtn = document.getElementById('btn-reset-filters');

    // Debounce timer for search box
    let debounceTimer = null;

    // Load initial properties
    loadProperties();

    // Event listeners
    if (cityFilter) {
        cityFilter.addEventListener('change', () => {
            syncCityChips(cityFilter.value);
            loadProperties();
        });
    }
    if (genderFilter) genderFilter.addEventListener('change', () => loadProperties());

    if (priceFilter) {
        priceFilter.addEventListener('input', (e) => {
            if (priceOutput) {
                priceOutput.textContent = '₹' + parseInt(e.target.value).toLocaleString('en-IN');
            }
        });
        priceFilter.addEventListener('change', () => loadProperties());
    }

    if (searchFilter) {
        searchFilter.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                loadProperties();
            }, 300);
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            if (cityFilter) cityFilter.value = 'all';
            if (genderFilter) genderFilter.value = 'all';
            if (priceFilter) {
                priceFilter.value = 20000;
                if (priceOutput) priceOutput.textContent = '₹20,000';
            }
            if (searchFilter) searchFilter.value = '';
            syncCityChips('all');
            loadProperties();
        });
    }
}

function syncCityChips(cityVal) {
    document.querySelectorAll('.city-chip').forEach(chip => {
        const text = chip.textContent.trim();
        if ((cityVal === 'all' && text.includes('All')) || text.toLowerCase() === cityVal.toLowerCase()) {
            chip.className = 'btn btn-sm btn-light rounded-pill px-3 fw-bold city-chip active';
        } else {
            chip.className = 'btn btn-sm btn-outline-light rounded-pill px-3 fw-bold city-chip';
        }
    });
}

/**
 * AJAX Property Fetcher
 */
function loadProperties() {
    const grid = document.getElementById('property-grid');
    const countBadge = document.getElementById('property-count-badge');
    if (!grid) return;

    // Get filter inputs
    const city = document.getElementById('filter-city')?.value || 'all';
    const gender = document.getElementById('filter-gender')?.value || 'all';
    const maxPrice = document.getElementById('filter-price')?.value || 20000;
    const search = document.getElementById('filter-search')?.value || '';

    // Show loading spinner indicator
    grid.innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="loading-spinner mb-3"></div>
            <p class="text-muted fw-semibold">Finding perfect student accommodations in ${city === 'all' ? 'top college hubs' : city}...</p>
        </div>
    `;

    const params = new URLSearchParams({
        city: city,
        gender: gender,
        max_price: maxPrice,
        search: search
    });

    fetch(`api/get_properties.php?${params.toString()}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response error');
            return response.json();
        })
        .then(res => {
            if (res.status === 'success') {
                if (countBadge) {
                    countBadge.textContent = `${res.count} ${res.count === 1 ? 'Property' : 'Properties'} Found`;
                }

                if (res.data.length === 0) {
                    grid.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <div class="bg-white p-5 rounded-4 border text-center shadow-sm">
                                <i class="fa-solid fa-house-circle-xmark text-secondary display-4 mb-3"></i>
                                <h4 class="fw-bold">No Accommodations Found</h4>
                                <p class="text-muted">No PG listing matches your exact budget or search query in ${city === 'all' ? 'the database' : city}.</p>
                                <button class="btn btn-outline-primary rounded-pill px-4" onclick="document.getElementById('btn-reset-filters').click()">
                                    Reset Filters
                                </button>
                            </div>
                        </div>
                    `;
                    return;
                }

                grid.innerHTML = res.data.map(prop => renderPropertyCard(prop)).join('');
            } else {
                showToast(res.message || 'Failed to fetch properties', 'danger');
            }
        })
        .catch(err => {
            console.error('AJAX Error:', err);
            grid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="alert alert-danger shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> Error loading properties. Please refresh or try again later.
                    </div>
                </div>
            `;
        });
}

/**
 * Renders HTML string for a property card
 */
function renderPropertyCard(prop) {
    const isInterestedClass = prop.is_interested ? 'active' : '';
    const heartIcon = prop.is_interested ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
    const formattedPrice = parseInt(prop.price).toLocaleString('en-IN');
    
    // Highlight Nashik badge
    const isNashik = prop.city.toLowerCase() === 'nashik';
    const cityBadgeClass = isNashik ? 'bg-warning text-dark' : 'bg-secondary-subtle text-dark';

    return `
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card property-card h-100">
                <div class="card-img-wrapper">
                    <span class="gender-badge ${prop.gender}">
                        <i class="fa-solid ${prop.gender === 'Female' ? 'fa-venus' : prop.gender === 'Male' ? 'fa-mars' : 'fa-genderless'} me-1"></i>
                        ${prop.gender} PG
                    </span>
                    
                    <button class="btn-shortlist ${isInterestedClass}" 
                            onclick="toggleInterest(${prop.id}, this)" 
                            title="${prop.is_interested ? 'Remove from Shortlist' : 'Add to Shortlist'}">
                        <i class="${heartIcon}"></i>
                    </button>

                    <div class="rating-badge">
                        <i class="fa-solid fa-star text-warning me-1"></i>${prop.rating}
                    </div>

                    <a href="property-detail.php?id=${prop.id}">
                        <img src="${prop.main_image}" class="card-img-top" alt="${escapeHtml(prop.name)}" loading="lazy">
                    </a>
                </div>

                <div class="card-body d-flex flex-column p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title fw-bold text-dark mb-0">
                            <a href="property-detail.php?id=${prop.id}" class="text-decoration-none text-dark">
                                ${escapeHtml(prop.name)}
                            </a>
                        </h5>
                    </div>

                    <div class="mb-2">
                        <span class="badge ${cityBadgeClass} rounded-pill px-2.5 py-1 font-semibold small">
                            <i class="fa-solid fa-city me-1"></i> ${escapeHtml(prop.city)}
                        </span>
                    </div>

                    <p class="text-muted small mb-3">
                        <i class="fa-solid fa-location-dot text-danger me-1"></i>
                        ${escapeHtml(prop.address)}
                    </p>

                    <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                        <div>
                            <span class="price-tag">₹${formattedPrice}</span>
                            <span class="price-period">/ month</span>
                        </div>
                        
                        <a href="property-detail.php?id=${prop.id}" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold">
                            View Details <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Dynamic AJAX Shortlist Toggle Handler
 */
function toggleInterest(propertyId, btnElement) {
    if (!propertyId) return;

    // Visual loading state
    const icon = btnElement ? btnElement.querySelector('i') : null;
    if (icon) icon.className = 'fa-solid fa-spinner fa-spin';

    fetch('api/toggle_interest.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ property_id: propertyId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'unauthenticated') {
            showToast('Please log in to shortlist properties.', 'warning');
            setTimeout(() => {
                window.location.href = `login.php?redirect=property-detail.php?id=${propertyId}`;
            }, 1200);
            if (icon) icon.className = 'fa-regular fa-heart';
            return;
        }

        if (data.status === 'success') {
            if (btnElement) {
                if (data.is_interested) {
                    btnElement.classList.add('active');
                    if (icon) icon.className = 'fa-solid fa-heart';
                } else {
                    btnElement.classList.remove('active');
                    if (icon) icon.className = 'fa-regular fa-heart';
                }
            }

            // Update property detail page interest count if present
            const detailCountSpan = document.getElementById('detail-interest-count');
            if (detailCountSpan) {
                detailCountSpan.textContent = `${data.interest_count} Interested ${data.interest_count === 1 ? 'Student' : 'Students'}`;
            }

            // Update main button text on detail page if present
            const detailBtn = document.getElementById('btn-toggle-interest-detail');
            if (detailBtn) {
                if (data.is_interested) {
                    detailBtn.className = 'btn btn-danger btn-lg w-100 rounded-pill fw-bold shadow-sm';
                    detailBtn.innerHTML = '<i class="fa-solid fa-heart me-2"></i> Shortlisted';
                } else {
                    detailBtn.className = 'btn btn-outline-danger btn-lg w-100 rounded-pill fw-bold';
                    detailBtn.innerHTML = '<i class="fa-regular fa-heart me-2"></i> Mark as Interested';
                }
            }

            showToast(data.message, 'success');
            updateShortlistNavBadge();
        } else {
            showToast(data.message || 'Error updating shortlist', 'danger');
            if (icon) icon.className = 'fa-regular fa-heart';
        }
    })
    .catch(err => {
        console.error('Shortlist error:', err);
        showToast('Server connection error', 'danger');
        if (icon) icon.className = 'fa-regular fa-heart';
    });
}

/**
 * Updates navbar shortlist counter badge
 */
function updateShortlistNavBadge() {
    const badge = document.getElementById('nav-shortlist-badge');
    if (!badge) return;

    fetch('api/check_auth.php')
        .then(res => res.json())
        .then(data => {
            if (data.authenticated) {
                badge.textContent = data.shortlist_count;
                badge.style.display = data.shortlist_count > 0 ? 'inline-block' : 'none';
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(() => {});
}

/**
 * Global Toast Notification Renderer
 */
function showToast(message, type = 'info') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `custom-toast border-start border-4 border-${type}`;

    let icon = 'fa-circle-info';
    if (type === 'success') icon = 'fa-circle-check text-success';
    if (type === 'warning') icon = 'fa-triangle-exclamation text-warning';
    if (type === 'danger') icon = 'fa-circle-xmark text-danger';

    toast.innerHTML = `
        <i class="fa-solid ${icon} fs-5"></i>
        <span>${escapeHtml(message)}</span>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Helper to escape HTML characters
 */
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
