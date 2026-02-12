<!-- LOGOUT CONFIRMATION MODAL -->
<div x-data="{ showLogoutModal: false }" 
     @open-logout-modal.window="showLogoutModal = true">
    
    <!-- Modal Backdrop -->
    <div x-show="showLogoutModal" 
         x-cloak
         class="fixed inset-0 z-[10000] flex items-center justify-center p-4" 
         style="background-color: rgba(11, 31, 58, 0.75); backdrop-filter: blur(8px);"
         @click.self="showLogoutModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <!-- Modal Card -->
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <!-- Header with blue gradient -->
            <div class="px-6 py-4 rounded-t-2xl border-b" style="background: linear-gradient(to bottom right, #dbeafe, #bfdbfe); border-color: #93c5fd;">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #dbeafe;">
                            <svg class="w-6 h-6" style="color: #1d4ed8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold" style="color: #111827;">Confirm Logout</h3>
                        <p class="text-sm" style="color: #6b7280;">Are you sure you want to sign out?</p>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-5">
                <p class="text-sm" style="color: #374151;">
                    You will be logged out of your current session. Any unsaved changes may be lost.
                </p>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 rounded-b-2xl" style="background-color: #f9fafb; border-top: 1px solid #e5e7eb;">
                <div class="flex items-center justify-end gap-3">
                    <button @click="showLogoutModal = false"
                            type="button"
                            class="px-5 py-2.5 rounded-lg transition-all font-semibold text-sm"
                            style="background-color: white; border: 2px solid #d1d5db; color: #374151;"
                            onmouseover="this.style.backgroundColor='#f9fafb'; this.style.borderColor='#9ca3af';"
                            onmouseout="this.style.backgroundColor='white'; this.style.borderColor='#d1d5db';">
                        Cancel
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2.5 rounded-lg transition-all font-semibold text-sm shadow-md"
                                style="background-color: #0B1F3A; color: white;"
                                onmouseover="this.style.backgroundColor='#1e40af';"
                                onmouseout="this.style.backgroundColor='#0B1F3A';">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { 
        display: none !important; 
    }
</style>