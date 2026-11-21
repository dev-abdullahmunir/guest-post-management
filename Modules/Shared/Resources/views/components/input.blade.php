<div class="{{ $attributes->get('wrapper-class', 'w-full') }}">
    @if(isset($label))
        <label for="{{ $id ?? $name }}" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
            {{ $label }}
            @if(isset($required) && $required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif
    <div class="relative">
        @if(isset($icon))
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500 text-sm">
                <i class="{{ $icon }}"></i>
            </div>
        @endif
        <input 
            type="{{ $type ?? 'text' }}" 
            name="{{ $name ?? '' }}" 
            id="{{ $id ?? $name ?? '' }}"
            value="{{ $value ?? '' }}"
            placeholder="{{ $placeholder ?? '' }}"
            {{ isset($required) && $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full rounded-lg bg-slate-900/80 border border-slate-700/80 px-3.5 py-2 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition ' . (isset($icon) ? 'pl-9' : '')]) }}
        />
    </div>
    @if(isset($hint))
        <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
    @endif
</div>
