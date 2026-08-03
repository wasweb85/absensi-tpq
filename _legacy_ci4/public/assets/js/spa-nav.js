/**
 * spa-nav.js — Navigasi SPA tanpa reload untuk CodeIgniter 4
 * Fetch API · fade+slide · pushState · popstate · loading overlay · active menu
 */
(function () {
    'use strict';

    /* ── Boot guard: pastikan hanya jalan 1x ───────────────────── */
    if (window.__spaNavBooted) return;
    window.__spaNavBooted = true;

    /* ── Konfigurasi ─────────────────────────────────────────────── */
    var CONTENT_ID    = 'spa-content';
    var LOADING_ID    = 'spa-loading';
    var NAV_ATTR      = 'data-ajax-nav';
    var ACTIVE_CLASS  = 'active';
    var FADE_OUT_MS   = 150;   // durasi fade-out (ms)
    var MIN_LOAD_MS   = 220;   // minimum tampil loading (anti-flicker)
    var LOADING_DELAY = 70;    // delay sebelum spinner muncul (ms)

    /* ── State ───────────────────────────────────────────────────── */
    var isLoading = false;          // flag: tolak fetch baru jika masih loading
    var pageCache = {};             // cache: url → html
    var loadTimer = null;

    /* ── Referensi elemen ────────────────────────────────────────── */
    var contentEl = null;
    var loadingEl = null;

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       LOADING OVERLAY
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function showLoading() {
        clearTimeout(loadTimer);
        loadTimer = setTimeout(function () {
            if (loadingEl) loadingEl.classList.add('spa-loading-visible');
        }, LOADING_DELAY);
    }

    function hideLoading() {
        clearTimeout(loadTimer);
        loadTimer = null;
        if (loadingEl) loadingEl.classList.remove('spa-loading-visible');
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       TRANSISI: fade + slide
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function fadeOut(cb) {
        if (!contentEl) { cb(); return; }
        contentEl.style.transition = 'opacity ' + FADE_OUT_MS + 'ms ease, transform ' + FADE_OUT_MS + 'ms ease';
        contentEl.style.opacity    = '0';
        contentEl.style.transform  = 'translateY(10px)';
        setTimeout(cb, FADE_OUT_MS);
    }

    function fadeIn() {
        if (!contentEl) return;
        contentEl.style.transition = 'opacity 280ms ease, transform 280ms ease';
        contentEl.style.opacity    = '1';
        contentEl.style.transform  = 'translateY(0)';
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       INJECT CONTENT — innerHTML = '' sebelum setiap inject
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function setContent(html) {
        contentEl.innerHTML = '';       // kosongkan dulu (cegah duplikasi)
        contentEl.innerHTML = html;     // inject baru
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       EKSTRAK KONTEN dari respons server
       Jika server mengembalikan full HTML (ada sidebar/navbar di dalamnya),
       ekstrak hanya bagian #spa-content agar tidak terjadi duplikasi.
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function extractContent(html) {
        // Respons sudah partial (tidak ada tag <html>) — langsung pakai
        if (!/<html[\s>]/i.test(html) && !/<!DOCTYPE/i.test(html)) {
            return html;
        }
        // Respons full HTML — ekstrak #spa-content + script page-specific
        var parser  = new DOMParser();
        var doc     = parser.parseFromString(html, 'text/html');
        var spaEl   = doc.getElementById(CONTENT_ID);
        var content = spaEl ? spaEl.innerHTML : '';

        // Kumpulkan script page-specific (bukan framework)
        var SKIP = ['jquery','bootstrap','popper','material-dashboard',
                    'perfect-scrollbar','nouislider','plugins.js',
                    'custom.js','spa-nav.js','file-uploader'];
        var scripts = '';
        doc.querySelectorAll('body script').forEach(function (s) {
            if (s.src) {
                var skip = SKIP.some(function (k) { return s.src.indexOf(k) !== -1; });
                if (!skip) scripts += '<script src="' + s.src + '"><\/script>';
            } else {
                var code = s.textContent.trim();
                if (code && code.indexOf('BaseConfig') === -1) {
                    scripts += '<script>' + code + '<\/script>';
                }
            }
        });
        // Style page-specific
        var styles = '';
        doc.querySelectorAll('head style, body style').forEach(function (s) {
            styles += '<style>' + s.textContent + '</style>';
        });
        return styles + content + scripts;
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       EKSEKUSI SCRIPT di konten yang baru di-inject
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function runScripts(container) {
        container.querySelectorAll('script').forEach(function (old) {
            var s = document.createElement('script');
            Array.from(old.attributes).forEach(function (a) { s.setAttribute(a.name, a.value); });
            if (old.src) {
                // Eksternal: skip jika sudah ada
                if (!document.querySelector('script[src="' + old.src + '"]')) {
                    document.head.appendChild(s);
                }
            } else {
                s.textContent = old.textContent;
                document.body.appendChild(s);
                document.body.removeChild(s);
            }
            old.parentNode && old.parentNode.removeChild(old);
        });
    }

    /**
     * MODAL RELOCATION
     * Bootstrap modals outside body can have backdrop/stacking context issues.
     * Move them to body after AJAX inject.
     */
    function relocateModals(container) {
        container.querySelectorAll('.modal').forEach(function (m) {
            // Move to body if not already there
            if (m.parentNode !== document.body) {
                document.body.appendChild(m);
            }
        });
    }

    /**
     * BACKDROP CLEANUP
     * Remove any leftover Bootstrap modal backdrops and reset body state.
     */
    function cleanupModals() {
        // Remove backdrops
        document.querySelectorAll('.modal-backdrop').forEach(function (b) {
            b.parentNode && b.parentNode.removeChild(b);
        });
        // Remove modals moved to body (optional: if we want to avoid DOM pollution)
        // However, Bootstrap needs the modal div to exist for animations.
        // Let's just reset body classes.
        document.body.classList.remove('modal-open');
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       ACTIVE MENU — hapus dari semua, tambah ke yang diklik
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function setActiveMenu(url) {
        var path;
        try { path = new URL(url, location.origin).pathname; } catch (e) { path = url; }
        document.querySelectorAll('[' + NAV_ATTR + ']').forEach(function (link) {
            var href = link.getAttribute('href') || '';
            var lp;
            try { lp = new URL(href, location.origin).pathname; } catch (e) { lp = href; }
            var active = (lp && lp !== '/' && path.startsWith(lp)) || lp === path;
            link.classList.toggle(ACTIVE_CLASS, active);
        });
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       NAVBAR TITLE — update per navigasi tanpa reload
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function updateNavTitle() {
        var brand = document.querySelector('.navbar-brand');
        if (!brand || !contentEl) return;
        // Prioritas: data-page-title attr → h4.card-header → h4 → h3
        var src = contentEl.querySelector('[data-page-title]');
        if (src) { brand.innerHTML = '<b>' + src.getAttribute('data-page-title') + '</b>'; return; }
        var h = contentEl.querySelector('.card-header h4, h4, h3, h2');
        if (h) brand.innerHTML = '<b>' + h.innerText.trim() + '</b>';
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       ERROR — tampilkan pesan jika fetch gagal (jangan biarkan kosong)
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function errorHTML(msg) {
        return '<div class="content"><div class="container-fluid" style="padding-top:60px">'
             + '<div class="row justify-content-center"><div class="col-md-6 text-center">'
             + '<i class="material-icons text-danger" style="font-size:54px">error_outline</i>'
             + '<h4 class="mt-3">' + (msg || 'Halaman tidak ditemukan') + '</h4>'
             + '<p class="text-muted">Silakan kembali ke halaman sebelumnya.</p>'
             + '</div></div></div></div>';
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       NAVIGATE — fungsi utama navigasi AJAX
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    function navigateTo(url, pushToHistory) {
        // [1] Tolak jika masih loading
        if (isLoading) return;

        // [2] Jika URL sama, skip
        try {
            var tgt = new URL(url, location.origin);
            if (tgt.pathname === location.pathname && tgt.search === location.search) return;
        } catch (e) {}

        isLoading = true;
        var startTime = Date.now();

        // [3] Tampilkan loading SEBELUM fetch
        showLoading();

        // [4] Fade out konten lama
        fadeOut(function () {

            // [SAFE] Cleanup modals and backdrops before navigation
            cleanupModals();

            // Kosongkan area konten
            if (contentEl) {
                contentEl.style.transition = 'none';
                contentEl.innerHTML = '';
            }

            // [5] Cache hit → inject langsung
            if (pageCache[url]) {
                renderContent(pageCache[url], url, pushToHistory, startTime);
                return;
            }

            // [6] Fetch konten dari server
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-SPA-Nav': '1'
                },
                credentials: 'same-origin'
            })
            .then(function (response) {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.text();
            })
            .then(function (html) {
                // Ekstrak hanya bagian konten (safety net anti-duplikasi)
                var clean = extractContent(html);
                pageCache[url] = clean;
                renderContent(clean, url, pushToHistory, startTime);
            })
            .catch(function (err) {
                console.error('[SPA]', err);
                var wait = Math.max(0, MIN_LOAD_MS - (Date.now() - startTime));
                setTimeout(function () {
                    if (contentEl) contentEl.innerHTML = errorHTML('Gagal memuat halaman');
                    hideLoading();
                    fadeIn();
                    isLoading = false;
                }, wait);
            });
        });
    }

    /* ── Render setelah fetch selesai ─────────────────────────────── */
    function renderContent(html, url, pushToHistory, startTime) {
        // Pastikan loading minimal MIN_LOAD_MS (anti-flicker)
        var elapsed = Date.now() - startTime;
        var wait    = Math.max(0, MIN_LOAD_MS - elapsed);

        setTimeout(function () {
            try {
                // [7] Inject konten baru (replace, bukan append)
                setContent(html);

                // [7.5] Relocate modals to body to fix stacking context issues
                relocateModals(contentEl);

                // [8] Jalankan script di dalam konten baru
                runScripts(contentEl);

                // [9] Update URL tanpa reload
                if (pushToHistory !== false) {
                    history.pushState({ spaUrl: url }, '', url);
                }

                // [10] Update judul navbar sesuai halaman baru
                updateNavTitle();

                // [11] Update active menu
                setActiveMenu(url);

                // [11] Reinit Bootstrap
                if (typeof $ !== 'undefined' && $.fn.bootstrapMaterialDesign) {
                    try {
                        $(document).bootstrapMaterialDesign();
                    } catch (e) {
                        console.warn('[SPA] Bootstrap reinit error:', e);
                    }
                }

                // [12] Scroll ke atas
                var panel = document.querySelector('.main-panel');
                if (panel) panel.scrollTop = 0; else window.scrollTo(0, 0);

                // [13] Tutup mobile sidebar jika terbuka
                var sb = document.getElementById('appSidebar');
                if (sb && sb.classList.contains('mobile-open')) {
                    sb.classList.remove('mobile-open');
                    var ov = document.getElementById('sidebarOverlay');
                    if (ov) ov.classList.remove('active');
                    document.body.style.overflow = '';
                }

                document.dispatchEvent(new CustomEvent('spa:loaded', { detail: { url: url } }));

            } catch (err) {
                console.error('[SPA] Render error:', err);
                if (contentEl) contentEl.innerHTML = errorHTML('Terjadi kesalahan saat merender halaman');
            } finally {
                // [14] Sembunyikan loading → fade in konten baru (ALWAYS run this)
                hideLoading();
                fadeIn();
                isLoading = false;
            }
        }, wait);
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       EVENT LISTENER — satu delegation global, daftarkan 1x saja
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    document.addEventListener('click', function (e) {
        // Cari ancestor terdekat dengan data-ajax-nav
        var link = e.target.closest('[' + NAV_ATTR + ']');
        if (!link) return;

        // Biarkan Ctrl/Cmd/Shift+klik (buka tab baru, dll) berjalan normal
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;

        var href = link.getAttribute('href');
        if (!href || href === '#' || href.startsWith('javascript:')) return;

        // Biarkan link ke domain lain berjalan normal
        try {
            if (new URL(href, location.origin).origin !== location.origin) return;
        } catch (err) { return; }

        // ★ KRITIKAL: blok default browser navigation
        e.preventDefault();
        e.stopPropagation();

        navigateTo(href, true);
    }, true /* capture phase */);

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       POPSTATE — tombol Back / Forward browser
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    window.addEventListener('popstate', function (e) {
        var url = (e.state && e.state.spaUrl) || location.href;
        navigateTo(url, false);
    });

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
       INIT — saat halaman pertama kali load
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    document.addEventListener('DOMContentLoaded', function () {
        contentEl = document.getElementById(CONTENT_ID);
        loadingEl = document.getElementById(LOADING_ID);

        if (!contentEl) {
            console.warn('[SPA] #' + CONTENT_ID + ' tidak ditemukan — navigasi SPA dinonaktifkan.');
            return;
        }

        // Konten awal sudah tampil (server-rendered), pastikan visible
        contentEl.style.opacity   = '1';
        contentEl.style.transform = 'translateY(0)';

        // Simpan URL saat ini ke history state agar popstate berfungsi
        history.replaceState({ spaUrl: location.href }, '', location.href);

        // Set active menu sesuai URL saat ini
        setActiveMenu(location.href);
    });

})();
