@extends('layouts.admin')
@section('title', ($item ? 'Edit' : 'Tambah').' Mitra — Admin Nuwus')
@section('heading', $item ? 'Edit Mitra' : 'Tambah Logo Mitra')
@section('actions')<a href="{{ route('admin.partners.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>@endsection

@section('content')
<form method="POST" enctype="multipart/form-data" class="panel form-grid"
      action="{{ $item ? route('admin.partners.update', $item['id']) : route('admin.partners.store') }}">
    @csrf
    @if ($item) @method('PUT') @endif

    <label class="field span-2">
        <span>Nama mitra *</span>
        <input type="text" name="name" value="{{ old('name', $item['name'] ?? '') }}" required maxlength="150">
        @error('name')<em>{{ $message }}</em>@enderror
    </label>
    <label class="field span-2">
        <span>Website mitra (opsional)</span>
        <input type="url" name="website" value="{{ old('website', $item['website'] ?? '') }}" placeholder="https://">
        @error('website')<em>{{ $message }}</em>@enderror
    </label>
    <label class="field">
        <span>Logo {{ $item ? '(kosongkan bila tidak diganti)' : '*' }} — PNG transparan disarankan, maks 2 MB</span>
        <input type="file" name="logo" accept="image/*" data-preview="logoPreview" @required(! $item)>
        @error('logo')<em>{{ $message }}</em>@enderror
    </label>
    <div class="field">
        <span>Pratinjau</span>
        <div class="preview preview-logo" id="logoPreview">
            @if (! empty($item['logo_url']))<img src="{{ $item['logo_url'] }}" alt="">@else<small>Belum ada logo</small>@endif
        </div>
    </div>
    <label class="field">
        <span>Urutan tampil</span>
        <input type="number" name="order" value="{{ old('order', $item['order'] ?? 0) }}" min="0">
    </label>
    <label class="toggle">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item['is_active'] ?? true))>
        <span>Tampilkan di running logo</span>
    </label>
    <div class="span-2 form-actions">
        <button class="btn btn-primary" type="submit">{{ $item ? 'Simpan Perubahan' : 'Tambah Mitra' }}</button>
    </div>
</form>
@endsection
