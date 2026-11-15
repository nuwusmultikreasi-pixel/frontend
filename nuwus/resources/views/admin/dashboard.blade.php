@extends('layouts.admin')
@section('title', 'Dashboard — Admin Nuwus')
@section('heading', 'Dashboard')

@section('content')
<div class="stat-grid">
    <div class="stat"><small>Total Produk</small><strong>{{ $productCount }}</strong><span>{{ $productActive }} tampil di website</span></div>
    <div class="stat stat-orange"><small>Total Mitra</small><strong>{{ $partnerCount }}</strong><span>{{ $partnerActive }} tampil di running logo</span></div>
    <a class="stat stat-action" href="{{ route('admin.products.create') }}"><strong>+</strong><span>Tambah Produk</span></a>
    <a class="stat stat-action" href="{{ route('admin.partners.create') }}"><strong>+</strong><span>Tambah Logo Mitra</span></a>
</div>

<div class="panel">
    <div class="panel-head"><h2>Produk terbaru</h2><a href="{{ route('admin.products.index') }}">Lihat semua →</a></div>
    @forelse ($latest as $p)
        <div class="row-item">
            <div><strong>{{ $p['name'] }}</strong><small>{{ $p['category'] ?: 'Tanpa kategori' }} · {{ parse_url($p['url'], PHP_URL_HOST) }}</small></div>
            <span @class(['badge', 'badge-on' => $p['is_active'], 'badge-off' => ! $p['is_active']])>{{ $p['is_active'] ? 'Tampil' : 'Disembunyikan' }}</span>
        </div>
    @empty
        <p class="muted">Belum ada produk.</p>
    @endforelse
</div>
@endsection
