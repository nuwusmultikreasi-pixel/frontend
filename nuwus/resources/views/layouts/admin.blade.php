@extends('layouts.app')
@section('body_class', 'admin-page')

@section('body')
<div class="admin">
    <aside class="sidebar" data-sidebar>
        @include('partials.brand')
        <nav>
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" @class(['active' => request()->routeIs('admin.products.*')])>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                Produk
            </a>
            <a href="{{ route('admin.partners.index') }}" @class(['active' => request()->routeIs('admin.partners.*')])>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/></svg>
                Mitra
            </a>
            <a href="{{ route('home') }}" target="_blank">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/></svg>
                Lihat Website
            </a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="sidebar-foot">
            @csrf
            <div class="user-chip">
                <span>{{ mb_strtoupper(mb_substr(session('api_user.name', 'A'), 0, 1)) }}</span>
                <div><strong>{{ session('api_user.name', 'Admin') }}</strong><small>Administrator</small></div>
            </div>
            <button type="submit" class="btn btn-ghost btn-sm btn-block">Keluar</button>
        </form>
    </aside>
    <div class="admin-main">
        <header class="admin-top">
            <button class="nav-toggle admin-toggle" aria-label="Menu" data-sidebar-toggle><span></span><span></span><span></span></button>
            <h1>@yield('heading')</h1>
            <div class="admin-actions">@yield('actions')</div>
        </header>
        <div class="admin-content">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>
</div>
@endsection
