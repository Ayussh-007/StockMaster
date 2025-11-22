<?php
// login.php - StockMaster Login UI (Light Mode)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>StockMaster – Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        background: #f3f4f6;
        min-height: 100vh;
    }

    .auth-wrapper {
        min-height: 100vh;
        padding: 2rem 0;
    }

    .auth-card {
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, 0.25);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(148, 163, 184, 0.12);
        background: #ffffff;
        color: #111827;
    }

    /* Branding */
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

    /* Form Elements */
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

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 1px rgba(129, 140, 248, 0.4);
        background-color: #ffffff;
    }

    /* Buttons */
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
        transition: all 0.2s;
    }

    .btn-primary-gradient:hover:not(:disabled) {
        filter: brightness(1.05);
        transform: translateY(-1px);
    }

    .btn-primary-gradient:disabled {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .btn-outline-dark-rounded {
        border-radius: 999px;
        border-width: 1px;
        padding: 0.5rem 1rem;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    /* Links & Utilities */
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

    /* Password Toggle Button */
    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        padding: 5px;
        cursor: pointer;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle:hover {
        color: #4b5563;
    }

    .alert-custom {
        border-radius: 12px;
        font-size: 0.85rem;
    }

    /* Modal Specifics */
    .modal-content {
        border-radius: 18px;
        border: none;
    }

    .step-badge {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        border: 1px solid #cbd5e1;
        margin-right: 5px;
        background: #f8fafc;
        color: #64748b;
    }

    .step-badge.active {
        border-color: #6366f1;
        background: #6366f1;
        color: white;
    }
    </style>
</head>

<body>
    <div class="container auth-wrapper d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="auth-card p-4 p-md-5">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="brand-badge">
                            <span class="brand-icon">SM</span>
                            <span class="brand-title">StockMaster</span>
                        </div>
                        <a href="signup.php" class="btn btn-outline-dark btn-outline-dark-rounded">
                            Sign Up
                        </a>
                    </div>

                    <div class="mb-4">
                        <h1 class="auth-title mb-1">Welcome back</h1>
                        <p class="auth-subtitle mb-0">
                            Sign in to manage inventory and track stock.
                        </p>
                    </div>

                    <div id="loginAlert" class="d-none alert alert-custom" role="alert"></div>

                    <form id="loginForm" class="mb-3">
                        <div class="mb-3">
                            <label for="loginId" class="form-label">Login ID</label>
                            <input type="text" class="form-control" id="loginId" placeholder="Enter your login ID"
                                autocomplete="username" required>
                        </div>

                        <div class="mb-2 position-relative">
                            <label for="password" class="form-label">Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="password"
                                    placeholder="Enter your password" style="padding-right: 45px;"
                                    autocomplete="current-password" required>

                                <button type="button" class="password-toggle" id="togglePasswordBtn">
                                    <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check text-muted-xs">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">Remember me</label>
                            </div>
                            <button type="button" class="btn btn-link link-sm p-0 border-0" data-bs-toggle="modal"
                                data-bs-target="#forgotPasswordModal">
                                Forgot password?
                            </button>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary-gradient" id="loginBtn">
                                Log In
                            </button>
                        </div>
                    </form>

                    <div class="mb-3">
                        <div class="divider mb-3">Or</div>
                        <p class="text-muted-xs mb-0 text-center">
                            New to StockMaster? <a href="signup.php" class="link-sm">Create an account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="forgotAlert" class="d-none alert alert-custom" role="alert"></div>

                    <div class="d-flex align-items-center mb-4">
                        <div class="text-muted-xs me-3"><span class="step-badge active" id="stepBadge1">1</span>Email
                        </div>
                        <div class="text-muted-xs me-3"><span class="step-badge" id="stepBadge2">2</span>OTP</div>
                        <div class="text-muted-xs"><span class="step-badge" id="stepBadge3">3</span>New Pwd</div>
                    </div>

                    <form id="forgotPasswordForm">
                        <div class="mb-3">
                            <label class="form-label">Registered Email</label>
                            <input type="email" class="form-control" id="resetEmail" placeholder="you@example.com"
                                required>
                        </div>

                        <button type="button" class="btn btn-primary-gradient w-100 mb-3" id="sendOtpBtn">Send
                            OTP</button>

                        <div id="otpSection" class="d-none">
                            <div class="mb-3">
                                <label class="form-label">OTP</label>
                                <input type="text" class="form-control" id="resetOtp" maxlength="6"
                                    placeholder="6-digit code">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword"
                                    placeholder="Strong password">
                            </div>
                            <button type="submit" class="btn btn-primary-gradient w-100" id="resetPasswordBtn">Reset
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    const API_BASE = 'api.php'; // Ensure this matches your Sign Up API file

    // ===========================
    // UI UTILITIES
    // ===========================
    function showAlert(elId, msg, type = 'danger') {
        const el = document.getElementById(elId);
        el.className = `alert alert-custom alert-${type}`;
        el.textContent = msg;
        el.classList.remove('d-none');
    }

    function hideAlert(elId) {
        document.getElementById(elId).classList.add('d-none');
    }

    // ===========================
    // PASSWORD TOGGLE (FIXED)
    // ===========================
    document.getElementById('togglePasswordBtn').addEventListener('click', function() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        const isPassword = input.type === 'password';

        input.type = isPassword ? 'text' : 'password';

        // SVG Path Swap
        icon.innerHTML = isPassword ?
            '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>' :
            '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
    });

    // ===========================
    // LOGIN LOGIC
    // ===========================
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        hideAlert('loginAlert');

        const loginId = document.getElementById('loginId').value.trim();
        const password = document.getElementById('password').value;
        const btn = document.getElementById('loginBtn');
        const originalText = btn.textContent;

        if (!loginId || !password) return;

        btn.disabled = true;
        btn.textContent = 'Logging in...';

        try {
            const res = await fetch(API_BASE + '?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    loginId,
                    password
                })
            });

            const data = await res.json();

            if (data.success) {
                showAlert('loginAlert', 'Success! Redirecting...', 'success');
                localStorage.setItem('user', JSON.stringify(data.user));
                setTimeout(() => window.location.href = 'dashboard.php', 1000);
            } else {
                showAlert('loginAlert', data.error || 'Invalid credentials');
                btn.disabled = false;
                btn.textContent = originalText;
            }
        } catch (error) {
            showAlert('loginAlert', 'Connection error. Please try again.');
            btn.disabled = false;
            btn.textContent = originalText;
        }
    });

    // ===========================
    // FORGOT PASSWORD LOGIC
    // ===========================
    const step1 = document.getElementById('stepBadge1');
    const step2 = document.getElementById('stepBadge2');
    const step3 = document.getElementById('stepBadge3');

    document.getElementById('sendOtpBtn').addEventListener('click', async function() {
        const email = document.getElementById('resetEmail').value.trim();
        if (!email) {
            showAlert('forgotAlert', 'Please enter your email', 'warning');
            return;
        }

        this.disabled = true;
        this.textContent = 'Sending...';

        try {
            // 1. Check Email & Send OTP
            const res = await fetch(API_BASE + '?action=send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email
                })
            });

            const data = await res.json();

            if (data.success) {
                showAlert('forgotAlert', 'OTP sent to email!', 'success');
                document.getElementById('otpSection').classList.remove('d-none');
                step1.classList.remove('active');
                step2.classList.add('active');
                this.classList.add('d-none'); // Hide send button
            } else {
                showAlert('forgotAlert', data.error || 'Email not found');
                this.disabled = false;
                this.textContent = 'Send OTP';
            }
        } catch (e) {
            showAlert('forgotAlert', 'Error sending OTP');
            this.disabled = false;
            this.textContent = 'Send OTP';
        }
    });

    document.getElementById('forgotPasswordForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('resetEmail').value;
        const otp = document.getElementById('resetOtp').value;
        const newPass = document.getElementById('newPassword').value;
        const btn = document.getElementById('resetPasswordBtn');

        btn.disabled = true;
        btn.textContent = 'Updating...';

        try {
            const res = await fetch(API_BASE + '?action=reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email,
                    otp,
                    newPassword: newPass
                })
            });

            const data = await res.json();

            if (data.success) {
                step2.classList.remove('active');
                step3.classList.add('active');
                showAlert('forgotAlert', 'Password updated! Login now.', 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showAlert('forgotAlert', data.error || 'Invalid OTP');
                btn.disabled = false;
                btn.textContent = 'Reset Password';
            }
        } catch (e) {
            showAlert('forgotAlert', 'Connection error');
            btn.disabled = false;
            btn.textContent = 'Reset Password';
        }
    });
    </script>
</body>

</html>