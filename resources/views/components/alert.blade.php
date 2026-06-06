@if (session('success'))
    <div class="mb-6 p-4 bg-success-green/10 border border-success-green/20 rounded-xl flex items-start gap-3">
        <span class="material-symbols-outlined text-success-green" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        <div class="flex-1">
            <h4 class="text-label-md font-bold text-success-green">Berhasil</h4>
            <p class="text-body-sm text-success-green/80">{{ session('success') }}</p>
        </div>
        <button class="text-success-green/50 hover:text-success-green transition-colors" onclick="this.parentElement.remove()">
            <span class="material-symbols-outlined text-sm">close</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-xl flex items-start gap-3">
        <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1;">error</span>
        <div class="flex-1">
            <h4 class="text-label-md font-bold text-error">Gagal</h4>
            <p class="text-body-sm text-error/80">{{ session('error') }}</p>
        </div>
        <button class="text-error/50 hover:text-error transition-colors" onclick="this.parentElement.remove()">
            <span class="material-symbols-outlined text-sm">close</span>
        </button>
    </div>
@endif
