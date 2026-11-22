@props([
  'id' => '',
  'name' => '',
  'label' => '',
  'placeholder' => 'Select date...',
  'value' => '',
  'mode' => 'single', // 'single' or 'range'
  'minDate' => '',
  'maxDate' => '',
  'dateFormat' => 'Y-m-d',
  'displayFormat' => 'M d, Y',
  'icon' => 'fa-regular fa-calendar',
  'required' => false,
  'shortcuts' => true,
  'class' => '',
  'wrapperClass' => '',
])

<div class="space-y-1.5 {{ $wrapperClass }}">
  @if($label)
    <label for="{{ $id }}" class="block text-xs font-bold text-slate-700">
      {{ $label }}
      @if($required)
        <span class="text-rose-500">*</span>
      @endif
    </label>
  @endif

  <div class="relative custom-datepicker-container">
    @if($icon)
      <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs z-10">
        <i class="{{ $icon }}"></i>
      </div>
    @endif
    
    <input 
      type="text" 
      id="{{ $id }}" 
      name="{{ $name }}" 
      value="{{ $value }}"
      placeholder="{{ $placeholder }}"
      data-calendar="true"
      data-mode="{{ $mode }}"
      @if($minDate) data-min-date="{{ $minDate }}" @endif
      @if($maxDate) data-max-date="{{ $maxDate }}" @endif
      data-date-format="{{ $dateFormat }}"
      data-display-format="{{ $displayFormat }}"
      data-shortcuts="{{ $shortcuts ? 'true' : 'false' }}"
      {{ $required ? 'required' : '' }}
      class="custom-datepicker w-full bg-slate-50 border border-slate-200/90 hover:border-slate-300 rounded-xl {{ $icon ? 'pl-9' : 'pl-3.5' }} pr-9 py-2 text-xs text-slate-800 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition cursor-pointer shadow-2xs {{ $class }}"
      {{ $attributes }}
      autocomplete="off"
    >

    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 text-[10px]">
      <i class="fa-solid fa-chevron-down"></i>
    </div>
  </div>
</div>
