<div {{ $attributes->merge(['class' => 'rounded-lg p-4 text-sm flex items-start gap-3 ' . match($type ?? 'info') {
    'success' => 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/20',
    'warning' => 'bg-amber-500/10 text-amber-300 border border-amber-500/20',
    'danger' => 'bg-rose-500/10 text-rose-300 border border-rose-500/20',
    default => 'bg-indigo-500/10 text-indigo-300 border border-indigo-500/20'
}]) }}>
    <div class="text-lg">
        <i class="{{ match($type ?? 'info') {
            'success' => 'fas fa-check-circle text-emerald-400',
            'warning' => 'fas fa-exclamation-triangle text-amber-400',
            'danger' => 'fas fa-times-circle text-rose-400',
            default => 'fas fa-info-circle text-indigo-400'
        } }}"></i>
    </div>
    <div class="flex-1">
        @if(isset($title))
            <h4 class="font-semibold mb-0.5">{{ $title }}</h4>
        @endif
        <div class="opacity-90">{{ $message ?? $slot }}</div>
    </div>
</div>
