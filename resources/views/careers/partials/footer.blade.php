@php
    $footerBackToTopUrl = $footerBackToTopUrl ?? '#hero';
@endphp

<footer class="ys-footer">
    <div class="ys-container ys-footer__inner">
        <img :src="activeLogoUrl" src="{{ $logoUrl }}" alt="{{ $companyName }}" loading="lazy">
        <p>© {{ now()->year }} {{ $companyName }} - همه حقوق محفوظ است.</p>
        <a href="{{ $footerBackToTopUrl }}">بازگشت به بالا ↑</a>
    </div>
</footer>
