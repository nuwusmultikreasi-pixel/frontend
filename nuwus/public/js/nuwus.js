(() => {
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => [...el.querySelectorAll(s)];
  const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

  // Navbar: bayangan saat scroll + menu mobile
  const nav = $('.nav');
  if (nav) {
    const onScroll = () => nav.classList.toggle('is-scrolled', window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    const toggle = $('[data-nav-toggle]');
    const links = $('[data-nav]');
    toggle?.addEventListener('click', () => {
      const open = links.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open);
    });
    $$('a', links).forEach((a) => a.addEventListener('click', () => links.classList.remove('is-open')));
  }

  // Sidebar admin (mobile)
  $('[data-sidebar-toggle]')?.addEventListener('click', () => $('[data-sidebar]').classList.toggle('is-open'));

  const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

  // Efek mengetik berulang: <span class="type" data-words="a|b|c">
  $$('.type[data-words]').forEach(async (el) => {
    const words = el.dataset.words.split('|');
    if (reduceMotion || words.length < 2) return;
    let i = 0;
    await sleep(2200);
    for (;;) {
      const word = words[i];
      for (let n = word.length; n >= 0; n--) { el.textContent = word.slice(0, n); await sleep(38); }
      i = (i + 1) % words.length;
      await sleep(250);
      for (let n = 1; n <= words[i].length; n++) { el.textContent = words[i].slice(0, n); await sleep(85); }
      await sleep(2200);
    }
  });

  // Efek mengetik sekali saat terlihat: <span class="type-once" data-text="...">
  const typeOnce = async (el) => {
    const text = el.dataset.text;
    el.innerHTML = `<span class="t-ghost">${esc(text)}</span><span class="t-typed"></span>`;
    const typed = el.lastChild;
    el.classList.add('is-typing');
    await sleep(300);
    for (let n = 1; n <= text.length; n++) { typed.textContent = text.slice(0, n); await sleep(95); }
    await sleep(900);
    el.classList.remove('is-typing');
  };

  // Elemen muncul saat di-scroll
  const reveal = (el) => {
    if (el.classList.contains('type-once')) typeOnce(el);
    else el.classList.add('is-in');
  };
  const watched = $$('.reveal, .type-once');
  if (reduceMotion || !('IntersectionObserver' in window)) {
    watched.forEach((el) => el.classList.add('is-in'));
  } else {
    const io = new IntersectionObserver((entries) => entries.forEach((e) => {
      if (e.isIntersecting) { io.unobserve(e.target); reveal(e.target); }
    }), { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    watched.forEach((el) => io.observe(el));
  }

  // Angka berhitung naik saat masuk layar. Diulang setiap kali muncul lagi,
  // baik discroll dari atas ke bawah maupun dari bawah ke atas.
  const counters = $$('[data-count]');
  if (counters.length) {
    const fmt = (el, v) => { el.textContent = String(Math.round(v)).padStart(+el.dataset.pad || 0, '0'); };
    const state = new WeakMap();
    const stop = (el) => { const id = state.get(el); if (id) cancelAnimationFrame(id); state.delete(el); };
    const run = (el) => {
      stop(el);
      const to = +el.dataset.count;
      const dur = Math.min(2200, 900 + String(to).length * 220);
      const t0 = performance.now();
      const tick = (now) => {
        const p = Math.min((now - t0) / dur, 1);
        const eased = 1 - Math.pow(1 - p, 4); // melambat di akhir
        fmt(el, to * eased);
        if (p < 1) state.set(el, requestAnimationFrame(tick)); else state.delete(el);
      };
      state.set(el, requestAnimationFrame(tick));
    };
    if (reduceMotion || !('IntersectionObserver' in window)) {
      counters.forEach((el) => fmt(el, +el.dataset.count));
    } else {
      counters.forEach((el) => fmt(el, 0));
      const co = new IntersectionObserver((entries) => entries.forEach((e) => {
        if (e.isIntersecting) run(e.target);
        else { stop(e.target); fmt(e.target, 0); }
      }), { threshold: 0.6 });
      counters.forEach((el) => co.observe(el));
    }
  }

  // Cahaya mengikuti kursor di kartu produk
  $$('.product-card').forEach((card) => card.addEventListener('pointermove', (e) => {
    const r = card.getBoundingClientRect();
    card.style.setProperty('--mx', `${e.clientX - r.left}px`);
    card.style.setProperty('--my', `${e.clientY - r.top}px`);
  }));

  // Filter kategori produk: kartu pertama yang tampil menjadi kartu unggulan (lebar)
  $$('.chip[data-filter]').forEach((chip) => {
    chip.addEventListener('click', () => {
      $$('.chip[data-filter]').forEach((c) => c.classList.toggle('is-active', c === chip));
      const f = chip.dataset.filter;
      let first = true;
      $$('.product-card').forEach((card) => {
        card.hidden = f !== '*' && card.dataset.category !== f;
        card.classList.toggle('is-featured', !card.hidden && first);
        if (!card.hidden) { first = false; card.classList.add('is-in'); }
      });
    });
  });

  // Modal detail produk
  const modal = $('#productModal');
  const products = window.NUWUS_PRODUCTS || [];
  if (modal) {
    const open = (i) => {
      const p = products[i];
      if (!p) return;
      const short = (p.name || '').replace(/^Contoh Produk —\s*/, '');
      const media = $('#pmMedia');
      media.closest('.modal-media').className = `modal-media tone-${i % 3}`;
      media.closest('.modal-card').className = `modal-card pc-tone-${i % 3}`;
      let host = p.url;
      try { host = new URL(p.url).host; } catch (_) { /* biarkan apa adanya */ }
      $('#pmHost').textContent = host;
      media.innerHTML = p.thumbnail_url
        ? `<img src="${esc(p.thumbnail_url)}" alt="${esc(p.name)}">`
        : `<div class="ph-site"><div class="ph-nav"><b></b><i></i><i></i><i></i></div><div class="ph-hero"><strong>${esc(short)}</strong><i></i><i class="short"></i><em></em></div><div class="ph-grid"><span></span><span></span><span></span></div></div>`;
      $('#pmMeta').innerHTML = [
        p.category && `<span class="tag">${esc(p.category)}</span>`,
        p.year && `<span class="tag tag-orange">${esc(p.year)}</span>`,
      ].filter(Boolean).join('');
      $('#pmTitle').textContent = p.name;
      $('#pmTagline').textContent = p.tagline || '';
      $('#pmTagline').hidden = !p.tagline;
      $('#pmDesc').textContent = p.description || '';
      $('#pmTech').innerHTML = (p.tech_list || []).map((t) => `<span>${esc(t)}</span>`).join('');
      $('#pmLink').href = p.url;
      $('#pmUrl').textContent = p.url;
      modal.showModal();
    };
    $$('.product-card').forEach((card) => card.addEventListener('click', () => open(+card.dataset.product)));
    $$('[data-close]', modal).forEach((b) => b.addEventListener('click', () => modal.close()));
    modal.addEventListener('click', (e) => { if (e.target === modal) modal.close(); });
  }

  // Konfirmasi hapus
  $$('form[data-confirm]').forEach((f) => f.addEventListener('submit', (e) => {
    if (!confirm(f.dataset.confirm)) e.preventDefault();
  }));

  // Pratinjau gambar sebelum upload
  $$('input[type=file][data-preview]').forEach((input) => input.addEventListener('change', () => {
    const box = document.getElementById(input.dataset.preview);
    const file = input.files[0];
    if (box && file) box.innerHTML = `<img src="${URL.createObjectURL(file)}" alt="">`;
  }));
})();
