<x-app-layout>
    <div class="mb-8">
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">Pengaturan Profil</h2>
        <p class="text-on-surface-variant text-body-sm mt-1">Kelola informasi akun dan pengaturan keamanan Anda.</p>
    </div>

    <div class="space-y-6">
        <div class="p-6 bg-surface-container-lowest rounded-xl border border-border-light shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 bg-surface-container-lowest rounded-xl border border-border-light shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 bg-surface-container-lowest rounded-xl border border-border-light shadow-sm border-l-4 border-l-danger-red">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
