@props(['icon' => 'inbox', 'title' => 'Tidak Ada Data', 'message' => 'Belum ada data yang dapat ditampilkan saat ini.'])

<div class="flex flex-col items-center justify-center p-12 text-center bg-surface-container-lowest rounded-xl border border-border-light border-dashed">
    <div class="w-16 h-16 bg-surface-container-high rounded-full flex items-center justify-center mb-4 text-outline">
        <span class="material-symbols-outlined text-4xl">{{ $icon }}</span>
    </div>
    <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">{{ $title }}</h3>
    <p class="text-body-sm text-on-surface-variant max-w-md">{{ $message }}</p>
    {{ $slot ?? '' }}
</div>
