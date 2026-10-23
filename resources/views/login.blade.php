<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RMS-Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

    <style>
        /* Ensure the toggle button blends nicely */
        #togglePassword {
            cursor: pointer;
            transition: opacity 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #togglePassword:hover {
            opacity: 0.7;
        }
        /* Make the input group seamless */
        .input-group .form-control.border-left-0.border-right-0 {
            border-left: none;
            border-right: none;
        }
        .input-group .input-group-text.bg-transparent {
            background: transparent !important;
        }
        /* Ensure SVG icon inherits primary colour */
        .text-primary svg {
            stroke: #4a6cf7; /* or your primary colour */
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
                <div class="row flex-grow">
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="auth-form-transparent text-left p-3">
                            <div class="brand-logo">
                                <img src="{{ asset('assets/images/rms_logo.png') }}" alt="logo">
                            </div>
                            <h4>Welcome back!</h4>
                            <h6 class="font-weight-light">Happy to see you again!</h6>

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                                @endforeach
                            </div>
                            @endif

                            @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif

                            <form id="formAuthentication" class="pt-3" action="{{ route('login-submit') }}" method="POST">
                                @csrf

                                <!-- Email Field -->
                                <div class="form-group">
                                    <label for="exampleInputEmail">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="icon-user text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="email" name="email"
                                        class="form-control form-control-lg border-left-0"
                                        id="exampleInputEmail"
                                        value="{{ old('username', Cookie::get('username')) }}"
                                        placeholder="Email">
                                    </div>
                                </div>

                                <!-- Password Field with SVG Toggle -->
                                <div class="form-group">
                                    <label for="exampleInputPassword">Password</label>
                                    <div class="input-group">
                                        <!-- Left icon (lock) -->
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="icon-lock text-primary"></i>
                                            </span>
                                        </div>

                                        <!-- Password input -->
                                        <input type="password"
                                        name="password"
                                        class="form-control form-control-lg border-left-0 border-right-0"
                                        id="exampleInputPassword"
                                        placeholder="Password">

                                        <!-- Right toggle with inline SVG -->
                                        <div class="input-group-append bg-transparent">
                                            <span class="input-group-text bg-transparent border-left-0"
                                                  id="togglePassword"
                                                  role="button"
                                                  aria-label="Toggle password visibility"
                                                  tabindex="0">
                                                <!-- SVG container for eye icon -->
                                                <span id="eyeIcon" class="text-primary">
                                                    <!-- Initially "eye-off" (hidden) SVG -->
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                                    </svg>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Forgot password -->
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <a href="#" class="auth-link text-black">Forgot password?</a>
                                </div>

                                <!-- Submit button -->
                                <div class="my-3">
                                    <button id="submitBtn"
                                    class="w-100 btn d-grid btn-primary btn-lg font-weight-medium auth-form-btn">
                                    <span id="btnText">Sign in</span>
                                    <span id="btnLoader"
                                    class="spinner-border spinner-border-sm ms-2 d-none"
                                    role="status"
                                    aria-hidden="true"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 login-half-bg d-flex flex-row">
                    <p class="text-white font-weight-medium text-center flex-grow align-self-end">
                        Copyright © 2025 All rights reserved.
                    </p>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->

<!-- plugins:js -->
<script src="{{ asset('assets/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<!-- endinject -->

<!-- ============================================ -->
<!-- PASSWORD TOGGLE SCRIPT (SVG version)        -->
<!-- ============================================ -->
<script>
    (function() {
        'use strict';

        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('exampleInputPassword');
        const eyeIconContainer = document.getElementById('eyeIcon');

        if (toggleBtn && passwordInput && eyeIconContainer) {
            // Define the two SVG icons as strings
            const eyeOffSVG = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            </svg>`;

            const eyeOnSVG = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>`;

            // Initially set the icon to eye-off (password hidden)
            eyeIconContainer.innerHTML = eyeOffSVG;

            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const isPassword = passwordInput.type === 'password';
                // Toggle input type
                passwordInput.type = isPassword ? 'text' : 'password';
                // Swap SVG
                eyeIconContainer.innerHTML = isPassword ? eyeOnSVG : eyeOffSVG;

                passwordInput.focus();
            });

            // Allow keyboard activation (Enter / Space)
            toggleBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleBtn.click();
                }
            });
        }

        // ---- Form submission loader (unchanged) ----
        const form = document.getElementById('formAuthentication');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = document.getElementById('submitBtn');
                const text = document.getElementById('btnText');
                const loader = document.getElementById('btnLoader');
                if (btn && text && loader) {
                    btn.disabled = true;
                    text.textContent = '';
                    loader.classList.remove('d-none');
                }
            });
        }
    })();
</script>

</body>
</html>