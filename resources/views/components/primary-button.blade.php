<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg font-label-md font-bold hover:bg-primary-container focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all shadow-md active:scale-[0.98]']) }}>
    {{ $slot }}
</button>
