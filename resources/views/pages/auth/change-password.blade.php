<x-default-layout>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Begin::Card -->
            <div class="card">
                <!-- Begin::Card Header -->
                <div class="card-header py-3">
                    <h3 class="card-title text-dark">{{ __('Settings') }}</h3>
                    <p class="text-muted fs-7 mb-0">{{ __('Manage your account settings and update your information.') }}</p>
                </div>
                <!-- End::Card Header -->

                <!-- Begin::Card Body -->
                <div class="card-body">
                    <form action="{{ route('update-settings') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Email Section -->
                        <div class="mb-10">
                            <h4 class="mb-5">{{ __('Email Settings') }}</h4>
                            
                            <!-- Current Email (Read-only) -->
                            <div class="mb-7">
                                <label class="form-label fw-bold text-dark">{{ __('Current Email') }}</label>
                                <input type="text" 
                                       class="form-control bg-light" 
                                       value="{{ auth()->user()->email }}" 
                                       readonly>
                                <div class="form-text">Your current registered email address</div>
                            </div>

                            <!-- New Email -->
                            <div class="mb-7">
                                <label class="form-label fw-bold text-dark">{{ __('New Email') }}</label>
                                <input type="email" 
                                       name="email" 
                                       id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="{{ __('Enter new email address') }}">
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">Leave blank if you don't want to change your email</div>
                            </div>
                        </div>

                        <div class="separator separator-dashed my-10"></div>

                        <!-- Password Section -->
                        <div class="mb-10">
                            <h4 class="mb-5">{{ __('Password Settings') }}</h4>

                            <!-- Begin::Old Password -->
                            <div class="mb-7">
                                <label class="form-label fw-bold text-dark">{{ __('Old Password') }}</label>
                                <div class="position-relative">
                                    <input type="password" name="old_password" id="old_password"
                                        class="form-control @error('old_password') is-invalid @enderror"
                                        placeholder="{{ __('Enter your old password') }}">
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        onclick="togglePassword('old_password')">
                                        <i class="bi bi-eye-slash fs-2" id="eye-slash-old"></i>
                                        <i class="bi bi-eye fs-2 d-none" id="eye-old"></i>
                                    </span>
                                </div>
                                @error('old_password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- End::Old Password -->

                            <!-- Begin::New Password -->
                            <div class="mb-7">
                                <label class="form-label fw-bold text-dark">{{ __('New Password') }}</label>
                                <div class="position-relative">
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="{{ __('Enter new password') }}">
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        onclick="togglePassword('password')">
                                        <i class="bi bi-eye-slash fs-2" id="eye-slash-new"></i>
                                        <i class="bi bi-eye fs-2 d-none" id="eye-new"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">Leave blank if you don't want to change your password</div>
                            </div>
                            <!-- End::New Password -->

                            <!-- Begin::Confirm Password -->
                            <div class="mb-7">
                                <label class="form-label fw-bold text-dark">{{ __('Confirm Password') }}</label>
                                <div class="position-relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        placeholder="{{ __('Confirm new password') }}">
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye-slash fs-2" id="eye-slash-confirm"></i>
                                        <i class="bi bi-eye fs-2 d-none" id="eye-confirm"></i>
                                    </span>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- End::Confirm Password -->
                        </div>

                        <!-- Begin::Submit -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="javascript:history.back()" class="btn btn-light btn-sm">
                                <i class="ki-duotone ki-arrow-left fs-3 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('Back') }}
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ki-duotone ki-check fs-3 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                        <!-- End::Submit -->
                    </form>
                </div>
                <!-- End::Card Body -->
            </div>
            <!-- End::Card -->
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            let eyeSlash, eye;
            
            if (fieldId === 'old_password') {
                eyeSlash = document.getElementById('eye-slash-old');
                eye = document.getElementById('eye-old');
            } else if (fieldId === 'password') {
                eyeSlash = document.getElementById('eye-slash-new');
                eye = document.getElementById('eye-new');
            } else if (fieldId === 'password_confirmation') {
                eyeSlash = document.getElementById('eye-slash-confirm');
                eye = document.getElementById('eye-confirm');
            }
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeSlash.classList.add('d-none');
                eye.classList.remove('d-none');
            } else {
                passwordInput.type = 'password';
                eyeSlash.classList.remove('d-none');
                eye.classList.add('d-none');
            }
        }

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const emailInput = document.getElementById('email');
            const oldPasswordInput = document.getElementById('old_password');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');

            form.addEventListener('submit', function(e) {
                let isValid = true;
                let errorMessage = '';

                // Clear previous errors
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                // Email validation
                if (emailInput.value.trim() !== '') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailInput.value)) {
                        isValid = false;
                        showError(emailInput, 'Please enter a valid email address');
                    }
                }

                // Password validation
                const hasOldPassword = oldPasswordInput.value.trim() !== '';
                const hasNewPassword = passwordInput.value.trim() !== '';
                const hasConfirmPassword = passwordConfirmInput.value.trim() !== '';

                // If any password field is filled, all must be filled
                if (hasOldPassword || hasNewPassword || hasConfirmPassword) {
                    if (!hasOldPassword) {
                        isValid = false;
                        showError(oldPasswordInput, 'Old password is required when changing password');
                    }
                    
                    if (!hasNewPassword) {
                        isValid = false;
                        showError(passwordInput, 'New password is required');
                    } else {
                        // Validate password strength
                        if (passwordInput.value.length < 8) {
                            isValid = false;
                            showError(passwordInput, 'Password must be at least 8 characters');
                        } else if (!/[a-zA-Z]/.test(passwordInput.value)) {
                            isValid = false;
                            showError(passwordInput, 'Password must contain at least one letter');
                        } else if (!/[0-9]/.test(passwordInput.value)) {
                            isValid = false;
                            showError(passwordInput, 'Password must contain at least one number');
                        } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(passwordInput.value)) {
                            isValid = false;
                            showError(passwordInput, 'Password must contain at least one symbol');
                        }
                    }
                    
                    if (!hasConfirmPassword) {
                        isValid = false;
                        showError(passwordConfirmInput, 'Please confirm your new password');
                    } else if (passwordInput.value !== passwordConfirmInput.value) {
                        isValid = false;
                        showError(passwordConfirmInput, 'Passwords do not match');
                    }
                }

                // Check if at least one field is filled
                if (emailInput.value.trim() === '' && !hasOldPassword && !hasNewPassword && !hasConfirmPassword) {
                    isValid = false;
                    alert('Please fill in at least one field to update your settings');
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            function showError(input, message) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.textContent = message;
                input.parentElement.appendChild(errorDiv);
            }

            // Real-time email validation
            emailInput.addEventListener('blur', function() {
                if (this.value.trim() !== '') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(this.value)) {
                        this.classList.add('is-invalid');
                        if (!this.parentElement.querySelector('.invalid-feedback')) {
                            showError(this, 'Please enter a valid email address');
                        }
                    } else {
                        this.classList.remove('is-invalid');
                        const errorDiv = this.parentElement.querySelector('.invalid-feedback');
                        if (errorDiv) errorDiv.remove();
                    }
                }
            });

            // Real-time password match validation
            passwordConfirmInput.addEventListener('input', function() {
                if (this.value !== '' && passwordInput.value !== '') {
                    if (this.value !== passwordInput.value) {
                        this.classList.add('is-invalid');
                        const existingError = this.parentElement.querySelector('.invalid-feedback');
                        if (existingError) existingError.remove();
                        showError(this, 'Passwords do not match');
                    } else {
                        this.classList.remove('is-invalid');
                        const errorDiv = this.parentElement.querySelector('.invalid-feedback');
                        if (errorDiv) errorDiv.remove();
                    }
                }
            });
        });
    </script>
    @endpush
</x-default-layout>
