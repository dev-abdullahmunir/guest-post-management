@props([
  'status' => 'Completed',
])

@php
  $normalized = strtolower(trim($status));
  $config = match($normalized) {
    'completed' => [
      'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
      'label' => 'Completed'
    ],
    'pending' => [
      'class' => 'bg-amber-50 text-amber-600 border-amber-100',
      'label' => 'Pending'
    ],
    'processing' => [
      'class' => 'bg-blue-50 text-blue-600 border-blue-100',
      'label' => 'Processing'
    ],
    'cancelled' => [
      'class' => 'bg-rose-50 text-rose-600 border-rose-100',
      'label' => 'Cancelled'
    ],
    default => [
      'class' => 'bg-slate-50 text-slate-600 border-slate-200',
      'label' => ucfirst($status)
    ]
  };
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $config['class'] }}">
  {{ $config['label'] }}
</span>
