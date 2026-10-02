<?php
// signup.php
// User Registration Page

require_once __DIR__ . '/config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include __DIR__ . '/includes/header.php';
?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 p-4">
                <div class="text-center mb-4">
                    <div class="brand-icon mx-auto mb-3" style="width: 48px; height: 48px; font-size: 1.5rem;">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h3 class="fw-bold mb-1">Create an Account</h3>
                    <p class="text-muted small">Sign up to explore & shortlist PG accommodations</p>
                </div>

                <div id="signup-alert" class="alert alert-danger py-2 small" style="display: none;"></div>

                <form id="signup-form">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-user"></i></span>
                            <input type="text" id="name" class="form-control bg-light border-start-0" placeholder="e.g. Rahul Sharma" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" id="email" class="form-control bg-light border-start-0" placeholder="rahul@example.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-phone"></i></span>
                            <input type="tel" id="phone" class="form-control bg-light border-start-0" placeholder="9876543210" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="password" class="form-control bg-light border-start-0" placeholder="At least 6 characters" required minlength="6">
                        </div>
                    </div>

                    <button type="submit" id="btn-signup" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm">
                        <i class="fa-solid fa-user-check me-2"></i> Register Account
                    </button>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <small class="text-muted">Already have an account? <a href="login.php" class="fw-bold text-primary text-decoration-none">Log In</a></small>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('signup-form');
    const alertBox = document.getElementById('signup-alert');
    const btn = document.getElementById('btn-signup');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        alertBox.style.display = 'none';

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!name || !email || !phone || !password) {
            alertBox.textContent = 'Please fill in all required fields.';
            alertBox.style.display = 'block';
            return;
        }

        if (password.length < 6) {
            alertBox.textContent = 'Password must be at least 6 characters long.';
            alertBox.style.display = 'block';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Registering...';

        fetch('api/signup.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email, phone, password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast('Registration successful! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 1000);
            } else {
                alertBox.textContent = data.message || 'Registration failed';
                alertBox.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-user-check me-2"></i> Register Account';
            }
        })
        .catch(err => {
            alertBox.textContent = 'Server connection error.';
            alertBox.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-user-check me-2"></i> Register Account';
        });
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
