/**
 * Exam Session Module
 *
 * Manages async answer saving, navigation without reload, finish via JSON,
 * and anti-cheat state machine with telemetry.
 */
export default function ExamSession(config) {
    const {
        ujianId,
        csrfToken,
        sessionUrl,
        answerUrl,
        finishUrl,
        violationUrl,
        resultUrl,
        warningLimit,
        hiddenThresholdMs,
    } = config;

    // ─── State ───
    let soals = [];
    let jawabanAll = {};
    let currentIndex = 0;
    let peringatan = 0;
    let isFinished = false;

    // Anti-cheat state machine
    const STATE = { ACTIVE: 'active', HIDDEN_PENDING: 'hidden_pending', HIDDEN_COUNTED: 'hidden_counted', INTERNAL_NAV: 'internal_navigation', FINISHED: 'finished' };
    let acState = STATE.ACTIVE;
    let pageSessionId = crypto.randomUUID ? crypto.randomUUID() : Date.now().toString(36) + Math.random().toString(36).slice(2);
    let hasUserInteracted = false;
    let internalNavigation = false;
    let hiddenAt = 0;
    let hiddenCycleId = null;
    let lastViolationSentAt = 0;
    let traceBuffer = [];
    let hiddenTimer = null;

    // ─── DOM refs ───
    const soalContainer = document.getElementById('exam-soal-container');
    const navContainer = document.getElementById('exam-nav-container');
    const soalNumber = document.getElementById('exam-soal-number');
    const soalTotal = document.getElementById('exam-soal-total');
    const prevBtn = document.getElementById('exam-prev-btn');
    const nextBtn = document.getElementById('exam-next-btn');
    const finishBtnInline = document.getElementById('exam-finish-btn-inline');
    const warningBadge = document.getElementById('warning-badge');
    const warningCount = document.getElementById('warning-count');

    // ─── Helpers ───
    function headers() {
        return { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' };
    }

    function showWarningBanner(message, isFatal) {
        let banner = document.getElementById('violation-banner');
        if (!banner) {
            banner = document.createElement('div');
            banner.id = 'violation-banner';
            banner.style.cssText = 'position:fixed;top:0;left:0;right:0;z-index:9999;padding:16px 20px;text-align:center;font-weight:bold;font-size:14px;transition:opacity 0.3s;';
            document.body.appendChild(banner);
        }
        banner.style.background = isFatal ? '#dc2626' : '#f59e0b';
        banner.style.color = isFatal ? '#fff' : '#1f2937';
        banner.textContent = message;
        banner.style.opacity = '1';
        if (!isFatal) {
            setTimeout(() => { banner.style.opacity = '0'; }, 4000);
        }
    }

    function updateWarningBadge() {
        if (warningBadge && warningCount) {
            if (peringatan > 0) {
                warningBadge.classList.remove('hidden');
                warningCount.innerText = peringatan;
            }
        }
    }

    function fixImagePaths(container) {
        const appUrl = window.__examAppUrl || '';
        container.querySelectorAll('.soal-content img, .jawaban-content img').forEach(img => {
            let src = img.getAttribute('src');
            if (src && !src.startsWith('http') && !src.startsWith('/') && !src.startsWith('data:')) {
                img.src = appUrl + '/' + src;
            }
        });
    }

    // ─── Render ───
    function renderSoal() {
        const soal = soals[currentIndex];
        if (!soal) return;

        soalNumber.textContent = currentIndex + 1;

        // Build question HTML
        const opsiEntries = Object.entries(soal.opsi).filter(([, v]) => v);
        const currentJawaban = jawabanAll[soal.id] || null;

        let html = `<div class="soal-content text-gray-800 text-lg font-medium leading-relaxed mb-8 prose max-w-none">${soal.pertanyaan}</div>`;
        html += '<div class="space-y-4">';
        for (const [key, value] of opsiEntries) {
            const checked = currentJawaban === key;
            html += `
                <label class="cursor-pointer block group" data-jawaban="${key}">
                    <div class="flex items-start gap-4 p-4 rounded-xl border transition-all duration-200
                        ${checked ? 'bg-[#fefce8] border-[#ffc800] shadow-[0_0_0_1px_#ffc800_inset]' : 'border-gray-200 hover:border-[#ffc800] hover:bg-yellow-50/50'}">
                        <div class="radio-dot w-8 h-8 rounded-lg flex items-center justify-center shrink-0 text-sm font-bold transition-colors
                            ${checked ? 'bg-[#ffc800] border-[#ffc800] text-white' : 'bg-gray-100 border border-gray-300 text-gray-500 group-hover:bg-[#ffc800] group-hover:text-white group-hover:border-[#ffc800]'}">
                            ${key}
                        </div>
                        <div class="jawaban-content text-gray-700 text-sm md:text-base prose max-w-none pt-1">${value}</div>
                    </div>
                </label>`;
        }
        html += '</div>';

        soalContainer.innerHTML = html;
        fixImagePaths(soalContainer);

        // Answer click handlers
        soalContainer.querySelectorAll('[data-jawaban]').forEach(label => {
            label.addEventListener('click', () => saveAnswer(soal.id, label.dataset.jawaban));
        });

        // Navigation buttons
        prevBtn.disabled = currentIndex === 0;
        prevBtn.classList.toggle('opacity-50', currentIndex === 0);
        prevBtn.classList.toggle('cursor-not-allowed', currentIndex === 0);

        if (currentIndex < soals.length - 1) {
            nextBtn.classList.remove('hidden');
            if (finishBtnInline) finishBtnInline.classList.add('hidden');
        } else {
            nextBtn.classList.add('hidden');
            if (finishBtnInline) finishBtnInline.classList.remove('hidden');
        }

        renderNav();
    }

    function renderNav() {
        navContainer.innerHTML = soals.map((s, i) => {
            const answered = !!jawabanAll[s.id];
            const active = i === currentIndex;
            let cls = 'w-full aspect-square flex items-center justify-center rounded-lg border text-sm font-bold transition-all duration-200 ';
            if (active) {
                cls += 'bg-[#ffc800] text-white border-[#ffc800] ring-2 ring-yellow-200 font-extrabold scale-110 shadow-md';
            } else if (answered) {
                cls += 'bg-green-500 text-white border-green-500 hover:bg-green-600';
            } else {
                cls += 'bg-white text-gray-500 border-gray-200 hover:border-gray-400 hover:bg-gray-50';
            }
            return `<button class="${cls}" data-nav-index="${i}">${i + 1}</button>`;
        }).join('');

        navContainer.querySelectorAll('[data-nav-index]').forEach(btn => {
            btn.addEventListener('click', () => {
                goToSoal(parseInt(btn.dataset.navIndex));
            });
        });
    }

    // ─── Actions ───
    async function saveAnswer(soalId, jawaban) {
        internalNavigation = true;
        try {
            const res = await fetch(answerUrl, {
                method: 'POST',
                headers: headers(),
                body: JSON.stringify({ soal_id: soalId, jawaban }),
            });
            const data = await res.json();
            if (data.status === 'finished') {
                window.location.href = resultUrl;
                return;
            }
            if (data.jawaban_all) {
                jawabanAll = data.jawaban_all;
            }
            renderSoal();
        } catch (err) {
            console.error('Gagal simpan jawaban', err);
        } finally {
            internalNavigation = false;
        }
    }

    function goToSoal(index) {
        if (index < 0 || index >= soals.length) return;
        internalNavigation = true;
        currentIndex = index;
        renderSoal();
        // Push URL without reload
        const url = new URL(window.location);
        url.pathname = url.pathname.replace(/\/kerjakan\/?\d*$/, `/kerjakan/${index}`);
        history.replaceState(null, '', url);
        internalNavigation = false;
    }

    async function doFinish() {
        if (isFinished) return;
        internalNavigation = true;
        try {
            const res = await fetch(finishUrl, {
                method: 'POST',
                headers: headers(),
                body: JSON.stringify({}),
            });
            const data = await res.json();
            isFinished = true;
            acState = STATE.FINISHED;
            window.location.href = data.result_url || resultUrl;
        } catch (err) {
            console.error('Gagal finish ujian', err);
        } finally {
            internalNavigation = false;
        }
    }

    // ─── Anti-Cheat State Machine ───
    function traceEvent(type, details) {
        traceBuffer.push({ type, ts: Date.now(), ...details });
        if (traceBuffer.length > 50) traceBuffer.shift();
    }

    async function sendViolationEvent(eventType, extraPayload) {
        if (isFinished || acState === STATE.FINISHED) return;
        const now = Date.now();
        const eventId = pageSessionId + '-' + now;

        const body = {
            ujian_id: ujianId,
            event_id: eventId,
            page_session_id: pageSessionId,
            event_type: eventType,
            trigger: eventType,
            visibility_state: document.visibilityState,
            client_ts: now,
            hidden_duration_ms: extraPayload?.hidden_duration_ms || null,
            internal_navigation: internalNavigation,
            has_user_interacted: hasUserInteracted,
            hidden_cycle_id: hiddenCycleId,
            trace_payload: { hidden_cycle_id: hiddenCycleId, recent_trace: traceBuffer.slice(-10) },
            url: location.href,
            ...extraPayload,
        };

        lastViolationSentAt = now;

        try {
            const res = await fetch(violationUrl, {
                method: 'POST',
                headers: headers(),
                body: JSON.stringify(body),
            });
            const data = await res.json();

            if (data.status === 'skipped') return;

            peringatan = data.peringatan ?? peringatan;
            updateWarningBadge();

            if (data.status === 'finished') {
                isFinished = true;
                acState = STATE.FINISHED;
                showWarningBanner(`PELANGGARAN BERAT! Ujian dihentikan otomatis karena ${warningLimit}x meninggalkan halaman.`, true);
                setTimeout(() => { window.location.href = resultUrl; }, 1500);
            } else if (data.status === 'warning') {
                showWarningBanner(`PERINGATAN (${peringatan}/${warningLimit}) — Dilarang meninggalkan halaman ujian!`, false);
            }
        } catch (err) {
            console.error('Gagal kirim violation event', err);
        }
    }

    function onHidden() {
        if (acState === STATE.FINISHED || isFinished) return;
        traceEvent('hidden', { visibility: document.visibilityState });

        if (internalNavigation) {
            traceEvent('hidden_during_internal_nav');
            return;
        }

        hiddenAt = Date.now();
        hiddenCycleId = pageSessionId + '-hc-' + hiddenAt;
        acState = STATE.HIDDEN_PENDING;

        // Start threshold timer
        hiddenTimer = setTimeout(() => {
            if (acState !== STATE.HIDDEN_PENDING) return;
            if (!hasUserInteracted) return;

            acState = STATE.HIDDEN_COUNTED;
            const duration = Date.now() - hiddenAt;
            sendViolationEvent('exit_candidate', { hidden_duration_ms: duration });
        }, hiddenThresholdMs);
    }

    function onVisible() {
        traceEvent('visible', { visibility: document.visibilityState, prevState: acState });

        if (hiddenTimer) {
            clearTimeout(hiddenTimer);
            hiddenTimer = null;
        }

        if (acState === STATE.HIDDEN_PENDING) {
            // Came back before threshold — no violation
            const duration = Date.now() - hiddenAt;
            traceEvent('hidden_short', { duration_ms: duration });
        }

        if (acState !== STATE.FINISHED) {
            acState = STATE.ACTIVE;
        }
    }

    // ─── Event Listeners ───
    function setupInteractionDetection() {
        ['click', 'keydown', 'touchstart', 'scroll'].forEach(evt => {
            document.addEventListener(evt, function onFirst() {
                hasUserInteracted = true;
                document.removeEventListener(evt, onFirst);
            }, { once: true });
        });
    }

    function setupVisibilityListeners() {
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                onHidden();
            } else {
                onVisible();
            }
        });

        window.addEventListener('pagehide', () => {
            traceEvent('pagehide', { visibility: document.visibilityState });
        });

        window.addEventListener('pageshow', () => {
            traceEvent('pageshow', { visibility: document.visibilityState });
            if (acState !== STATE.FINISHED) acState = STATE.ACTIVE;
        });

        window.addEventListener('focus', () => {
            traceEvent('focus');
        });

        window.addEventListener('blur', () => {
            traceEvent('blur', { docHidden: document.hidden });
        });
    }

    function setupNavigation() {
        prevBtn.addEventListener('click', () => goToSoal(currentIndex - 1));
        nextBtn.addEventListener('click', () => goToSoal(currentIndex + 1));
        if (finishBtnInline) {
            finishBtnInline.addEventListener('click', () => {
                if (confirm('Yakin ingin mengakhiri ujian? Pastikan semua jawaban sudah terisi.')) {
                    doFinish();
                }
            });
        }

        const formSelesai = document.getElementById('form-selesai');
        if (formSelesai) {
            formSelesai.addEventListener('submit', (e) => {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin mengakhiri ujian ini? Jawaban tidak dapat diubah setelah ini.')) {
                    doFinish();
                }
            });
        }

        // Back button guard
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.pushState(null, null, location.href);
            if (confirm("Apakah Anda ingin keluar dari ujian dan melihat hasil?\n\nMenekan tombol 'Back' dianggap mengakhiri ujian.")) {
                doFinish();
            }
        };
    }

    // ─── Init ───
    async function init() {
        setupInteractionDetection();
        setupVisibilityListeners();

        try {
            const res = await fetch(sessionUrl, { headers: headers() });
            const data = await res.json();

            if (data.status === 'finished') {
                window.location.href = data.result_url || resultUrl;
                return;
            }

            soals = data.soals || [];
            peringatan = data.peringatan || 0;

            // Build jawabanAll from soals data
            soals.forEach(s => {
                if (s.jawaban) jawabanAll[s.id] = s.jawaban;
            });

            soalTotal.textContent = soals.length;
            updateWarningBadge();
            renderSoal();
            setupNavigation();
        } catch (err) {
            console.error('Gagal load session', err);
        }
    }

    return { init, goToSoal, doFinish };
}
