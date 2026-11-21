<?php

namespace Modules\Shared\View\Components;

class Button
{
    public static function render($text = '', $type = 'button', $variant = 'primary', $size = 'md', $icon = null, $attributes = '')
    {
        $baseClass = "inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2";
        
        $sizes = [
            'sm' => 'px-2.5 py-1.5 text-xs',
            'md' => 'px-4 py-2 text-sm',
            'lg' => 'px-5 py-2.5 text-base',
        ];
        $sizeClass = $sizes[$size] ?? $sizes['md'];

        $variants = [
            'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500 shadow-sm',
            'secondary' => 'bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 focus:ring-slate-500',
            'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500 shadow-sm',
            'danger' => 'bg-rose-600 hover:bg-rose-700 text-white focus:ring-rose-500 shadow-sm',
            'outline' => 'bg-transparent border border-slate-600 text-slate-300 hover:bg-slate-800 focus:ring-slate-500',
        ];
        $variantClass = $variants[$variant] ?? $variants['primary'];

        $iconHtml = $icon ? "<i class='{$icon} mr-2'></i>" : '';

        return "<button type='{$type}' class='{$baseClass} {$sizeClass} {$variantClass}' {$attributes}>{$iconHtml}{$text}</button>";
    }
}
