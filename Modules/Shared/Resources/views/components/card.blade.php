<div {{ $attributes->merge(['class' => 'rounded-xl bg-slate-900/60 border border-slate-800 shadow-sm backdrop-blur-sm overflow-hidden']) }}>
    @if(isset($title) || isset($headerAction))
        <div class="px-5 py-4 border-b border-slate-800/80 flex items-center justify-between">
            <div>
                @if(isset($title))
                    <h3 class="text-base font-semibold text-slate-100">{{ $title }}</h3>
                @endif
                @if(isset($subtitle))
                    <p class="text-xs text-slate-400 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($headerAction))
                <div>{{ $headerAction }}</div>
            @endif
        </div>
    @endif
    <div class="p-5">
        {{ $slot }}
    </div>
    @if(isset($footer))
        <div class="px-5 py-3 bg-slate-950/40 border-t border-slate-800/80">
            {{ $footer }}
        </div>
    @endif
</div>
