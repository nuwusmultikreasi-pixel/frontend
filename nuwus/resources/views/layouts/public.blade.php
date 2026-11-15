@extends('layouts.app')

@section('body')
<header class="nav" id="top">
    <div class="container nav-inner">
        @include('partials.brand')
        <button class="nav-toggle" aria-label="Buka menu" aria-expanded="false" data-nav-toggle>
            <span></span><span></span><span></span>
        </button>
        <nav class="nav-links" data-nav>
            <a href="{{ route('home') }}#produk">Produk</a>
            <a href="{{ route('home') }}#mitra">Mitra</a>
            <a href="{{ route('home') }}#tentang">Tentang</a>
            <a href="{{ route('home') }}#kontak">Kontak</a>
            @if (session('api_token'))
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-dark btn-sm">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                    Login
                </a>
            @endif
        </nav>
    </div>
</header>

<main>
    @yield('content')
</main>

@php($c = config('nuwus.company'))
<footer class="footer">
    <div class="container footer-grid">
        <div>
            @include('partials.brand')
            <p class="footer-about">Studio pengembang perangkat lunak berbadan hukum di Kota Malang. Kami merancang dan membangun website serta aplikasi yang siap dipakai bisnis.</p>
        </div>
        <div>
            <h4>Navigasi</h4>
            <a href="{{ route('home') }}#produk">Produk</a>
            <a href="{{ route('home') }}#mitra">Mitra</a>
            <a href="{{ route('home') }}#tentang">Tentang</a>
            <a href="{{ route('home') }}#kontak">Kontak</a>
        </div>
        <div>
            <h4>Kontak</h4>
            <a href="mailto:{{ $c['email'] }}">{{ $c['email'] }}</a>
            <a href="https://wa.me/{{ $c['whatsapp'] }}" target="_blank" rel="noopener">{{ $c['phone'] }}</a>
            <span>{{ $c['domicile'] }}, Jawa Timur</span>
        </div>
        <div>
            <h4>Legalitas</h4>
            <span>NIB {{ $c['nib'] }}</span>
            <span>SK {{ $c['sk_number'] }}</span>
            <span>KBLI {{ $c['kbli_code'] }}</span>
        </div>
    </div>
    <div class="container footer-bottom">
        <span>&copy; {{ date('Y') }} {{ $c['name'] }}. Seluruh hak cipta dilindungi.</span>
        <a href="#top">Kembali ke atas ↑</a>
    </div>
</footer>
@endsection
