@extends('layouts.admin')
@section('title', 'Mitra — Admin Nuwus')
@section('heading', 'Logo Mitra')
@section('actions')<a href="{{ route('admin.partners.create') }}" class="btn btn-primary btn-sm">+ Tambah Mitra</a>@endsection

@section('content')
@if ($offline)<div class="alert alert-error">Backend Django tidak dapat dihubungi.</div>@endif
<p class="muted">Logo yang berstatus “Tampil” akan berjalan sebagai running logo di halaman utama.</p>
<div class="partner-admin-grid">
    @forelse ($items as $p)
        <div class="partner-tile">
            <div class="pt-logo"><img src="{{ $p['logo_url'] }}" alt="{{ $p['name'] }}"></div>
            <div class="pt-info">
                <strong>{{ $p['name'] }}</strong>
                <small>Urutan {{ $p['order'] }} · <span @class(['badge', 'badge-on' => $p['is_active'], 'badge-off' => ! $p['is_active']])>{{ $p['is_active'] ? 'Tampil' : 'Disembunyikan' }}</span></small>
            </div>
            <div class="t-actions">
                <a href="{{ route('admin.partners.edit', $p['id']) }}" class="btn btn-ghost btn-sm">Edit</a>
                <form method="POST" action="{{ route('admin.partners.destroy', $p['id']) }}" data-confirm="Hapus mitra “{{ $p['name'] }}”?">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <div class="panel muted">Belum ada mitra. <a href="{{ route('admin.partners.create') }}">Tambah logo mitra</a>.</div>
    @endforelse
</div>
@endsection
