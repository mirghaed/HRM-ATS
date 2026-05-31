@props([
    'name' => 'spark',
    'class' => '',
])

@if($name === 'team')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm9 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3 20a4.5 4.5 0 0 1 9 0m3 0a4.5 4.5 0 0 1 6 0" />
    </svg>
@elseif($name === 'job')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16v10H4zM9 7V5h6v2m-4 5h2" />
    </svg>
@elseif($name === 'resume')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l3 3v15H7z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v4h4M10 12h4m-4 3h4" />
    </svg>
@elseif($name === 'growth')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="m4 17 5-5 4 4 7-8" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h3v3" />
    </svg>
@elseif($name === 'filter')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M7 12h10m-7 6h4" />
    </svg>
@elseif($name === 'analytics')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 20V9m7 11V5m7 15v-8" />
    </svg>
@elseif($name === 'wallet')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8h18v10H3z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8V6h14l4 2m-4 5h2" />
    </svg>
@elseif($name === 'support')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10a4 4 0 1 1 8 0v2a3 3 0 0 0 1 2.2L18 15H6l1-0.8A3 3 0 0 0 8 12z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 18a2 2 0 0 0 4 0" />
    </svg>
@elseif($name === 'faq')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19a7 7 0 1 0-7-7" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 9.5a2.5 2.5 0 1 1 4.4 1.6c-.9.9-1.9 1.4-1.9 2.9m0 2h.01" />
    </svg>
@elseif($name === 'learning')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5 3 9l9 4 9-4-9-4Z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V16c0 1.5 2.2 3 5 3s5-1.5 5-3v-4.5" />
    </svg>
@else
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="{{ $class }}" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
    </svg>
@endif
