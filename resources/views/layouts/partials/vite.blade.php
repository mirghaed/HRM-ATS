@php
    $manifestPath = public_path('build/manifest.json');
    $manifest = file_exists($manifestPath)
        ? (json_decode((string) file_get_contents($manifestPath), true) ?? [])
        : [];

    $manifestFiles = collect($manifest)
        ->flatMap(static function (array $entry): array {
            return array_values(array_filter([
                $entry['file'] ?? null,
                ...($entry['css'] ?? []),
            ]));
        })
        ->unique()
        ->values();

    $manifestValid = $manifestFiles->isNotEmpty()
        && $manifestFiles->every(static fn (string $file): bool => file_exists(public_path('build/'.$file)));

    $cssEntry = $manifest['resources/css/app.css'] ?? null;
    $jsEntry = $manifest['resources/js/app.js'] ?? null;

    $builtStylesheets = collect();
    if (! empty($cssEntry['file'])) {
        $builtStylesheets->push($cssEntry['file']);
    }
    foreach ($jsEntry['css'] ?? [] as $cssFile) {
        $builtStylesheets->push($cssFile);
    }
    $builtStylesheets = $builtStylesheets->unique()->values();

    $fallbackCssPath = public_path('assets/vite/app.css');
    $fallbackJsPath = public_path('assets/vite/app.js');
    $hasFallbackAssets = file_exists($fallbackCssPath) && file_exists($fallbackJsPath);
@endphp

@if ($manifestValid && $builtStylesheets->isNotEmpty() && ! empty($jsEntry['file']))
    @foreach ($builtStylesheets as $stylesheet)
        <link rel="stylesheet" href="{{ asset('build/'.$stylesheet) }}">
    @endforeach
    <script src="{{ asset('build/'.$jsEntry['file']) }}" defer></script>
@elseif ($hasFallbackAssets)
    <link rel="stylesheet" href="{{ asset('assets/vite/app.css') }}?v={{ filemtime($fallbackCssPath) }}">
    <script src="{{ asset('assets/vite/app.js') }}?v={{ filemtime($fallbackJsPath) }}" defer></script>
@endif
