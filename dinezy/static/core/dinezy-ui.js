
(function () {
    const ICON = { success: 'fa-circle-check', warning: 'fa-triangle-exclamation', error: 'fa-circle-xmark', danger: 'fa-lock', info: 'fa-circle-info' };
    const TITLE = { success: 'Berhasil', warning: 'Perhatian', error: 'Gagal', info: 'Informasi' };
    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    function confirmDlg(o) {
        o = Object.assign({ title: 'Lanjutkan?', text: '', variant: 'info', ok: 'Ya, lanjutkan', cancel: 'Batal' }, o || {});
        return new Promise(resolve => {
            const wrap = document.createElement('div');
            wrap.className = 'dz-dlg-wrap';
            wrap.innerHTML = `<div class="dz-dlg v-${o.variant}" role="alertdialog" aria-modal="true" aria-labelledby="dzDlgT">
                <div class="dz-dlg-ic"><i class="fas ${o.icon || ICON[o.variant] || ICON.info}"></i></div>
                <h3 id="dzDlgT">${esc(o.title)}</h3>
                ${o.text ? `<p>${esc(o.text)}</p>` : ''}
                ${o.html || ''}
                <div class="dz-dlg-act"><button type="button" class="b-cancel">${esc(o.cancel)}</button><button type="button" class="b-ok">${esc(o.ok)}</button></div>
            </div>`;
            document.body.appendChild(wrap);
            requestAnimationFrame(() => wrap.classList.add('show'));
            const close = v => { wrap.classList.remove('show'); document.removeEventListener('keydown', key); setTimeout(() => wrap.remove(), 220); resolve(v); };
            const key = e => { if (e.key === 'Escape') close(false); if (e.key === 'Enter') close(true); };
            document.addEventListener('keydown', key);
            wrap.addEventListener('click', e => { if (e.target === wrap) close(false); });
            wrap.querySelector('.b-cancel').onclick = () => close(false);
            const ok = wrap.querySelector('.b-ok'); ok.onclick = () => close(true); setTimeout(() => ok.focus(), 50);
        });
    }

    function toast(o) {
        o = Object.assign({ type: 'info', timeout: 5200 }, o || {});
        let box = document.querySelector('.dz-toasts');
        if (!box) { box = document.createElement('div'); box.className = 'dz-toasts'; document.body.appendChild(box); }
        const t = document.createElement('div');
        t.className = `dz-toast t-${o.type}`; t.setAttribute('role', 'status');
        t.innerHTML = `<div class="dz-toast-ic"><i class="fas ${ICON[o.type] || ICON.info}"></i></div>
            <div class="dz-toast-b"><b>${esc(o.title || TITLE[o.type] || 'Info')}</b>${o.text ? `<span>${esc(o.text)}</span>` : ''}</div>
            <button type="button" class="dz-toast-x" aria-label="Tutup"><i class="fas fa-xmark"></i></button>
            <div class="dz-toast-bar" style="animation-duration:${o.timeout}ms"></div>`;
        box.appendChild(t);
        requestAnimationFrame(() => t.classList.add('show'));
        const close = () => { t.classList.remove('show'); setTimeout(() => t.remove(), 260); };
        t.querySelector('.dz-toast-x').onclick = close;
        let timer = setTimeout(close, o.timeout);
        t.addEventListener('mouseenter', () => { clearTimeout(timer); t.classList.add('paused'); });
        t.addEventListener('mouseleave', () => { t.classList.remove('paused'); timer = setTimeout(close, 1800); });
        return t;
    }

    document.addEventListener('submit', async e => {
        const f = e.target;
        if (!f.matches || !f.matches('form[data-confirm]') || f.dataset.confirmed === '1') return;
        e.preventDefault();
        const ok = await confirmDlg({ title: f.dataset.confirm, text: f.dataset.confirmText || '', variant: f.dataset.confirmVariant || 'info',
                                      ok: f.dataset.confirmOk || 'Ya, lanjutkan', icon: f.dataset.confirmIcon });
        if (ok) { f.dataset.confirmed = '1'; (f.requestSubmit ? f.requestSubmit(e.submitter) : f.submit()); }
    }, true);

    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('dz-messages');
        if (!el) return;
        try { JSON.parse(el.textContent).forEach((m, i) => setTimeout(() => toast(m), 150 + i * 180)); } catch (_) {}
    });

    window.DZUI = { confirm: confirmDlg, toast, esc };
})();
