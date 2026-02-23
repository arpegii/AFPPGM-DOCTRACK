<section x-data="{
    password: '',
    hasMinLength: false,
    hasUppercase: false,
    hasLowercase: false,
    hasNumber: false,
    
    validatePassword() {
        this.hasMinLength = this.password.length >= 8;
        this.hasUppercase = /[A-Z]/.test(this.password);
        this.hasLowercase = /[a-z]/.test(this.password);
        this.hasNumber = /[0-9]/.test(this.password);
    }
}">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_320px] lg:items-start">
        <!-- Left Side: Header and Form -->
        <div class="w-full">
            <header>
                <h2 class="text-lg font-semibold text-slate-900">
                    {{ __('Update Password') }}
                </h2>

                <p class="mt-1 text-sm text-slate-600">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </header>

            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('put')

                <div>
                    <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="update_password_password" :value="__('New Password')" />
                    <x-text-input 
                        id="update_password_password" 
                        name="password" 
                        type="password" 
                        class="mt-1 block w-full" 
                        autocomplete="new-password"
                        x-model="password"
                        @input="validatePassword()" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                    @if (session('status') === 'password-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600"
                        >{{ __('Saved.') }}</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Right Side: Password Requirements Box -->
        <div class="flex w-full flex-col items-start lg:items-center">
            <div class="w-full rounded-xl border border-slate-200 bg-slate-50 p-6 lg:max-w-[320px]">
                <h3 class="mb-5 text-base font-semibold text-slate-700">Password must contain:</h3>
                <div class="space-y-4">
                    <!-- At least 8 characters -->
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-6 h-6">
                            <svg x-show="hasMinLength" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="!hasMinLength" class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-base" :class="hasMinLength ? 'text-green-700 font-medium' : 'text-slate-600'">
                            At least 8 characters
                        </span>
                    </div>

                    <!-- One uppercase letter -->
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-6 h-6">
                            <svg x-show="hasUppercase" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="!hasUppercase" class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-base" :class="hasUppercase ? 'text-green-700 font-medium' : 'text-slate-600'">
                            One uppercase letter
                        </span>
                    </div>

                    <!-- One lowercase letter -->
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-6 h-6">
                            <svg x-show="hasLowercase" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="!hasLowercase" class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-base" :class="hasLowercase ? 'text-green-700 font-medium' : 'text-slate-600'">
                            One lowercase letter
                        </span>
                    </div>

                    <!-- One number -->
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-6 h-6">
                            <svg x-show="hasNumber" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="!hasNumber" class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-base" :class="hasNumber ? 'text-green-700 font-medium' : 'text-slate-600'">
                            One number
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
