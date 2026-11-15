@extends('layouts.admin')
@section('title', 'Produk — Admin Nuwus')
@section('heading', 'Produk')
@section('actions')<a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Tambah Produk</a>@endsection

@section('content')
@if ($offline)<div class="alert alert-error">Backend Django tidak dapat dihubungi.</div>@endif
<div class="panel table-wrap">
    <table class="table">
        <thead><tr><th>Produk</th><th>Kategori</th><th>Link</th><th>Urutan</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse ($items as $p)
            <tr>
                <td>
                    <div class="t-prod">
                        @if ($p['thumbnail_url'])<img src="{{ $p['thumbnail_url'] }}" alt="">@else<span class="t-ph">{{ mb_strtoupper(mb_substr($p['name'], 0, 1)) }}</span>@endif
                        <div><strong>{{ $p['name'] }}</strong><small>{{ \Illuminate\Support\Str::limit($p['tagline'] ?: $p['description'], 60) }}</small></div>
                    </div>
                </td>
                <td>{{ $p['category'] ?: '—' }}</td>
                <td><a href="{{ $p['url'] }}" target="_blank" rel="noopener">{{ parse_url($p['url'], PHP_URL_HOST) }}</a></td>
                <td>{{ $p['order'] }}</td>
                <td><span @class(['badge', 'badge-on' => $p['is_active'], 'badge-off' => ! $p['is_active']])>{{ $p['is_active'] ? 'Tampil' : 'Disembunyikan' }}</span></td>
                <td class="t-actions">
                    <a href="{{ route('admin.products.edit', $p['id']) }}" class="btn btn-ghost btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $p['id']) }}" data-confirm="Hapus produk “{{ $p['name'] }}”?">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted center">Belum ada produk. <a href="{{ route('admin.products.create') }}">Tambah sekarang</a>.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
