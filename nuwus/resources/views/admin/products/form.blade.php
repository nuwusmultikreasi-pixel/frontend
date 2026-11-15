@extends('layouts.admin')
@section('title', ($item ? 'Edit' : 'Tambah').' Produk — Admin Nuwus')
@section('heading', $item ? 'Edit Produk' : 'Tambah Produk')
@section('actions')<a href="{{ route('admin.products.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>@endsection

@section('content')
<form method="POST" enctype="multipart/form-data" class="panel form-grid"
      action="{{ $item ? route('admin.products.update', $item['id']) : route('admin.products.store') }}">
    @csrf
    @if ($item) @method('PUT') @endif

    <label class="field span-2">
        <span>Nama produk *</span>
        <input type="text" name="name" value="{{ old('name', $item['name'] ?? '') }}" required maxlength="150">
        @error('name')<em>{{ $message }}</em>@enderror
    </label>
    <label class="field">
        <span>Kategori</span>
        <input type="text" name="category" value="{{ old('category', $item['category'] ?? '') }}" placeholder="mis. Company Profile, E-Commerce" maxlength="80">
        @error('category')<em>{{ $message }}</em>@enderror
    </label>
    <label class="field">
        <span>Tahun</span>
        <input type="number" name="year" value="{{ old('year', $item['year'] ?? date('Y')) }}" min="2000" max="2100">
        @error('year')<em>{{ $message }}</em>@enderror
    </label>
    <label class="field span-2">
        <span>Link website *</span>
        <input type="url" name="url" value="{{ old('url', $item['url'] ?? '') }}" placeholder="https://" required>
        @error('url')<em>{{ $message }}</em>@enderror
    </label>
    <label class="field span-2">
        <span>Ringkasan singkat (tampil di kartu)</span>
        <input type="text" name="tagline" value="{{ old('tagline', $item['tagline'] ?? '') }}" maxlength="200">
        @error('tagline')<em>{{ $message }}</em>@enderror
    </label>
    <label class="field span-2">
        <span>Deskripsi lengkap * (tampil saat kartu diklik)</span>
        <textarea name="description" rows="7" required>{{ old('description', $item['description'] ?? '') }}</textarea>
        @error('description')<em>{{ $message }}</em>@enderror
    </label>
    <label class="field span-2">
        <span>Teknologi (pisahkan dengan koma)</span>
        <input type="text" name="tech_stack" value="{{ old('tech_stack', $item['tech_stack'] ?? '') }}" placeholder="Laravel, MySQL, Tailwind">
        @error('tech_stack')<em>{{ $message }}</em>@enderror
    </label>
    <label class="field">
        <span>Gambar / thumbnail (JPG/PNG/WebP, maks 4 MB)</span>
        <input type="file" name="thumbnail" accept="image/*" data-preview="thumbPreview">
        @error('thumbnail')<em>{{ $message }}</em>@enderror
    </label>
    <div class="field">
        <span>Pratinjau</span>
        <div class="preview" id="thumbPreview">
            @if (! empty($item['thumbnail_url']))<img src="{{ $item['thumbnail_url'] }}" alt="">@else<small>Belum ada gambar</small>@endif
        </div>
    </div>
    <label class="field">
        <span>Urutan tampil</span>
        <input type="number" name="order" value="{{ old('order', $item['order'] ?? 0) }}" min="0">
    </label>
    <label class="toggle">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item['is_active'] ?? true))>
        <span>Tampilkan di website</span>
    </label>

    <div class="span-2 form-actions">
        <button class="btn btn-primary" type="submit">{{ $item ? 'Simpan Perubahan' : 'Tambah Produk' }}</button>
    </div>
</form>
@endsection
