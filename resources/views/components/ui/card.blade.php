@props([
  'title' => '',
  'subtitle' => '',
  'actionText' => '',
  'actionUrl' => '',
  'icon' => null,
])

<div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] p-6">
  @if($title || $actionText)
    <div class="flex items-center justify-between pb-4 border-b border-slate-50 mb-4">
      <div class="flex items-center gap-2">
        @if($icon)
          <i class="{{ $icon }} text-indigo-600"></i>
        @endif
        <div>
          <h3 class="text-sm font-bold text-slate-800">{{ $title }}</h3>
          @if($subtitle)
            <p class="text-xs text-slate-400 mt-0.5">{{ $subtitle }}</p>
          @endif
        </div>
      </div>
      @if($actionText)
        <a href="{{ $actionUrl ?? '#' }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 transition">
          <span>{{ $actionText }}</span>
          <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
      @endif
    </div>
  @endif

  <div>
    {{ $slot }}
  </div>
</div>
