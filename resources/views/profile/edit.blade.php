<x-app-layout>
    <div class="py-8 sm:py-10">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:px-8">
            <section class="page-hero">
                <div>
                    <h1 class="page-title">Profile Settings</h1>
                    <p class="page-subtitle">Manage your account details, password, and security preferences in one place.</p>
                </div>
            </section>

            <div class="panel-surface p-4 sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="panel-surface p-4 sm:p-8">
                @include('profile.partials.update-password-form')
            </div>

            <div class="panel-surface p-4 sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
