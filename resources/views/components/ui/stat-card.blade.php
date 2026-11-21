@props([
  'title' => '',
  'value' => '0',
  'growth' => '',
  'growthType' => 'up', // up, down
  'icon' => 'fa-chart-line',
  'color' => 'blue', // blue, purple, green, orange
])

@php
  $colorConfig = [
    'blue' => [
      'bg' => 'bg-blue-50/70 border-blue-100 hover:border-blue-200',
      'iconBg' => 'bg-blue-500 text-white',
      'accent' => 'text-blue-600',
      'chartColor' => '#3b82f6'
    ],
    'purple' => [
      'bg' => 'bg-purple-50/70 border-purple-100 hover:border-purple-200',
      'iconBg' => 'bg-purple-500 text-white',
      'accent' => 'text-purple-600',
      'chartColor' => '#a855f7'
    ],
    'green' => [
      'bg' => 'bg-emerald-50/70 border-emerald-100 hover:border-emerald-200',
      'iconBg' => 'bg-emerald-500 text-white',
      'accent' => 'text-emerald-600',
      'chartColor' => '#10b981'
    ],
    'orange' => [
      'bg' => 'bg-amber-50/70 border-amber-100 hover:border-amber-200',
      'iconBg' => 'bg-amber-500 text-white',
      'accent' => 'text-amber-600',
      'chartColor' => '#f59e0b'
    ],
  ][$color] ?? [
    'bg' => 'bg-blue-50/70 border-blue-100',
    'iconBg' => 'bg-blue-500 text-white',
    'accent' => 'text-blue-600',
    'chartColor' => '#3b82f6'
  ];
@endphp

<div class="relative bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:shadow-md transition-all flex flex-col justify-between overflow-hidden group">
  <!-- Subtle top accent background tint -->
  <div class="absolute top-0 right-0 w-32 h-32 rounded-full opacity-30 -mr-10 -mt-10 {{ $colorConfig['bg'] }} blur-2xl pointer-events-none"></div>

  <div class="flex items-start gap-4">
    <div class="w-11 h-11 rounded-xl {{ $colorConfig['iconBg'] }} shadow-md flex items-center justify-center shrink-0">
      <i class="{{ $icon }} text-lg"></i>
    </div>
    <div class="flex-1 min-w-0">
      <p class="text-xs font-medium text-slate-500 tracking-tight">{{ $title }}</p>
      <div class="flex items-baseline gap-2 mt-1">
        <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ $value }}</h3>
        @if($growth)
          <span class="inline-flex items-center text-xs font-bold {{ $growthType === 'up' ? 'text-emerald-600' : 'text-rose-500' }}">
            <i class="fa-solid {{ $growthType === 'up' ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[10px] mr-0.5"></i>
            {{ $growth }}
          </span>
        @endif
      </div>
    </div>
  </div>

  <!-- Smooth SVG Sparkline Curve at bottom -->
  <div class="mt-3 -mx-2 -mb-2 h-10 flex items-end">
    <svg class="w-full h-8 overflow-visible" viewBox="0 0 100 25" preserveAspectRatio="none">
      <path d="M0,20 Q20,5 40,18 T80,8 T100,12" fill="none" stroke="{{ $colorConfig['chartColor'] }}" stroke-width="2.5" stroke-linecap="round" />
    </svg>
  </div>
</div>
