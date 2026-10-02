<?php
// index.php
// Property Listing Page (Main Landing & Search Portal)

require_once __DIR__ . '/config/db.php';

// Fetch distinct cities for filter dropdown
$cities_stmt = $pdo->query("SELECT DISTINCT city FROM properties ORDER BY city ASC");
$cities = $cities_stmt->fetchAll(PDO::FETCH_COLUMN);

include __DIR__ . '/includes/header.php';
?>

<main class="container py-4">
    <!-- Hero Banner -->
    <div class="hero-header mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill">
                        <i class="fa-solid fa-sparkles me-1"></i> Verified Student Housing
                    </span>
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill">
                        <i class="fa-solid fa-graduation-cap me-1"></i> Near Top Colleges in Nashik, Mumbai, Pune, Bangalore & Delhi
                    </span>
                </div>
                
                <h1 class="display-5 fw-extrabold mb-3 text-white">Find Your Perfect Student Accommodation</h1>
                <p class="lead mb-4 text-white-50">
                    Explore top-rated Paying Guest (PG) accommodations, hostels, and co-living spaces with transparent pricing, full amenities, and verified student reviews near top educational institutes.
                </p>

                <!-- Quick City Filter Pills -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="text-white-50 small fw-semibold me-1">Quick Select City:</span>
                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold city-chip active" onclick="quickSelectCity('all', this)">All Cities</button>
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold city-chip" onclick="quickSelectCity('Nashik', this)"><i class="fa-solid fa-location-dot me-1 text-warning"></i> Nashik</button>
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold city-chip" onclick="quickSelectCity('Mumbai', this)">Mumbai</button>
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold city-chip" onclick="quickSelectCity('Pune', this)">Pune</button>
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold city-chip" onclick="quickSelectCity('Bangalore', this)">Bangalore</button>
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold city-chip" onclick="quickSelectCity('Delhi', this)">Delhi</button>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end d-none d-lg-block">
                <i class="fa-solid fa-building-circle-check text-white opacity-25" style="font-size: 8rem;"></i>
            </div>
        </div>
    </div>

    <!-- Famous Colleges Highlight Banner -->
    <div class="card border-0 bg-primary-subtle rounded-4 p-3 mb-4 shadow-sm">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-primary fs-3"></i>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Popular Colleges in Nashik:</h6>
                    <small class="text-muted">KK Wagh (KKWIEER) • Sandip University • MET Bhujbal Knowledge City • BYK College • KTHM • SIOM</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-primary rounded-pill fw-semibold px-3" onclick="quickSearchCollege('KK Wagh')">
                    KK Wagh PG
                </button>
                <button class="btn btn-sm btn-outline-primary rounded-pill fw-semibold px-3" onclick="quickSearchCollege('Sandip')">
                    Sandip University PG
                </button>
                <button class="btn btn-sm btn-outline-primary rounded-pill fw-semibold px-3" onclick="quickSearchCollege('BYK')">
                    BYK College PG
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="card filter-card mb-5 p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                <i class="fa-solid fa-sliders text-primary me-2"></i> Filter Accommodations
            </h5>
            <button id="btn-reset-filters" class="btn btn-sm btn-link text-decoration-none text-muted fw-semibold">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset Filters
            </button>
        </div>

        <div class="row g-3">
            <!-- Keyword Search -->
            <div class="col-md-3">
                <label for="filter-search" class="form-label fw-semibold">Search Keywords / Colleges</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="filter-search" class="form-control bg-light border-start-0" placeholder="e.g. KK Wagh, Sandip, IIT, DU...">
                </div>
            </div>

            <!-- City Filter -->
            <div class="col-md-3">
                <label for="filter-city" class="form-label fw-semibold">Select City</label>
                <select id="filter-city" class="form-select bg-light">
                    <option value="all">All Cities</option>
                    <?php foreach ($cities as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Gender Filter -->
            <div class="col-md-3">
                <label for="filter-gender" class="form-label fw-semibold">Gender Preference</label>
                <select id="filter-gender" class="form-select bg-light">
                    <option value="all">All Gender Types</option>
                    <option value="Male">Male PG</option>
                    <option value="Female">Female PG</option>
                    <option value="Unisex">Unisex / Co-living</option>
                </select>
            </div>

            <!-- Budget Filter Slider -->
            <div class="col-md-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="filter-price" class="form-label fw-semibold">Max Monthly Budget</label>
                    <span id="price-output" class="fw-bold text-primary">₹20,000</span>
                </div>
                <input type="range" class="form-range mt-2" id="filter-price" min="4000" max="25000" step="500" value="20000">
            </div>
        </div>
    </div>

    <!-- Listing Section Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            Available PG Properties
        </h4>
        <span id="property-count-badge" class="badge bg-primary fs-6 fw-semibold rounded-pill px-3 py-2">
            Loading...
        </span>
    </div>

    <!-- Dynamic Property Cards Grid Container (Populated via AJAX in app.js) -->
    <div id="property-grid" class="row g-4">
        <!-- Rendered by AJAX -->
    </div>
</main>

<script>
function quickSelectCity(city, btn) {
    document.querySelectorAll('.city-chip').forEach(b => {
        b.className = 'btn btn-sm btn-outline-light rounded-pill px-3 fw-bold city-chip';
    });
    if (btn) {
        btn.className = 'btn btn-sm btn-light rounded-pill px-3 fw-bold city-chip active';
    }
    const citySelect = document.getElementById('filter-city');
    if (citySelect) {
        citySelect.value = city;
        if (window.loadProperties) window.loadProperties();
    }
}

function quickSearchCollege(keyword) {
    const searchInput = document.getElementById('filter-search');
    if (searchInput) {
        searchInput.value = keyword;
        if (window.loadProperties) window.loadProperties();
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
