
(function () {
    const QR_LIB = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
    const FONT = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Inter+Tight:wght@700;800&display=swap';
    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const rp = n => 'Rp ' + Number(n || 0).toLocaleString('id-ID');
    const abs = u => (u && !/^(https?:|blob:|data:)/.test(u)) ? window.location.origin + u : (u || '');

    const PAPER = {
        '57': { page: '57mm auto', width: '45mm', left: '1mm', u: '0.8mm', thermal: true },
        '58': { page: '58mm auto', width: '46mm', left: '1mm', u: '0.82mm', thermal: true },
        '80': { page: '80mm auto', width: '70mm', left: '1mm', u: '1.05mm', thermal: true },
        'a5': { page: 'A5 portrait', width: '118mm', u: '1.25mm' },
        'a4': { page: 'A4 portrait', width: '150mm', u: '1.4mm' },
    };
    const paperOf = p => PAPER[p] || PAPER['80'];

    function printHTML(title, css, body, script) {
        const html = `<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>${esc(title)}</title>
            <link href="${FONT}" rel="stylesheet"><style>${css}</style>
            <script src="${QR_LIB}"><\/script></head><body>${body}
            <script>${script || ''}<\/script></body></html>`;
        window.__dzLastPrint = html;
        const old = document.getElementById('dz-print-frame'); if (old) old.remove();
        const f = document.createElement('iframe');
        f.id = 'dz-print-frame';
        f.setAttribute('aria-hidden', 'true');
        f.style.cssText = 'position:fixed;right:0;bottom:0;width:0;height:0;border:0;visibility:hidden';
        document.body.appendChild(f);
        const w = f.contentWindow;
        let printed = false;
        const go = () => {
            if (printed) return;
            printed = true;
            const ready = (w.document.fonts && w.document.fonts.ready) ? w.document.fonts.ready : Promise.resolve();
            ready.then(() => setTimeout(() => { try { w.focus(); w.print(); } catch (e) { console.error(e); } }, 300));
        };
        const d = w.document; d.open(); d.write(html); d.close();

        if (d.readyState === 'complete') go(); else w.addEventListener('load', go, { once: true });
        setTimeout(go, 3000);
    }

    const QR_CSS = `
        @page { size: A4 portrait; margin: 8mm; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body { margin: 0; font-family: 'Inter', Arial, sans-serif; color: #0b1d38; background: #fff; }
        .sheet { display: grid; grid-template-columns: repeat(2, 95mm); grid-auto-rows: 136mm; gap: 6mm; justify-content: center; padding: 4mm 0; }
        .sheet.single { grid-template-columns: 95mm; padding-top: 20mm; }
        .card { position: relative; width: 95mm; height: 136mm; background: #fff; border-radius: 6mm; overflow: hidden; display: flex; flex-direction: column;
                outline: .3mm dashed #b8c2d3; outline-offset: 2.5mm; break-inside: avoid; page-break-inside: avoid; border: .3mm solid #e3e8f0; }
        .top { position: relative; background: #0b1d38; color: #fff; padding: 7mm 7mm 17mm; overflow: hidden; }
        .top::before { content: ''; position: absolute; right: -16mm; top: -18mm; width: 60mm; height: 60mm; border-radius: 50%; background: radial-gradient(circle, rgba(126,166,230,.45), transparent 65%); }
        .brand { position: relative; z-index: 1; display: flex; align-items: center; gap: 3mm; }
        .brand .lg { width: 12mm; height: 12mm; border-radius: 2.5mm; background: #fff; display: flex; align-items: center; justify-content: center; padding: 1.2mm; flex-shrink: 0; }
        .brand .lg img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .brand b { display: block; font-family: 'Inter Tight', 'Inter', sans-serif; font-size: 5mm; line-height: 1.1; letter-spacing: -.02em; }
        .brand small { font-size: 2.6mm; color: #a9c4ee; letter-spacing: .3mm; text-transform: uppercase; font-weight: 600; }
        .headline { position: relative; z-index: 1; margin-top: 5mm; font-family: 'Inter Tight', 'Inter', sans-serif; font-size: 6.4mm; font-weight: 800; line-height: 1.08; letter-spacing: -.02em; }
        .headline span { color: #7ea6e6; }
        .table-no { position: absolute; z-index: 2; left: 50%; transform: translateX(-50%); top: calc(var(--top-h, 41mm) - 6mm);
                    background: #fff; color: #0b1d38; border-radius: 99mm; padding: 1.8mm 6mm; display: flex; align-items: baseline; gap: 2mm;
                    box-shadow: 0 1.5mm 4mm rgba(11,29,56,.25); border: .4mm solid #e3e8f0; white-space: nowrap; }
        .table-no small { font-size: 2.6mm; font-weight: 700; letter-spacing: .5mm; color: #5b6b85; }
        .table-no b { font-family: 'Inter Tight', 'Inter', sans-serif; font-size: 7mm; font-weight: 800; letter-spacing: -.02em; }
        .qr-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 9mm 7mm 2mm; }
        .qr-frame { position: relative; padding: 3.2mm; border-radius: 4mm; background: #fff; }
        .qr-frame i { position: absolute; width: 7mm; height: 7mm; border: 1.1mm solid #0b1d38; }
        .qr-frame i:nth-child(1) { left: 0; top: 0; border-right: 0; border-bottom: 0; border-radius: 3mm 0 0 0; }
        .qr-frame i:nth-child(2) { right: 0; top: 0; border-left: 0; border-bottom: 0; border-radius: 0 3mm 0 0; }
        .qr-frame i:nth-child(3) { left: 0; bottom: 0; border-right: 0; border-top: 0; border-radius: 0 0 0 3mm; }
        .qr-frame i:nth-child(4) { right: 0; bottom: 0; border-left: 0; border-top: 0; border-radius: 0 0 3mm 0; }
        .qr { width: 44mm; height: 44mm; }
        .qr img, .qr canvas { width: 100% !important; height: 100% !important; display: block; }
        .scan { margin-top: 3mm; font-size: 2.9mm; font-weight: 700; letter-spacing: .5mm; text-transform: uppercase; color: #17345e; }
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2mm; padding: 0 6mm 4mm; }
        .step { text-align: center; font-size: 2.45mm; line-height: 1.3; color: #4b5b74; }
        .step b { display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2mm; width: 6mm; height: 6mm; border-radius: 50%; background: #e8eef8; color: #0b1d38; font-size: 2.8mm; }
        .foot { display: flex; align-items: center; justify-content: space-between; gap: 2mm; padding: 3mm 6mm; background: #f3f6fb; border-top: .3mm solid #e3e8f0; font-size: 2.3mm; color: #5b6b85; }
        .foot .pw { display: flex; align-items: center; gap: 1.5mm; font-weight: 600; color: #17345e; }
        .foot .pw img { height: 4mm; }`;

    function qrMeja({ cafeName, cafeLogo, brandLogoWhite, brandLogoNavy, tables }) {
        const single = tables.length === 1;
        const logo = abs(cafeLogo) || abs(brandLogoNavy);
        const cards = tables.map((t, i) => `
            <div class="card">
                <div class="top">
                    <div class="brand"><span class="lg"><img src="${esc(logo)}" alt=""></span><div><b>${esc(cafeName)}</b><small>Self-order · Tanpa antre</small></div></div>
                    <div class="headline">Scan, pilih menu,<br><span>pesan dari meja.</span></div>
                </div>
                <div class="table-no"><small>MEJA</small><b>${esc(t.nomor)}</b></div>
                <div class="qr-wrap">
                    <div class="qr-frame"><i></i><i></i><i></i><i></i><div class="qr" id="qr${i}"></div></div>
                    <div class="scan">Arahkan kamera ke QR Code</div>
                </div>
                <div class="steps"><div class="step"><b>1</b>Scan QR Code</div><div class="step"><b>2</b>Pilih menu favorit</div><div class="step"><b>3</b>Kirim & bayar</div></div>
                <div class="foot"><span>Tunai · QRIS</span><span class="pw"><img src="${esc(abs(brandLogoNavy))}" alt="">Dinezy</span></div>
            </div>`).join('');
        const script = `
            ${JSON.stringify(tables.map(t => t.url))}.forEach((u, i) => new QRCode(document.getElementById('qr' + i), { text: u, width: 512, height: 512, colorDark: '#0b1d38', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.H }));
            document.querySelectorAll('.card').forEach(c => c.style.setProperty('--top-h', c.querySelector('.top').offsetHeight + 'px'));`;
        printHTML(single ? `QR Meja ${tables[0].nomor}` : `QR Semua Meja — ${cafeName}`, QR_CSS,
            `<div class="sheet ${single ? 'single' : ''}">${cards}</div>`, script);
    }

    function notaThermalCSS(p) {
        return `
        @page { size: ${p.page}; margin: 0; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        :root { --u: ${p.u}; }
        html, body { margin: 0; background: #fff; color: #000; }
        body { font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-weight: 700; }
        .r { width: ${p.width}; margin: 0 0 0 ${p.left}; padding: 0 0 calc(var(--u)*2); font-size: calc(var(--u)*3.6); line-height: 1.3; }
        .c { text-align: center; }
        .row { display: flex; justify-content: space-between; gap: calc(var(--u)*2); align-items: baseline; }
        .row > :last-child { text-align: right; }
        .lt { font-weight: 400; }
        /* tepi bergerigi ala struk */
        .zig { height: calc(var(--u)*2.2); background: conic-gradient(from 135deg at 50% 0, #000 90deg, transparent 0) 0 0 / calc(var(--u)*3.2) 100% repeat-x; }
        .zig.up { background: conic-gradient(from -45deg at 50% 100%, #000 90deg, transparent 0) 0 0 / calc(var(--u)*3.2) 100% repeat-x; margin-top: calc(var(--u)*3); }
        .head { padding: calc(var(--u)*3) 0 calc(var(--u)*1); }
        .logo { height: calc(var(--u)*12); max-width: 55%; object-fit: contain; display: block; margin: 0 auto calc(var(--u)*1.6); filter: grayscale(1) contrast(2.2) brightness(.95); }
        .name { font-size: calc(var(--u)*5.8); font-weight: 900; line-height: 1.05; text-transform: uppercase; letter-spacing: .03em; }
        .addr { font-size: calc(var(--u)*3); margin-top: calc(var(--u)*.8); }
        .stub { display: flex; border: calc(var(--u)*.6) solid #000; margin: calc(var(--u)*2.6) 0 calc(var(--u)*2.2); }
        .stub .mj { background: #000; color: #fff; padding: calc(var(--u)*1.2) calc(var(--u)*2.4); text-align: center; min-width: 34%; display: flex; flex-direction: column; justify-content: center; }
        .stub .mj small { font-size: calc(var(--u)*2.6); letter-spacing: .14em; }
        .stub .mj b { font-size: calc(var(--u)*8.4); font-weight: 900; line-height: 1; }
        .stub .od { flex: 1; padding: calc(var(--u)*1.4) calc(var(--u)*2.2); display: flex; flex-direction: column; justify-content: center; gap: calc(var(--u)*.3); }
        .stub .od small { font-size: calc(var(--u)*2.6); letter-spacing: .1em; font-weight: 400; }
        .stub .od b { font-size: calc(var(--u)*5); font-weight: 900; line-height: 1.05; }
        .stub .od span { font-size: calc(var(--u)*3); }
        .meta .row { padding: calc(var(--u)*.3) 0; font-size: calc(var(--u)*3.4); }
        .lbl { display: flex; align-items: center; gap: calc(var(--u)*1.6); margin: calc(var(--u)*2.6) 0 calc(var(--u)*1.2); font-size: calc(var(--u)*2.9); letter-spacing: .18em; }
        .lbl::before, .lbl::after { content: ''; flex: 1; border-top: calc(var(--u)*.45) solid #000; }
        .it { display: flex; gap: calc(var(--u)*2); padding: calc(var(--u)*1.3) 0; border-bottom: calc(var(--u)*.35) dashed #000; }
        .it.last { border-bottom: 0; }
        .it .q { flex-shrink: 0; min-width: calc(var(--u)*6.6); height: calc(var(--u)*6.6); border: calc(var(--u)*.55) solid #000; border-radius: calc(var(--u)*1.2); display: flex; align-items: center; justify-content: center; font-size: calc(var(--u)*3.8); font-weight: 900; }
        .it .b { flex: 1; min-width: 0; }
        .it .nm { font-size: calc(var(--u)*3.8); font-weight: 900; line-height: 1.2; }
        .it .row { font-size: calc(var(--u)*3.3); }
        .it .note { margin: calc(var(--u)*.6) 0 calc(var(--u)*.4); padding-left: calc(var(--u)*1.6); border-left: calc(var(--u)*.8) solid #000; font-size: calc(var(--u)*3.1); font-weight: 700; font-style: italic; line-height: 1.25; word-break: break-word; }
        .sum { border-top: calc(var(--u)*.45) solid #000; margin-top: calc(var(--u)*1.2); padding-top: calc(var(--u)*1.6); }
        .sum .row { padding: calc(var(--u)*.35) 0; font-size: calc(var(--u)*3.5); }
        .total { background: #000; color: #fff; display: flex; justify-content: space-between; align-items: center; padding: calc(var(--u)*2) calc(var(--u)*2.4); margin-top: calc(var(--u)*1.8); }
        .total span { font-size: calc(var(--u)*3.6); letter-spacing: .12em; }
        .total b { font-size: calc(var(--u)*6.4); font-weight: 900; }
        .paid { display: flex; justify-content: space-between; align-items: center; margin-top: calc(var(--u)*2.2); }
        .paid .lt { font-size: calc(var(--u)*3.3); } .paid .lt b { font-weight: 900; }
        .stamp { padding: calc(var(--u)*.8) calc(var(--u)*2.6); border: calc(var(--u)*1) double #000; font-size: calc(var(--u)*3.8); font-weight: 900; letter-spacing: .14em; }
        .stamp.inv { background: #000; color: #fff; border-style: solid; }
        .msg { margin-top: calc(var(--u)*3); border: calc(var(--u)*.45) dashed #000; border-radius: calc(var(--u)*2); padding: calc(var(--u)*2) calc(var(--u)*2.4); text-align: center; }
        .msg b { display: block; font-size: calc(var(--u)*3.9); font-weight: 900; line-height: 1.25; }
        .qrw { display: flex; align-items: center; gap: calc(var(--u)*2.4); margin-top: calc(var(--u)*3); }
        .qr { width: calc(var(--u)*19); height: calc(var(--u)*19); flex-shrink: 0; image-rendering: pixelated; }
        .qr img, .qr canvas { width: 100% !important; height: 100% !important; image-rendering: pixelated; }
        .qrw b { display: block; font-size: calc(var(--u)*3.6); font-weight: 900; } .qrw span { font-size: calc(var(--u)*3); font-weight: 400; }
        .pw { margin-top: calc(var(--u)*3); font-size: calc(var(--u)*2.8); letter-spacing: .06em; }
        .pw b { font-weight: 900; }`;
    }

    function notaCSS(p) {
        return `
        @page { size: ${p.page}; margin: 12mm; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        :root { --u: ${p.u}; }
        body { margin: 0; background: #fff; font-family: 'Inter', Arial, sans-serif; color: #0b1220; }
        .r { width: ${p.width}; margin: 0 auto; padding: calc(var(--u)*4) 0; font-size: calc(var(--u)*3.05); line-height: 1.45; }
        .c { text-align: center; }
        .logo { height: calc(var(--u)*12); width: auto; max-width: 60%; object-fit: contain; display: block; margin: 0 auto calc(var(--u)*2); }
        .name { font-family: 'Inter Tight', 'Inter', sans-serif; font-weight: 800; font-size: calc(var(--u)*5); letter-spacing: -.02em; line-height: 1.15; }
        .addr { color: #4b5563; font-size: calc(var(--u)*2.7); margin-top: calc(var(--u)*1); }
        .line, .dash { border-top: calc(var(--u)*.35) dashed #9aa3b2; margin: calc(var(--u)*3) 0; } .line { border-top-style: solid; border-color: #0b1220; }
        .row { display: flex; justify-content: space-between; gap: calc(var(--u)*2); } .row > :last-child { text-align: right; }
        .blk { display: flex; justify-content: space-between; align-items: center; background: #0b1d38; color: #fff; border-radius: calc(var(--u)*2); padding: calc(var(--u)*2.4) calc(var(--u)*3); margin: calc(var(--u)*3) 0 calc(var(--u)*2.5); }
        .blk small { display: block; font-size: calc(var(--u)*2.3); text-transform: uppercase; color: #a9c4ee; font-weight: 600; }
        .blk b { font-family: 'Inter Tight', 'Inter', sans-serif; font-size: calc(var(--u)*4.4); }
        .meta .row { padding: calc(var(--u)*.4) 0; } .meta .row span:first-child { color: #6b7280; } .meta b { font-weight: 600; }
        .it { padding: calc(var(--u)*1.4) 0; border-bottom: calc(var(--u)*.2) dotted #d1d5db; } .it .nm { font-weight: 600; } .it .row span:first-child { color: #6b7280; } .it .note { color: #92400e; font-size: calc(var(--u)*2.7); font-style: italic; margin: calc(var(--u)*.4) 0; }
        .sum .row { padding: calc(var(--u)*.5) 0; } .sum .row span:first-child { color: #4b5563; }
        .grand { border: calc(var(--u)*.45) solid #0b1220; border-radius: calc(var(--u)*2); padding: calc(var(--u)*2.4) calc(var(--u)*3); margin-top: calc(var(--u)*2); align-items: center; }
        .grand span { font-weight: 700; } .grand b { font-family: 'Inter Tight', 'Inter', sans-serif; font-size: calc(var(--u)*5.2); }
        .stamp { display: inline-block; margin-top: calc(var(--u)*3); padding: calc(var(--u)*1.2) calc(var(--u)*4); border: calc(var(--u)*.5) solid #0f7a3d; color: #0f7a3d; border-radius: calc(var(--u)*1.5); font-weight: 800; letter-spacing: 1px; font-size: calc(var(--u)*3.2); }
        .stamp.inv { color: #b42318; border-color: #b42318; }
        .thanks { font-family: 'Inter Tight', 'Inter', sans-serif; font-weight: 700; font-size: calc(var(--u)*3.6); margin-top: calc(var(--u)*1); }
        .qr { width: calc(var(--u)*18); height: calc(var(--u)*18); margin: calc(var(--u)*3) auto calc(var(--u)*1); } .qr img, .qr canvas { width: 100% !important; height: 100% !important; }
        .small { color: #4b5563; font-size: calc(var(--u)*2.6); }
        .pw { margin-top: calc(var(--u)*3); color: #6b7280; font-size: calc(var(--u)*2.4); }`;
    }

    function nota({ order, cafeName, cafeAddress, cafePhone, cafeLogo, brandLogoNavy, menuUrl, kasirName, paper, footer }) {
        const p = paperOf(paper);
        const now = new Date();
        const tgl = now.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
        const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        const items = order.items || [];
        const qty = items.reduce((s, i) => s + Number(i.jumlah || 0), 0);
        const lunas = ['Diproses', 'Selesai'].includes(order.status_pesanan);
        const num = n => Number(n || 0).toLocaleString('id-ID');
        const fee = Number(order.biaya_layanan || 0);
        const subtotal = Number(order.total_harga || 0) - fee;
        const metodeBayar = esc(order.metode_pembayaran || 'TUNAI');
        let body;
        if (p.thermal) {
            const rowsT = items.map((it, i) => `
                <div class="it${i === items.length - 1 ? ' last' : ''}"><span class="q">${it.jumlah}</span><div class="b"><div class="nm">${esc(it.nama_menu || 'Item')}</div>${it.catatan ? `<div class="note">Catatan: ${esc(it.catatan)}</div>` : ''}
                <div class="row"><span class="lt">@ ${num(it.harga_satuan)}</span><b>${num(it.harga_satuan * it.jumlah)}</b></div></div></div>`).join('');
            body = `
            <div class="r">
                <div class="zig"></div>
                <div class="c head">
                    ${cafeLogo ? `<img class="logo" src="${esc(abs(cafeLogo))}" alt="">` : ''}
                    <div class="name">${esc(cafeName)}</div>
                    ${cafeAddress ? `<div class="addr">${esc(cafeAddress)}</div>` : ''}
                    ${cafePhone ? `<div class="addr">WA ${esc(cafePhone)}</div>` : ''}
                </div>
                <div class="stub">
                    <div class="mj"><small>MEJA</small><b>${esc(order.nomor_meja || '-')}</b></div>
                    <div class="od"><small>NO. PESANAN</small><b>#${order.id}</b><span>${tgl} · ${jam}</span></div>
                </div>
                <div class="meta">
                    <div class="row"><span class="lt">Pelanggan</span><b>${esc(order.nama_pemesan || '-')}</b></div>
                    ${kasirName ? `<div class="row"><span class="lt">Kasir</span><b>${esc(kasirName)}</b></div>` : ''}
                </div>
                <div class="lbl">PESANAN</div>
                ${rowsT}
                <div class="sum">
                    <div class="row"><span class="lt">Subtotal (${qty} item)</span><b>${num(subtotal)}</b></div>
                    ${fee ? `<div class="row"><span class="lt">Biaya layanan</span><b>${num(fee)}</b></div>` : ''}
                </div>
                <div class="total"><span>TOTAL</span><b>${rp(order.total_harga)}</b></div>
                <div class="paid"><span class="lt">Bayar: <b>${metodeBayar}</b></span><span class="stamp ${lunas ? '' : 'inv'}">${lunas ? 'LUNAS' : 'BELUM BAYAR'}</span></div>
                ${footer ? `<div class="msg"><b>${esc(footer)}</b></div>` : ''}
                ${menuUrl ? `<div class="qrw"><div class="qr" id="nq"></div><div><b>Pesan lagi?</b><span>Scan QR ini dari meja Anda, tanpa antre.</span></div></div>` : ''}
                <div class="c pw">Dipesan via <b>Dinezy</b></div>
                <div class="zig up"></div>
            </div>`;
        } else {
            const rows = items.map(it => `
                <div class="it"><div class="nm">${esc(it.nama_menu || 'Item')}</div>${it.catatan ? `<div class="note">Catatan: ${esc(it.catatan)}</div>` : ''}
                <div class="row"><span>${it.jumlah} x ${num(it.harga_satuan)}</span><b>${num(it.harga_satuan * it.jumlah)}</b></div></div>`).join('');
            body = `
            <div class="r">
                <div class="c">
                    ${cafeLogo ? `<img class="logo" src="${esc(abs(cafeLogo))}" alt="">` : ''}
                    <div class="name">${esc(cafeName)}</div>
                    ${cafeAddress ? `<div class="addr">${esc(cafeAddress)}${cafePhone ? ' · ' + esc(cafePhone) : ''}</div>` : ''}
                </div>
                <div class="blk"><div><small>NO. PESANAN</small><b>#${order.id}</b></div><div style="text-align:right"><small>MEJA</small><b>${esc(order.nomor_meja || '-')}</b></div></div>
                <div class="meta">
                    <div class="row"><span>Tanggal</span><b>${tgl}, ${jam}</b></div>
                    <div class="row"><span>Pelanggan</span><b>${esc(order.nama_pemesan || '-')}</b></div>
                    <div class="row"><span>Pembayaran</span><b>${metodeBayar}</b></div>
                    ${kasirName ? `<div class="row"><span>Kasir</span><b>${esc(kasirName)}</b></div>` : ''}
                </div>
                <div class="line"></div>
                ${rows}
                <div class="dash"></div>
                <div class="sum"><div class="row"><span>Subtotal (${qty} item)</span><b>${num(subtotal)}</b></div>
                ${fee ? `<div class="row"><span>Biaya layanan</span><b>${num(fee)}</b></div>` : ''}</div>
                <div class="row grand"><span>TOTAL</span><b>${rp(order.total_harga)}</b></div>
                <div class="c"><span class="stamp ${lunas ? '' : 'inv'}">${lunas ? 'LUNAS' : 'BELUM DIBAYAR'}</span></div>
                <div class="dash"></div>
                ${footer ? `<div class="c thanks">${esc(footer)}</div>` : ''}
                ${menuUrl ? `<div class="qr" id="nq"></div><div class="c small">Scan untuk pesan lagi</div>` : ''}
                <div class="c pw">Dinezy</div>
            </div>`;
        }
        const script = menuUrl ? `new QRCode(document.getElementById('nq'), { text: ${JSON.stringify(menuUrl)}, width: 192, height: 192, colorDark: '#000000', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.M });` : '';
        printHTML(`Nota #ORD-${order.id}`, p.thermal ? notaThermalCSS(p) : notaCSS(p), body, script);
    }

    function dapurCSS(p) {
        return `
        @page { size: ${p.page}; margin: ${p.thermal ? '0' : '12mm'}; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        :root { --u: ${p.u}; }
        body { margin: 0; font-family: ${p.thermal ? "Arial, Helvetica, sans-serif" : "'Inter', Arial, sans-serif"}; color: #000; background: #fff; }
        .k { width: ${p.width}; margin: ${p.thermal ? '0 0 0 ' + p.left : '0 auto'}; padding: calc(var(--u)*3) 0 calc(var(--u)*5); font-size: calc(var(--u)*3.3); }
        .head { display: flex; justify-content: space-between; align-items: center; border-bottom: calc(var(--u)*.6) solid #000; padding-bottom: calc(var(--u)*2); }
        .head .t { font-family: 'Inter Tight', 'Inter', sans-serif; font-weight: 800; font-size: calc(var(--u)*4.4); letter-spacing: 1px; }
        .head .c { font-size: calc(var(--u)*2.6); text-align: right; }
        .meja { display: flex; justify-content: space-between; align-items: flex-end; margin: calc(var(--u)*3) 0 calc(var(--u)*2); }
        .meja small { display: block; font-size: calc(var(--u)*2.6); font-weight: 700; letter-spacing: 1px; }
        .meja b { font-family: 'Inter Tight', 'Inter', sans-serif; font-size: calc(var(--u)*11); line-height: .9; font-weight: 800; }
        .meja .no { text-align: right; } .meja .no b { font-size: calc(var(--u)*5.5); }
        .info { font-size: calc(var(--u)*2.9); border-top: calc(var(--u)*.3) dashed #000; border-bottom: calc(var(--u)*.3) dashed #000; padding: calc(var(--u)*1.5) 0; display: flex; justify-content: space-between; }
        ul { list-style: none; padding: 0; margin: calc(var(--u)*2) 0 0; }
        li { display: flex; gap: calc(var(--u)*3); align-items: flex-start; padding: calc(var(--u)*2) 0; border-bottom: calc(var(--u)*.3) dashed #000; }
        li .q { min-width: calc(var(--u)*9); height: calc(var(--u)*9); border: calc(var(--u)*.6) solid #000; border-radius: calc(var(--u)*1.5); display: flex; align-items: center; justify-content: center; font-family: 'Inter Tight', 'Inter', sans-serif; font-weight: 800; font-size: calc(var(--u)*5); }
        li .n { font-weight: 700; font-size: calc(var(--u)*4.2); line-height: 1.2; padding-top: calc(var(--u)*1); min-width: 0; }
        li .cn { display: block; margin-top: calc(var(--u)*1.2); padding: calc(var(--u)*.8) calc(var(--u)*1.6); background: #000; color: #fff; font-size: calc(var(--u)*3.4); font-weight: 800; line-height: 1.25; word-break: break-word; }
        .sum { margin-top: calc(var(--u)*3); font-weight: 700; display: flex; justify-content: space-between; font-size: calc(var(--u)*3.2); }
        .foot { margin-top: calc(var(--u)*4); text-align: center; font-size: calc(var(--u)*2.5); }
        .box { margin-top: calc(var(--u)*3); border: calc(var(--u)*.4) solid #000; border-radius: calc(var(--u)*1.5); height: calc(var(--u)*12); display: flex; align-items: flex-start; padding: calc(var(--u)*1.2) calc(var(--u)*2); font-size: calc(var(--u)*2.5); }`;
    }

    function dapur({ order, cafeName, paper }) {
        const p = paperOf(paper);
        const items = order.items || [];
        const t = order.created_at ? new Date(order.created_at) : new Date();
        const jam = t.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        const tgl = t.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        const body = `
        <div class="k">
            <div class="head"><span class="t">TIKET DAPUR</span><span class="c">${esc(cafeName)}<br>${tgl} · ${jam}</span></div>
            <div class="meja"><div><small>MEJA</small><b>${esc(order.nomor_meja || '-')}</b></div><div class="no"><small>PESANAN</small><b>#${order.id}</b></div></div>
            <div class="info"><span>a.n. <b>${esc(order.nama_pemesan || '-')}</b></span><span>${esc(order.metode_pembayaran || '')}</span></div>
            <ul>${items.map(it => `<li><span class="q">${it.jumlah}</span><span class="n">${esc(it.nama_menu || 'Item')}${it.catatan ? `<span class="cn">» ${esc(it.catatan)}</span>` : ''}</span></li>`).join('')}</ul>
            <div class="sum"><span>Total item</span><span>${items.reduce((s, i) => s + Number(i.jumlah || 0), 0)}</span></div>
            <div class="box">Catatan dapur:</div>
            <div class="foot">Dicetak ${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })} · Dinezy</div>
        </div>`;
        printHTML(`Dapur #${order.id}`, dapurCSS(p), body, '');
    }

    window.DZPrint = { qrMeja, nota, dapur };
})();
