<?php
// login.php
// User Login Page

require_once __DIR__ . '/config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

include __DIR__ . '/includes/header.php';
?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 p-4">
                <div class="text-center mb-4">
                    <div class="brand-icon mx-auto mb-3" style="width: 48px; height: 48px; font-size: 1.5rem;">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h3 class="fw-bold mb-1">Welcome Back</h3>
                    <p class="text-muted small">Log in to manage your shortlisted student PGs</p>
                </div>

                <div id="login-alert" class="alert alert-danger py-2 small" style="display: none;"></div>

                <form id="login-form">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" id="email" class="form-control bg-light border-start-0" placeholder="e.g. rahul@example.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-key"></i></span>
                            <input type="password" id="password" class="form-control bg-light border-start-0" placeholder="Enter password" required>
                        </div>
                    </div>

                    <button type="submit" id="btn-login" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-sm">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Log In
                    </button>
                </form>

                <hr class="my-4">

                <!-- Demo Login Credentials Helper -->
                <div class="bg-light p-3 rounded-3 mb-3">
                    <small class="fw-bold text-dark d-block mb-1"><i class="fa-solid fa-circle-info text-primary me-1"></i> Demo Credentials:</small>
                    <small class="text-muted d-block">Email: <code>rahul@example.com</code></small>
                    <small class="text-muted d-block">Password: <code>password123</code></small>
                </div>

                <div class="text-center">
                    <small class="text-muted">Don't have an account? <a href="signup.php" class="fw-bold text-primary text-decoration-none">Sign Up</a></small>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    const alertBox = document.getElementById('login-alert');
    const btn = document.getElementById('btn-login');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        alertBox.style.display = 'none';

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!email || !password) {
            alertBox.textContent = 'Please fill in all fields.';
            alertBox.style.display = 'block';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Logging in...';

        fetch('api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast('Login successful! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = '<?= htmlspecialchars($redirect) ?>';
                }, 1000);
            } else {
                alertBox.textContent = data.message || 'Invalid credentials';
                alertBox.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-right-to-bracket me-2"></i> Log In';
            }
        })
        .catch(err => {
            alertBox.textContent = 'Server connection error.';
            alertBox.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-right-to-bracket me-2"></i> Log In';
        });
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
