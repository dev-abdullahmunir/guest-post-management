@props([
  'id' => '',
  'name' => '',
  'label' => '',
  'placeholder' => 'Select an option...',
  'required' => false,
  'searchable' => true,
  'icon' => '',
  'options' => [],
  'selected' => '',
  'class' => '',
])

<div class="space-y-1.5">
  @if($label)
    <label for="{{ $id }}" class="block text-xs font-bold text-slate-700">
      {{ $label }}
      @if($required)
        <span class="text-rose-500">*</span>
      @endif
    </label>
  @endif

  <div class="relative">
    <select 
      id="{{ $id }}" 
      name="{{ $name }}" 
      class="custom-select {{ $class }}"
      data-placeholder="{{ $placeholder }}"
      data-searchable="{{ $searchable ? 'true' : 'false' }}"
      data-icon="{{ $icon }}"
      {{ $required ? 'required' : '' }}
      {{ $attributes }}
    >
      <option value="">{{ $placeholder }}</option>
      @foreach($options as $opt)
        @php
          $val = is_array($opt) ? ($opt['value'] ?? $opt['id'] ?? '') : $opt;
          $text = is_array($opt) ? ($opt['label'] ?? $opt['name'] ?? $opt['title'] ?? $val) : $opt;
          $badge = is_array($opt) ? ($opt['badge'] ?? '') : '';
          $subtext = is_array($opt) ? ($opt['subtext'] ?? '') : '';
          $optIcon = is_array($opt) ? ($opt['icon'] ?? '') : '';
          $isSel = (string)$selected === (string)$val;
        @endphp
        <option 
          value="{{ $val }}" 
          {{ $isSel ? 'selected' : '' }}
          @if($badge) data-badge="{{ $badge }}" @endif
          @if($subtext) data-subtext="{{ $subtext }}" @endif
          @if($optIcon) data-icon="{{ $optIcon }}" @endif
        >
          {{ $text }}
        </option>
      @endforeach
    </select>
  </div>
</div>
