<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>StockMaster – Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
    /* --- Theme Styles from Login Page --- */
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

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 1px rgba(129, 140, 248, 0.4);
        background-color: #ffffff;
    }

    /* Bootstrap Validation Styling Overrides */
    .form-control.is-valid {
        border-color: #198754;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linecap='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
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

    .password-toggle {
        position: absolute;
        right: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        color: #6b7280;
        display: flex;
        align-items: center;
    }

    /* Password Requirements Styling */
    .pwd-req-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-top: 0.75rem;
        background: #f8fafc;
        padding: 0.75rem;
        border-radius: 12px;
    }

    .req-item {
        font-size: 0.75rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: color 0.2s;
    }

    .req-item.met {
        color: #10b981;
        font-weight: 500;
    }

    .req-item svg {
        width: 14px;
        height: 14px;
        stroke-width: 2.5;
    }

    .alert-custom {
        border-radius: 12px;
        font-size: 0.85rem;
        display: none;
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
                        <a href="login.php" class="btn btn-outline-dark btn-outline-dark-rounded">
                            Sign In
                        </a>
                    </div>

                    <div class="mb-4">
                        <h1 class="auth-title mb-1">Create Account</h1>
                        <p class="auth-subtitle mb-0">
                            Join us and start managing your inventory efficiently.
                        </p>
                    </div>

                    <div id="alert" class="alert alert-custom" role="alert"></div>

                    <div class="mb-3">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label">Login ID <span class="text-danger">*</span></label>
                                <span id="loginIdCount" class="text-muted-xs">0/12</span>
                            </div>
                            <input type="text" id="loginId" class="form-control" placeholder="6-12 characters"
                                maxlength="12" autocomplete="off">
                            <div class="invalid-feedback" id="loginIdFeedback"></div>
                            <div class="valid-feedback">Login ID is available!</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="email" class="form-control" placeholder="name@company.com"
                                autocomplete="email">
                            <div class="invalid-feedback" id="emailFeedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="password" id="password" class="form-control"
                                    placeholder="Create strong password" style="padding-right: 45px;">
                                <button type="button" class="password-toggle" onclick="togglePwd()">
                                    <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>

                            <div class="pwd-req-grid">
                                <div class="req-item" id="r1">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                    <span>8+ characters</span>
                                </div>
                                <div class="req-item" id="r2">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                    <span>Lowercase (a-z)</span>
                                </div>
                                <div class="req-item" id="r3">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                    <span>Uppercase (A-Z)</span>
                                </div>
                                <div class="req-item" id="r4">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                    <span>Special (!@#$)</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check text-muted-xs">
                                <input class="form-check-input" type="checkbox" id="terms">
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="link-sm">Terms of Service</a> and <a href="#"
                                        class="link-sm">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button id="submitBtn" class="btn btn-primary-gradient" disabled onclick="submitForm()">
                                <span id="btnText">Create Account</span>
                            </button>
                        </div>
                    </div>

                    <p class="text-muted-xs mb-0 text-center mt-4">
                        Already have an account? <a href="login.php" class="link-sm">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
    // API URL - Points to your PHP file
    const API = 'api.php';

    // Get elements
    const loginIdEl = document.getElementById('loginId');
    const emailEl = document.getElementById('email');
    const passwordEl = document.getElementById('password');
    const termsEl = document.getElementById('terms');
    const submitBtn = document.getElementById('submitBtn');
    const alertEl = document.getElementById('alert');

    // Validation status
    let loginOk = false,
        emailOk = false,
        pwdOk = false;
    let timer;

    // =====================
    // SHOW ALERT
    // =====================
    function showAlert(msg, type) {
        // Map types to bootstrap alert classes
        const bsType = type === 'error' ? 'danger' : 'success';
        alertEl.textContent = msg;
        alertEl.className = 'alert alert-custom alert-' + bsType;
        alertEl.style.display = 'block';

        if (type === 'success') {
            setTimeout(() => alertEl.style.display = 'none', 5000);
        }
    }

    // =====================
    // TOGGLE PASSWORD
    // =====================
    function togglePwd() {
        const isPwd = passwordEl.type === 'password';
        passwordEl.type = isPwd ? 'text' : 'password';
        document.getElementById('eyeIcon').innerHTML = isPwd ?
            '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>' :
            '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
    }

    // =====================
    // UPDATE REQUIREMENT
    // =====================
    function updateReq(id, ok) {
        const el = document.getElementById(id);
        el.className = ok ? 'req-item met' : 'req-item';
        el.querySelector('svg').innerHTML = ok ?
            '<polyline points="20 6 9 17 4 12"/>' :
            '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>';
    }

    // =====================
    // CHECK FORM VALIDITY
    // =====================
    function checkForm() {
        const valid = loginOk && emailOk && pwdOk && termsEl.checked;
        submitBtn.disabled = !valid;
    }

    // =====================
    // LOGIN ID INPUT
    // =====================
    loginIdEl.addEventListener('input', function() {
        const val = this.value.trim();
        document.getElementById('loginIdCount').textContent = val.length + '/12';
        clearTimeout(timer);

        const feedback = document.getElementById('loginIdFeedback');

        // Reset Classes
        this.classList.remove('is-valid', 'is-invalid');

        if (val.length === 0) {
            loginOk = false;
            checkForm();
            return;
        }

        if (val.length < 6 || val.length > 12) {
            this.classList.add('is-invalid');
            feedback.textContent = 'Must be 6-12 characters';
            loginOk = false;
            checkForm();
            return;
        }

        // Check with database after 500ms
        timer = setTimeout(async () => {
            try {
                const res = await fetch(API + '?action=check-login-id&login_id=' +
                    encodeURIComponent(val));
                const data = await res.json();

                if (data.exists) {
                    this.classList.add('is-invalid');
                    feedback.textContent = 'This Login ID is already taken.';
                    loginOk = false;
                } else {
                    this.classList.add('is-valid');
                    loginOk = true;
                }
            } catch (e) {
                console.error(e);
                this.classList.add('is-invalid');
                feedback.textContent = 'Error checking availability.';
                loginOk = false;
            }
            checkForm();
        }, 500);
    });

    // =====================
    // EMAIL INPUT
    // =====================
    emailEl.addEventListener('input', function() {
        const val = this.value.trim();
        clearTimeout(timer);

        const feedback = document.getElementById('emailFeedback');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        this.classList.remove('is-valid', 'is-invalid');

        if (val.length === 0) {
            emailOk = false;
            checkForm();
            return;
        }

        if (!emailRegex.test(val)) {
            this.classList.add('is-invalid');
            feedback.textContent = 'Please enter a valid email address.';
            emailOk = false;
            checkForm();
            return;
        }

        // Check with database after 500ms
        timer = setTimeout(async () => {
            try {
                const res = await fetch(API + '?action=check-email&email=' + encodeURIComponent(
                    val));
                const data = await res.json();

                if (data.exists) {
                    this.classList.add('is-invalid');
                    feedback.textContent = 'This email is already registered.';
                    emailOk = false;
                } else {
                    this.classList.add('is-valid');
                    emailOk = true;
                }
            } catch (e) {
                this.classList.add('is-invalid');
                feedback.textContent = 'Error checking email.';
                emailOk = false;
            }
            checkForm();
        }, 500);
    });

    // =====================
    // PASSWORD INPUT
    // =====================
    passwordEl.addEventListener('input', function() {
        const val = this.value;

        const checks = {
            r1: val.length >= 8,
            r2: /[a-z]/.test(val),
            r3: /[A-Z]/.test(val),
            r4: /[!@#$%^&*(),.?":{}|<>]/.test(val)
        };

        updateReq('r1', checks.r1);
        updateReq('r2', checks.r2);
        updateReq('r3', checks.r3);
        updateReq('r4', checks.r4);

        pwdOk = checks.r1 && checks.r2 && checks.r3 && checks.r4;

        if (val.length > 0) {
            if (pwdOk) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }

        checkForm();
    });

    // =====================
    // TERMS CHECKBOX
    // =====================
    termsEl.addEventListener('change', checkForm);

    // =====================
    // SUBMIT FORM
    // =====================
    async function submitForm() {
        if (submitBtn.disabled) return;

        const btnText = document.getElementById('btnText');
        const originalText = btnText.textContent;

        btnText.textContent = 'Creating...';
        submitBtn.disabled = true;
        alertEl.style.display = 'none';

        try {
            const res = await fetch(API + '?action=register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    loginId: loginIdEl.value.trim(),
                    email: emailEl.value.trim(),
                    password: passwordEl.value
                })
            });

            const data = await res.json();

            if (data.success) {
                showAlert('🎉 Account created successfully! Redirecting...', 'success');

                // Clear form
                loginIdEl.value = '';
                emailEl.value = '';
                passwordEl.value = '';
                termsEl.checked = false;
                loginIdEl.classList.remove('is-valid');
                emailEl.classList.remove('is-valid');
                passwordEl.classList.remove('is-valid');

                // Redirect logic
                setTimeout(() => window.location.href = 'login.php', 2000);
            } else {
                showAlert(data.error || 'Registration failed', 'error');
                submitBtn.disabled = false;
                btnText.textContent = originalText;
            }
        } catch (e) {
            showAlert('Connection error! Please try again later.', 'error');
            submitBtn.disabled = false;
            btnText.textContent = originalText;
        }
    }
    </script>
</body>

</html>