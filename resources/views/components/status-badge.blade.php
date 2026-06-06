@props(['status'])

@php
    $classes = match($status) {
        'Selesai', 'Terverifikasi' => 'bg-success-green/10 text-success-green border-success-green/20',
        'Terjadwal', 'Menunggu' => 'bg-warning-orange/10 text-warning-orange border-warning-orange/20',
        'Batal', 'Ditolak' => 'bg-error/10 text-error border-error/20',
        default => 'bg-surface-variant text-on-surface-variant border-outline-variant',
    };
    
    $icon = match($status) {
        'Selesai', 'Terverifikasi' => 'check_circle',
        'Terjadwal', 'Menunggu' => 'schedule',
        'Batal', 'Ditolak' => 'cancel',
        default => 'info',
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-label-sm font-bold border {{ $classes }}">
    <span class="material-symbols-outlined text-[14px]" {{ in_array($status, ['Selesai', 'Terverifikasi']) ? 'style="font-variation-settings: \'FILL\' 1;"' : '' }}>{{ $icon }}</span>
    {{ $status }}
</span>
