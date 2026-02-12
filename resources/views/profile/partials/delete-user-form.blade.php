<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6" id="delete-account-form" onsubmit="handleDeleteSubmit(event)">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" type="submit">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

<!-- Account Deleting Modal (Processing) -->
<div id="deleting-modal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-content">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center" style="width: 80px; height: 80px;">
                    <div class="loader"></div>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-3 mt-4">Deleting Account...</h3>
                <p class="text-base text-gray-600">
                    Please wait while we process your request.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Account Deleted Success Modal -->
<div id="deleted-success-modal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-content">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center rounded-full bg-green-100" style="width: 80px; height: 80px;">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" style="width: 64px; height: 64px;">
                        <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-3 mt-4">Account Deleted</h3>
                <p class="text-base text-gray-600">
                    Your account has been successfully deleted. Redirecting to login...
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal overlay styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 99999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex !important;
    }

    .modal-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        padding: 1rem;
    }

    .modal-content {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        max-width: 28rem;
        width: 100%;
        padding: 2rem;
        position: relative;
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* Improved Spinner */
    .loader {
        width: 48px;
        height: 48px;
        border: 5px solid #FFF;
        border-bottom-color: #dc2626;
        border-radius: 50%;
        display: inline-block;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }

    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    /* Checkmark styles */
    .checkmark {
        stroke-width: 2;
        stroke: #10b981;
        stroke-miterlimit: 10;
    }

    .checkmark-circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #10b981;
        fill: none;
        animation: strokeCircle 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark-check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        stroke: #10b981;
        stroke-width: 3;
        animation: strokeCheck 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    @keyframes strokeCircle {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes strokeCheck {
        100% {
            stroke-dashoffset: 0;
        }
    }
</style>

<script>
    // Handle delete form submission
    function handleDeleteSubmit(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const deletingModal = document.getElementById('deleting-modal');
        const deletedSuccessModal = document.getElementById('deleted-success-modal');

        // Close Alpine modal by dispatching close event
        const closeEvent = new CustomEvent('close-modal', { detail: 'confirm-user-deletion' });
        window.dispatchEvent(closeEvent);

        // Small delay to ensure Alpine modal closes first
        setTimeout(() => {
            // Show deleting modal
            deletingModal.style.display = 'flex';
            deletingModal.classList.add('show');
        }, 100);

        // Submit delete request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                // Hide deleting modal
                deletingModal.style.display = 'none';
                deletingModal.classList.remove('show');

                // Show success modal
                deletedSuccessModal.style.display = 'flex';
                deletedSuccessModal.classList.add('show');

                // Redirect to login after 3 seconds
                setTimeout(function() {
                    window.location.href = '{{ route("login") }}';
                }, 3000);
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to delete account.');
                });
            }
        })
        .catch(error => {
            console.error('Error deleting account:', error);
            
            // Hide deleting modal
            deletingModal.style.display = 'none';
            deletingModal.classList.remove('show');
            
            // Show error alert
            alert(error.message || 'An error occurred. Please check your password and try again.');
            
            // Reopen the Alpine modal
            const openEvent = new CustomEvent('open-modal', { detail: 'confirm-user-deletion' });
            window.dispatchEvent(openEvent);
        });

        return false;
    }

    // Prevent closing modal during processing
    document.addEventListener('DOMContentLoaded', function() {
        const deletingModal = document.getElementById('deleting-modal');
        const deletedSuccessModal = document.getElementById('deleted-success-modal');
        
        if (deletingModal) {
            deletingModal.addEventListener('click', function(e) {
                // Only prevent if clicking the overlay itself
                if (e.target === deletingModal || e.target === deletingModal.querySelector('.modal-container')) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        }

        if (deletedSuccessModal) {
            deletedSuccessModal.addEventListener('click', function(e) {
                // Prevent closing success modal
                if (e.target === deletedSuccessModal || e.target === deletedSuccessModal.querySelector('.modal-container')) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        }
    });
</script>