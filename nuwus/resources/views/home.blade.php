@extends('layouts.public')

@section('content')
@php($c = $company)

{{-- ============ HERO ============ --}}
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow"><span class="dot"></span> Software House · Kota Malang</span>
            <h1 aria-label="Kami membangun website, aplikasi, dan sistem informasi yang bekerja untuk bisnis Anda.">
                <span aria-hidden="true">Kami membangun</span>
                <span class="type-line" aria-hidden="true"><span class="type hl" data-words="website modern|aplikasi bisnis|sistem informasi|toko online">website modern</span></span>
                <span aria-hidden="true">yang <span class="type-once hl-orange" data-text="bekerja">bekerja</span> untuk bisnis Anda.</span>
            </h1>
            <p class="lead">{{ $c['name'] }} merancang, mengembangkan, dan merawat website serta aplikasi — dari company profile hingga sistem informasi — dengan standar profesional dan legalitas resmi.</p>
            <div class="hero-cta">
                <a href="#produk" class="btn btn-primary">Lihat Portofolio Produk</a>
                <a href="https://wa.me/{{ $c['whatsapp'] }}" target="_blank" rel="noopener" class="btn btn-outline">Konsultasi Gratis</a>
            </div>
            @php($stats = [
                [count($products), 'Produk dirilis'],
                [count($partners), 'Mitra kerja sama'],
                [$c['kbli_code'], 'KBLI Software'],
            ])
            <dl class="hero-stats">
                @foreach ($stats as [$value, $label])
                    <div>
                        <dt><span data-count="{{ $value }}" style="min-width: {{ strlen((string) $value) }}ch">{{ $value }}</span></dt>
                        <dd>{{ $label }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
        <div class="hero-visual">
            <div class="hv-frame"></div>
            <figure class="hv-photo">
                <img src="{{ asset('img/hero-nuwus.webp') }}" alt="Tim Nuwus sedang mengembangkan website" width="896" height="1195" fetchpriority="high">
            </figure>
            <div class="hv-orange" aria-hidden="true">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/></svg>
            </div>
            <div class="hv-badge">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M12 2l8 4v6c0 5-3.5 9-8 10-4.5-1-8-5-8-10V6z"/><path d="m9 12 2 2 4-4"/></svg>
                <div>
                    <small>Terdaftar resmi</small>
                    <strong>NIB {{ $c['nib'] }}</strong>
                </div>
            </div>
            <div class="hv-logo" aria-hidden="true">
                <img src="{{ asset('img/logo-mark-white.svg') }}" alt="" width="22" height="38">
            </div>
        </div>
    </div>
</section>

{{-- ============ MITRA (running logo) ============ --}}
<section class="partners" id="mitra">
    <div class="container">
        <p class="partners-title">Dipercaya oleh mitra yang telah bekerja sama dengan kami</p>
    </div>
    @if (count($partners))
        @php($loopCount = max(2, (int) ceil(10 / max(count($partners), 1))))
        <div class="marquee" style="--duration: {{ max(20, count($partners) * $loopCount * 3) }}s">
            <div class="marquee-track">
                @for ($half = 0; $half < 2; $half++)
                    <div class="marquee-group" @if($half) aria-hidden="true" @endif>
                        @for ($r = 0; $r < $loopCount; $r++)
                            @foreach ($partners as $p)
                                @php($tag = $p['website'] ? 'a' : 'span')
                                <{{ $tag }} class="marquee-item" @if($p['website']) href="{{ $p['website'] }}" target="_blank" rel="noopener" @endif title="{{ $p['name'] }}" @if($half || $r) tabindex="-1" @endif>
                                    <img src="{{ $p['logo_url'] }}" alt="{{ $p['name'] }}" loading="lazy">
                                </{{ $tag }}>
                            @endforeach
                        @endfor
                    </div>
                @endfor
            </div>
        </div>
    @else
        <div class="container"><p class="empty">Logo mitra akan tampil di sini.</p></div>
    @endif
</section>

{{-- ============ PRODUK ============ --}}
<section class="section" id="produk">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="kicker">Portofolio</span>
                <h2>Produk yang sudah kami <span class="type-once hl" data-text="bangun">bangun</span></h2>
                <p>Klik kartu produk untuk melihat deskripsi lengkap dan tautan menuju websitenya.</p>
            </div>
            @if ($categories->count() > 1)
                <div class="chips" role="tablist" aria-label="Filter kategori">
                    <button class="chip is-active" data-filter="*">Semua</button>
                    @foreach ($categories as $cat)
                        <button class="chip" data-filter="{{ $cat }}">{{ $cat }}</button>
                    @endforeach
                </div>
            @endif
        </div>

        @if (count($products))
            <div class="product-grid">
                @foreach ($products as $i => $p)
                    @php($host = parse_url($p['url'], PHP_URL_HOST))
                    @php($short = trim(preg_replace('/^Contoh Produk —\s*/u', '', $p['name'])))
                    <button type="button" @class(['product-card', 'pc-tone-'.($i % 3), 'is-featured' => $i === 0, 'reveal'])
                            data-product="{{ $i }}" data-category="{{ $p['category'] }}" style="--d: {{ min($i, 5) * 80 }}ms"
                            aria-label="Lihat detail {{ $p['name'] }}">
                        <div class="pc-inner">
                        <div class="pc-stage">
                            <div class="pc-browser">
                                <div class="pc-bar"><i></i><i></i><i></i><span>{{ $host }}</span></div>
                                <div class="pc-screen">
                                    @if ($p['thumbnail_url'])
                                        <img src="{{ $p['thumbnail_url'] }}" alt="" loading="lazy">
                                    @else
                                        <div class="ph-site">
                                            <div class="ph-nav"><b></b><i></i><i></i><i></i></div>
                                            <div class="ph-hero"><strong>{{ \Illuminate\Support\Str::limit($short, 28) }}</strong><i></i><i class="short"></i><em></em></div>
                                            <div class="ph-grid"><span></span><span></span><span></span></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="pc-body">
                            <div class="pc-meta">
                                <span class="pc-index">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                @if ($p['category'])<span class="pc-cat">{{ $p['category'] }}</span>@endif
                                @if ($p['year'])<span class="pc-year">{{ $p['year'] }}</span>@endif
                            </div>
                            <h3>{{ $p['name'] }}</h3>
                            <p>{{ $p['tagline'] ?: \Illuminate\Support\Str::limit($p['description'], 110) }}</p>
                            @if (count($p['tech_list']))
                                <div class="pc-tech">
                                    @foreach (array_slice($p['tech_list'], 0, 4) as $t)<span>{{ $t }}</span>@endforeach
                                </div>
                            @endif
                            <div class="pc-foot">
                                <span class="pc-more">Lihat detail</span>
                                <span class="pc-arrow" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M7 17 17 7M8 7h9v9"/></svg></span>
                            </div>
                        </div>
                        </div>
                    </button>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p><strong>Belum ada produk.</strong> Login sebagai admin untuk menambahkan produk pertama, atau pastikan backend Django sedang berjalan.</p>
            </div>
        @endif
    </div>
</section>

{{-- Modal detail produk --}}
<dialog class="modal" id="productModal" aria-labelledby="pmTitle">
    <div class="modal-card">
        <button class="modal-close" data-close aria-label="Tutup">&times;</button>
        <div class="modal-media"><div class="pc-browser"><div class="pc-bar"><i></i><i></i><i></i><span id="pmHost"></span></div><div class="pc-screen" id="pmMedia"></div></div></div>
        <div class="modal-body">
            <div class="modal-meta" id="pmMeta"></div>
            <h3 id="pmTitle"></h3>
            <p class="modal-tagline" id="pmTagline"></p>
            <div class="modal-desc" id="pmDesc"></div>
            <div class="modal-tech" id="pmTech"></div>
            <div class="modal-actions">
                <a id="pmLink" href="#" target="_blank" rel="noopener" class="btn btn-primary">
                    Kunjungi Website
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M7 17 17 7M8 7h9v9"/></svg>
                </a>
                <span class="modal-url" id="pmUrl"></span>
            </div>
        </div>
    </div>
</dialog>
<script>window.NUWUS_PRODUCTS = @json(array_values($products));</script>

{{-- ============ TENTANG / LEGALITAS ============ --}}
<section class="section section-dark" id="tentang">
    <div class="container about-grid">
        <div>
            <span class="kicker kicker-light">Tentang Kami</span>
            <h2>Perusahaan perangkat lunak <span class="type-once hl-orange" data-text="resmi">resmi</span> berbadan hukum.</h2>
            <p>{{ $c['name'] }} adalah {{ strtolower($c['legal_form']) }} yang berkedudukan di {{ $c['domicile'] }} dan bergerak di bidang <strong>{{ $c['kbli_title'] }}</strong> (KBLI {{ $c['kbli_code'] }}).</p>
            <p>Kami fokus membangun produk digital — website, sistem informasi, dan platform perdagangan elektronik — yang rapi secara teknis, mudah digunakan, dan siap berkembang bersama bisnis Anda.</p>
            <ul class="values">
                <li><b>01</b> Desain bersih & fokus pada pengguna</li>
                <li><b>02</b> Kode yang terawat dan dapat dikembangkan</li>
                <li><b>03</b> Pendampingan setelah produk dirilis</li>
            </ul>
        </div>
        <div class="legal-card">
            <h3>Legalitas Perusahaan</h3>
            <dl>
                <div><dt>Nama Badan Usaha</dt><dd>{{ $c['name'] }}</dd></div>
                <div><dt>SK Kemenkum</dt><dd>{{ $c['sk_number'] }}<small>Tanggal {{ $c['sk_date'] }}</small></dd></div>
                <div><dt>Nomor Induk Berusaha</dt><dd>{{ $c['nib'] }}<small>Terbit {{ $c['nib_date'] }} · Risiko {{ $c['risk_level'] }}</small></dd></div>
                <div><dt>KBLI</dt><dd>{{ $c['kbli_code'] }} — {{ $c['kbli_title'] }}<small>{{ $c['kbli_scope'] }}</small></dd></div>
            </dl>
        </div>
    </div>
</section>

{{-- ============ KONTAK ============ --}}
<section class="section" id="kontak">
    <div class="container contact-grid">
        <div>
            <span class="kicker">Kontak</span>
            <h2>Punya ide produk digital? Mari <span class="type-once hl" data-text="diskusikan.">diskusikan.</span></h2>
            <p>Ceritakan kebutuhan Anda — kami bantu dari perencanaan hingga peluncuran.</p>
            <div class="contact-list">
                <a href="https://wa.me/{{ $c['whatsapp'] }}" target="_blank" rel="noopener" class="contact-item">
                    <span class="ci-icon ci-orange"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/></svg></span>
                    <span><small>Telepon / WhatsApp</small>{{ $c['phone'] }}</span>
                </a>
                <a href="mailto:{{ $c['email'] }}" class="contact-item">
                    <span class="ci-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg></span>
                    <span><small>Email</small>{{ $c['email'] }}</span>
                </a>
                <div class="contact-item">
                    <span class="ci-icon ci-dark"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                    <span><small>Alamat Kantor</small>{{ $c['address'] }}</span>
                </div>
            </div>
        </div>
        <div class="map">
            <iframe title="Lokasi kantor" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                src="https://maps.google.com/maps?q={{ urlencode('Jl. Sumbersari Gg. 4, Sumbersari, Lowokwaru, Kota Malang') }}&z=16&output=embed"></iframe>
        </div>
    </div>
</section>
@endsection
