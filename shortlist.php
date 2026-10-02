<?php
// shortlist.php
// User Shortlisted Accommodations Page (Powered by React Component Integration)

require_once __DIR__ . '/config/db.php';

include __DIR__ . '/includes/header.php';
?>

<main class="container py-4">
    <!-- React Component Container for Shortlist Manager -->
    <div id="react-shortlist-app">
        <div class="text-center py-5">
            <div class="loading-spinner mb-3"></div>
            <p class="text-muted fw-semibold">Initializing React Component...</p>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
