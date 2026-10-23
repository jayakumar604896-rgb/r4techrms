@extends('layouts.app')

@section('title', 'Create Receipt')

@section('content')
<style>
    .page-header h3 {
        font-weight: 700;
        font-size: 26px;
        color: #3A3A8B;
    }
    .card {
        border-radius: 14px;
        border: none;
        padding-top: 10px;
        background: #ffffff;
        box-shadow: 0px 8px 30px rgba(0,0,0,0.06);
    }
    .card-title {
        font-weight: 700;
        font-size: 22px;
        padding-left: 15px;
        border-left: 4px solid #6C63FF;
        color: #2b2b2b;
        margin-bottom: 25px;
    }
    label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    .form-control,
    .form-select {
        padding: 0.80rem 1rem !important;
        height: 46px !important;
        border-radius: 8px !important;
        border: 1px solid #d4d4d4 !important;
        font-size: 0.95rem !important;
        background-color: #fff !important;
        transition: all .2s ease-in-out;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: #6C63FF !important;
        box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25) !important;
    }
    textarea.form-control {
        height: auto !important;
        min-height: 100px;
    }
    .btn-primary {
        background: linear-gradient(90deg, #4B49AC, #6C63FF) !important;
        border: none !important;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: .3px;
        transition: .2s;
    }
    .btn-primary:hover {
        opacity: .9;
        transform: scale(1.03);
    }
    .btn-light {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        background: #162734;
    }
    .required-star {
        color: #dc3545;
    }
    .is-invalid {
        border-color: #dc3545 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
</style>

<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Create Receipt</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('receipts.index') }}">Receipts</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create Receipt</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Receipt Details</h4>

                    <!-- Success & Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-circle-outline me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-alert-circle-outline me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Start Form -->
                    <form action="{{ route('receipts.store') }}" method="POST" id="receiptForm">
                        @csrf
                        @include('receipts._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('receiptForm');
        const amountReceived = document.getElementById('amount_received');
        const totalAmountDue = document.getElementById('total_amount_due');
        const balanceDue = document.getElementById('balance_due');
        const mobileInput = document.getElementById('mobile_no');
        const emailInput = document.getElementById('email');

        // Auto-calculate balance due
        function calculateBalance() {
            const received = parseFloat(amountReceived.value) || 0;
            const total = parseFloat(totalAmountDue.value) || 0;
            balanceDue.value = (total - received).toFixed(2);
        }

        if (amountReceived && totalAmountDue) {
            amountReceived.addEventListener('input', calculateBalance);
            totalAmountDue.addEventListener('input', calculateBalance);
        }

        // Mobile number validation – only digits, max 15
        if (mobileInput) {
            mobileInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length > 15) {
                    this.value = this.value.slice(0, 15);
                }
            });
        }

        // Email validation on blur
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                if (this.value && !isValidEmail(this.value)) {
                    showError(this, 'Please enter a valid email address');
                } else {
                    clearError(this);
                }
            });
        }

        // Real-time validation removal on input
        form.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    const errorDiv = this.parentElement.querySelector('.invalid-feedback');
                    if (errorDiv) errorDiv.remove();
                }
            });
        });

        // Form submission validation
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let isValid = true;
            const errorMessages = [];

            // Check required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    const label = field.previousElementSibling?.textContent?.replace('*', '').trim() || field.name;
                    errorMessages.push(`${label} is required`);
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Validate mobile number (if not empty)
            if (mobileInput && mobileInput.value && !/^\d{10,15}$/.test(mobileInput.value)) {
                isValid = false;
                showError(mobileInput, 'Mobile number must be 10-15 digits');
                errorMessages.push('Mobile number must be 10-15 digits');
            }

            // Validate email format if present
            if (emailInput && emailInput.value && !isValidEmail(emailInput.value)) {
                isValid = false;
                showError(emailInput, 'Invalid email format');
                errorMessages.push('Email address is invalid');
            }

            // Validate amount fields
            if (parseFloat(amountReceived.value) < 0) {
                isValid = false;
                showError(amountReceived, 'Amount cannot be negative');
                errorMessages.push('Amount received cannot be negative');
            }
            if (parseFloat(totalAmountDue.value) < 0) {
                isValid = false;
                showError(totalAmountDue, 'Total amount due cannot be negative');
                errorMessages.push('Total amount due cannot be negative');
            }

            if (isValid) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Saving...';
                submitBtn.disabled = true;
                form.submit();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Form Validation Error',
                    html: '<div class="text-start"><p>Please fix the following errors:</p><ul class="mb-0"><li>' + 
                          errorMessages.join('</li><li>') + 
                          '</li></ul></div>',
                    confirmButtonColor: '#6C63FF'
                });
            }
        });

        // Helper functions
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function showError(input, message) {
            input.classList.add('is-invalid');
            let errorDiv = input.parentElement.querySelector('.invalid-feedback');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                input.parentElement.appendChild(errorDiv);
            }
            errorDiv.textContent = message;
        }

        function clearError(input) {
            input.classList.remove('is-invalid');
            const errorDiv = input.parentElement.querySelector('.invalid-feedback');
            if (errorDiv) errorDiv.remove();
        }
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif
@endsection