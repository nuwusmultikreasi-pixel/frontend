@php($light = $light ?? false)
<a href="{{ route('home') }}" @class(['brand', 'brand-light' => $light]) aria-label="Beranda {{ config('nuwus.company.name') }}">
    <img src="{{ asset($light ? 'img/logo-mark-white.svg' : 'img/logo-mark.svg') }}" alt="" width="23" height="40">
    <span class="brand-text">
        <strong>Nuwus</strong>
        <strong>Multikreasi</strong>
    </span>
</a>
