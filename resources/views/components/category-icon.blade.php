{{-- A tinted square showing a category's icon in the category's colour. --}}
@props(['category', 'size' => 'md'])

@php
    $boxes = ['sm' => 'size-9 rounded-lg', 'md' => 'size-10 rounded-lg', 'lg' => 'size-12 rounded-xl'];
    $icons = ['sm' => 'size-4', 'md' => 'size-5', 'lg' => 'size-6'];
@endphp

<span {{ $attributes->class("inline-flex shrink-0 items-center justify-center {$boxes[$size]}") }}
    style="background-color: {{ $category['color'] }}1a; color: color-mix(in oklab, {{ $category['color'] }} 82%, black);">
    <x-icon :name="$category['icon']" class="{{ $icons[$size] }}" />
</span>
