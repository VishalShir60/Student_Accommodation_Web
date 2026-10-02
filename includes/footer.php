<?php
// includes/footer.php
?>
    <!-- Footer -->
    <footer class="py-5 border-top text-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-icon">
                            <i class="fa-solid fa-building-user"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-white">StudentStay</h4>
                    </div>
                    <p class="text-secondary small">
                        Discover verified Paying Guest (PG) accommodations, hostels, and co-living spaces tailored for students and young professionals across top educational hubs in India.
                    </p>
                    <div class="mt-3">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill font-semibold">
                            <i class="fa-solid fa-code me-1"></i> Built by Vishal Shirsath
                        </span>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <h6 class="fw-bold text-white mb-3">Quick Links</h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2">
                        <li><a href="index.php">Explore Accommodations</a></li>
                        <li><a href="shortlist.php">Saved Shortlist</a></li>
                        <li><a href="login.php">User Login</a></li>
                        <li><a href="signup.php">Register Account</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4">
                    <h6 class="fw-bold text-white mb-3">Popular Cities</h6>
                    <ul class="list-unstyled text-secondary small d-flex flex-column gap-2">
                        <li><a href="index.php?city=Nashik">PG in Nashik</a></li>
                        <li><a href="index.php?city=Mumbai">PG in Mumbai</a></li>
                        <li><a href="index.php?city=Bangalore">PG in Bangalore</a></li>
                        <li><a href="index.php?city=Delhi">PG in Delhi</a></li>
                        <li><a href="index.php?city=Pune">PG in Pune</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4">
                    <h6 class="fw-bold text-white mb-3">Tech Stack</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-dark border border-secondary text-secondary">HTML5 & CSS3</span>
                        <span class="badge bg-dark border border-secondary text-secondary">Bootstrap 5</span>
                        <span class="badge bg-dark border border-secondary text-secondary">PHP 8</span>
                        <span class="badge bg-dark border border-secondary text-secondary">MySQL</span>
                        <span class="badge bg-dark border border-secondary text-secondary">JavaScript & AJAX</span>
                        <span class="badge bg-dark border border-secondary text-secondary">React 18</span>
                    </div>
                </div>
            </div>

            <div class="border-top border-secondary mt-4 pt-4 text-center text-secondary small">
                <p class="mb-0">
                    &copy; <?= date('Y') ?> StudentStay. Designed & Developed with <i class="fa-solid fa-heart text-danger mx-1"></i> by <strong class="text-white">Vishal Shirsath</strong>.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core App JS -->
    <script src="assets/js/app.js"></script>
    <!-- React Component Script (Parsed with Babel) -->
    <script type="text/babel" src="assets/js/react_app.js"></script>
</body>
</html>
