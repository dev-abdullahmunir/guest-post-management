<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold tracking-wide ' . match($variant ?? 'default') {
    'success' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
    'warning' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
    'danger' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
    'info' => 'bg-sky-500/10 text-sky-400 border border-sky-500/20',
    'purple' => 'bg-purple-500/10 text-purple-400 border border-purple-500/20',
    default => 'bg-slate-800 text-slate-300 border border-slate-700'
}]) }}>
    @if(isset($dot) && $dot)
        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ match($variant ?? 'default') {
            'success' => 'bg-emerald-400',
            'warning' => 'bg-amber-400',
            'danger' => 'bg-rose-400',
            'info' => 'bg-sky-400',
            default => 'bg-slate-400'
        } }}"></span>
    @endif
    {{ $slot }}
</span>
