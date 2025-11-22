<?php
// login.php - StockMaster Login UI (Light Mode)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>StockMaster – Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Optional: Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
        }

        .auth-wrapper {
            min-height: 100vh;
        }

        .auth-card {
            border-radius: 18px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow:
                0 18px 45px rgba(15, 23, 42, 0.08),
                0 0 0 1px rgba(148, 163, 184, 0.12);
            background: #ffffff;
            color: #111827;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            background: #eef2ff;
            border: 1px solid rgba(129, 140, 248, 0.5);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #4b5563;
        }

        .brand-icon {
            width: 26px;
            height: 26px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 30% 30%, #6366f1, #3b82f6);
            color: #eef2ff;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .brand-title {
            font-weight: 700;
            letter-spacing: 0.08em;
            font-size: 0.78rem;
        }

        .auth-title {
            font-size: 1.7rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .auth-subtitle {
            color: #6b7280;
            font-size: 0.92rem;
        }

        .form-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .form-control {
            border-radius: 999px;
            background: #f9fafb;
            border: 1px solid rgba(148, 163, 184, 0.8);
            color: #111827;
            padding: 0.6rem 0.9rem;
            font-size: 0.9rem;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 1px rgba(129, 140, 248, 0.4);
            background-color: #ffffff;
            color: #111827;
        }

        .btn-primary-gradient {
            border-radius: 999px;
            border: none;
            padding: 0.65rem 1.1rem;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background-image: linear-gradient(120deg, #6366f1, #3b82f6);
            color: #f9fafb;
        }

        .btn-primary-gradient:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .btn-outline-dark-rounded {
            border-radius: 999px;
            border-width: 1px;
            padding: 0.5rem 1rem;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .link-sm {
            font-size: 0.82rem;
            color: #2563eb;
            text-decoration: none;
        }

        .link-sm:hover {
            color: #1d4ed8;
        }

        .text-muted-xs {
            font-size: 0.78rem;
            color: #6b7280;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #9ca3af;
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            flex: 1;
            background: linear-gradient(to right, transparent, rgba(209, 213, 219, 0.9));
        }

        .password-toggle {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
        }

        .alert-custom {
            border-radius: 999px;
            padding: 0.6rem 0.9rem;
            font-size: 0.82rem;
        }

        .modal-content {
            background-color: #ffffff;
            border-radius: 18px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            color: #111827;
        }

        .step-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
        }

        .step-badge {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            border: 1px solid rgba(148, 163, 184, 0.6);
            margin-right: 0.35rem;
            background: #f9fafb;
            color: #6b7280;
        }

        .step-badge.active {
            border-color: #6366f1;
            background: #6366f1;
            color: #ffffff;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container auth-wrapper d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="auth-card p-4 p-md-5">
                <!-- Brand Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="brand-badge">
                        <span class="brand-icon">SM</span>
                        <span class="brand-title">StockMaster</span>
                    </div>
                    <a href="signup.php" class="btn btn-outline-dark btn-outline-dark-rounded">
                        Sign up
                    </a>
                </div>

                <!-- Title -->
                <div class="mb-4">
                    <h1 class="auth-title mb-1">Welcome back</h1>
                    <p class="auth-subtitle mb-0">
                        Sign in to manage inventory, track stock, and stay on top of your warehouse.
                    </p>
                </div>

                <!-- Alerts -->
                <div id="loginAlert" class="d-none alert alert-custom" role="alert"></div>

                <!-- Login Form -->
                <form id="loginForm" class="mb-3">
                    <!-- Login ID -->
                    <div class="mb-3">
                        <label for="loginId" class="form-label">Login ID</label>
                        <input
                            type="text"
                            class="form-control"
                            id="loginId"
                            name="loginId"
                            placeholder="Enter your login ID"
                            autocomplete="username"
                            required
                        >
                    </div>

                    <!-- Password -->
                    <div class="mb-2 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                        <span class="password-toggle" id="togglePassword">Show</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check text-muted-xs">
                            <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">
                                Remember me
                            </label>
                        </div>
                        <button
                            type="button"
                            class="btn btn-link link-sm p-0 border-0"
                            data-bs-toggle="modal"
                            data-bs-target="#forgotPasswordModal"
                        >
                            Forgot password?
                        </button>
                    </div>

                    <!-- Submit -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary-gradient" id="loginBtn">
                            Log in
                        </button>
                    </div>
                </form>

                <div class="mb-3">
                    <div class="divider mb-3">
                        Or
                    </div>
                    <p class="text-muted-xs mb-0">
                        New to StockMaster?
                        <a href="signup.php" class="link-sm">Create an account</a>
                    </p>
                </div>

                <p class="text-muted-xs mb-0 mt-3">
                    By continuing, you agree to StockMaster’s terms and acknowledge the privacy notice.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="forgotPasswordLabel">Reset password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <div id="forgotAlert" class="d-none alert alert-custom" role="alert"></div>

                <!-- Step indicator -->
                <div class="d-flex align-items-center mb-3">
                    <div class="step-label me-3">
                        <span class="step-badge active" id="stepBadge1">1</span>Email
                    </div>
                    <div class="step-label me-3">
                        <span class="step-badge" id="stepBadge2">2</span>OTP
                    </div>
                    <div class="step-label">
                        <span class="step-badge" id="stepBadge3">3</span>New password
                    </div>
                </div>

                <form id="forgotPasswordForm">
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="resetEmail" class="form-label">Registered email</label>
                        <input
                            type="email"
                            class="form-control"
                            id="resetEmail"
                            name="resetEmail"
                            placeholder="you@example.com"
                            required
                        >
                        <div class="form-text text-muted-xs">
                            We’ll send a one-time password (OTP) to this email.
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn btn-primary-gradient w-100 mb-3"
                        id="sendOtpBtn"
                    >
                        Send OTP
                    </button>

                    <!-- OTP & new password fields (hidden initially) -->
                    <div id="otpSection" class="d-none">
                        <div class="mb-3">
                            <label for="resetOtp" class="form-label">OTP</label>
                            <input
                                type="text"
                                class="form-control"
                                id="resetOtp"
                                name="resetOtp"
                                maxlength="6"
                                placeholder="Enter 6-digit code"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="newPassword"
                                name="newPassword"
                                placeholder="Choose a strong password"
                            >
                            <div class="form-text text-muted-xs">
                                Minimum 8 characters, with upper, lower and a special character.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm new password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="confirmPassword"
                                name="confirmPassword"
                                placeholder="Re-enter new password"
                            >
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary-gradient w-100"
                            id="resetPasswordBtn"
                        >
                            Reset password
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <p class="text-muted-xs mb-0">
                    If you remember your password, you can simply close this window and log in.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS + dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Adjust if login_api.php is in a different folder
    const API_BASE = 'login_api.php';

    // Utility to show alerts
    function showAlert(elementId, message, type = 'danger') {
        const el = document.getElementById(elementId);
        el.className = 'alert alert-' + type + ' alert-custom';
        el.textContent = message;
        el.classList.remove('d-none');
    }

    function hideAlert(elementId) {
        const el = document.getElementById(elementId);
        el.classList.add('d-none');
        el.textContent = '';
    }

    // Password show/hide
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    // Login form submit
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideAlert('loginAlert');

        const loginId = document.getElementById('loginId').value.trim();
        const password = document.getElementById('password').value;

        if (!loginId || !password) {
            showAlert('loginAlert', 'Please enter both Login ID and Password.', 'warning');
            return;
        }

        loginBtn.disabled = true;
        loginBtn.textContent = 'Logging in...';

        try {
            const response = await fetch(API_BASE + '?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ loginId, password })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                showAlert('loginAlert', data.error || 'Login failed. Please try again.', 'danger');
            } else {
                showAlert('loginAlert', 'Login successful. Redirecting...', 'success');
                // Redirect to dashboard.html after login
                setTimeout(() => {
                    window.location.href = 'dashboard.html';
                }, 800);
            }
        } catch (error) {
            console.error(error);
            showAlert('loginAlert', 'Something went wrong. Please try again.', 'danger');
        } finally {
            loginBtn.disabled = false;
            loginBtn.textContent = 'Log in';
        }
    });

    // Forgot password flow
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const forgotAlertId = 'forgotAlert';
    const otpSection = document.getElementById('otpSection');

    const stepBadge1 = document.getElementById('stepBadge1');
    const stepBadge2 = document.getElementById('stepBadge2');
    const stepBadge3 = document.getElementById('stepBadge3');

    function setStep(step) {
        [stepBadge1, stepBadge2, stepBadge3].forEach((badge, idx) => {
            if (idx + 1 === step) {
                badge.classList.add('active');
            } else {
                badge.classList.remove('active');
            }
        });
    }

    sendOtpBtn.addEventListener('click', async () => {
        hideAlert(forgotAlertId);

        const email = document.getElementById('resetEmail').value.trim();
        if (!email) {
            showAlert(forgotAlertId, 'Please enter your registered email.', 'warning');
            return;
        }

        sendOtpBtn.disabled = true;
        sendOtpBtn.textContent = 'Sending...';

        try {
            // Optional: check if email exists first
            const checkResp = await fetch(API_BASE + '?action=check-email-exists&email=' + encodeURIComponent(email));
            const checkData = await checkResp.json();

            if (!checkData.exists) {
                showAlert(forgotAlertId, 'This email is not registered.', 'danger');
                sendOtpBtn.disabled = false;
                sendOtpBtn.textContent = 'Send OTP';
                return;
            }

            // Send OTP
            const response = await fetch(API_BASE + '?action=send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                showAlert(forgotAlertId, data.error || 'Failed to send OTP. Please try again.', 'danger');
            } else {
                showAlert(forgotAlertId, 'OTP sent to your email. Check your inbox.', 'success');
                otpSection.classList.remove('d-none');
                setStep(2);
            }
        } catch (error) {
            console.error(error);
            showAlert(forgotAlertId, 'Something went wrong. Please try again.', 'danger');
        } finally {
            sendOtpBtn.disabled = false;
            sendOtpBtn.textContent = 'Send OTP';
        }
    });

    // Handle reset password submit
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const resetPasswordBtn = document.getElementById('resetPasswordBtn');

    forgotPasswordForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideAlert(forgotAlertId);

        const email = document.getElementById('resetEmail').value.trim();
        const otp = document.getElementById('resetOtp').value.trim();
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (!email || !otp || !newPassword || !confirmPassword) {
            showAlert(forgotAlertId, 'Please fill all the fields.', 'warning');
            return;
        }

        if (newPassword !== confirmPassword) {
            showAlert(forgotAlertId, 'New password and confirm password do not match.', 'warning');
            return;
        }

        resetPasswordBtn.disabled = true;
        resetPasswordBtn.textContent = 'Resetting...';

        try {
            const response = await fetch(API_BASE + '?action=reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, otp, newPassword })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                showAlert(forgotAlertId, data.error || 'Could not reset password.', 'danger');
            } else {
                setStep(3);
                showAlert(forgotAlertId, 'Password reset successfully. You can log in with your new password.', 'success');

                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
                    modal.hide();
                    otpSection.classList.add('d-none');
                    setStep(1);
                    forgotPasswordForm.reset();
                }, 1200);
            }
        } catch (error) {
            console.error(error);
            showAlert(forgotAlertId, 'Something went wrong. Please try again.', 'danger');
        } finally {
            resetPasswordBtn.disabled = false;
            resetPasswordBtn.textContent = 'Reset password';
        }
    });

    // Reset modal state when closed
    const forgotModalEl = document.getElementById('forgotPasswordModal');
    forgotModalEl.addEventListener('hidden.bs.modal', () => {
        hideAlert(forgotAlertId);
        otpSection.classList.add('d-none');
        setStep(1);
        forgotPasswordForm.reset();
    });
</script>
</body>
</html>
