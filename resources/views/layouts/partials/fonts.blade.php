@php
    $peydaRegular = public_path('assets/fonts/peyda/woff2/PeydaWebFaNum-Regular.woff2');
    $usePeyda = file_exists($peydaRegular);
@endphp

@if ($usePeyda)
    <style>
        @font-face {
            font-family: "PeydaWebFaNum";
            src: url("{{ asset('assets/fonts/peyda/woff2/PeydaWebFaNum-Regular.woff2') }}") format("woff2"),
                url("{{ asset('assets/fonts/peyda/woff/PeydaWebFaNum-Regular.woff') }}") format("woff");
            font-style: normal;
            font-weight: 400;
            font-display: swap;
        }

        @font-face {
            font-family: "PeydaWebFaNum";
            src: url("{{ asset('assets/fonts/peyda/woff2/PeydaWebFaNum-Medium.woff2') }}") format("woff2"),
                url("{{ asset('assets/fonts/peyda/woff/PeydaWebFaNum-Medium.woff') }}") format("woff");
            font-style: normal;
            font-weight: 500;
            font-display: swap;
        }

        @font-face {
            font-family: "PeydaWebFaNum";
            src: url("{{ asset('assets/fonts/peyda/woff2/PeydaWebFaNum-Bold.woff2') }}") format("woff2"),
                url("{{ asset('assets/fonts/peyda/woff/PeydaWebFaNum-Bold.woff') }}") format("woff");
            font-style: normal;
            font-weight: 700;
            font-display: swap;
        }

        @font-face {
            font-family: "PeydaWebFaNum";
            src: url("{{ asset('assets/fonts/peyda/woff2/PeydaWebFaNum-Black.woff2') }}") format("woff2"),
                url("{{ asset('assets/fonts/peyda/woff/PeydaWebFaNum-Black.woff') }}") format("woff");
            font-style: normal;
            font-weight: 900;
            font-display: swap;
        }

        html,
        body {
            font-family: "PeydaWebFaNum", "Vazirmatn", sans-serif;
        }
    </style>
@endif
