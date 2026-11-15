@extends('layouts.app')
@section('title', 'Login Admin — '.config('nuwus.company.short_name'))
@section('body_class', 'auth-page')

@section('body')
<div class="auth-wrap">
    <aside class="auth-side">
        @include('partials.brand', ['light' => true])
        <div>
            <h2>Kelola portofolio produk & mitra dari satu tempat.</h2>
            <p>Tambahkan produk website yang baru rilis dan logo mitra kerja sama — langsung tampil di halaman utama.</p>
        </div>
        <small>&copy; {{ date('Y') }} {{ config('nuwus.company.name') }}</small>
    </aside>
    <main class="auth-main">
        <form method="POST" action="{{ route('login.submit') }}" class="auth-form" novalidate>
            @csrf
            <a href="{{ route('home') }}" class="back-link">← Kembali ke beranda</a>
            <h1>Masuk Admin</h1>
            <p class="muted">Gunakan akun admin yang dibuat di backend Django.</p>

            @include('partials.flash')

            <label class="field">
                <span>Username</span>
                <input type="text" name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>
                @error('username')<em>{{ $message }}</em>@enderror
            </label>
            <label class="field">
                <span>Password</span>
                <input type="password" name="password" autocomplete="current-password" required>
                @error('password')<em>{{ $message }}</em>@enderror
            </label>
            <button class="btn btn-primary btn-block" type="submit">Masuk</button>
        </form>
    </main>
</div>
@endsection
