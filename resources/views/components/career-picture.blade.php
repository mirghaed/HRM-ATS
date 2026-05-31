@props([
    'name',
    'folder' => 'illustrations',
    'alt' => '',
    'width' => null,
    'height' => null,
    'class' => '',
    'loading' => 'lazy',
    'fetchpriority' => null,
    'decoding' => 'async',
    'sizes' => null,
    'srcsetWebp' => null,
    'srcsetPng' => null,
])

@php
    $webpSrc = asset('assets/careers/' . $folder . '/' . $name . '.webp');
    $pngSrc = asset('assets/careers/' . $folder . '/' . $name . '.png');
@endphp

<picture>
    <source srcset="{{ $srcsetWebp ?: $webpSrc }}" type="image/webp" @if($sizes) sizes="{{ $sizes }}" @endif>
    <img
        src="{{ $pngSrc }}"
        @if($srcsetPng) srcset="{{ $srcsetPng }}" @endif
        @if($sizes) sizes="{{ $sizes }}" @endif
        alt="{{ $alt }}"
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        loading="{{ $loading }}"
        decoding="{{ $decoding }}"
        @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
        class="{{ $class }}"
    >
</picture>
