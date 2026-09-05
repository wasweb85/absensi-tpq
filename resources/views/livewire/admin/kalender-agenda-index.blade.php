<div class="content" style="padding: 1.25rem !important;">
    <div class="container-fluid" style="padding: 0 !important; max-width: 100%;">

        {{-- CSS Terpadu Kanvas Kaca & Standalone Reset (Anti-Conflict Bootstrap/Tailwind) --}}
        <style>
            .un-wrapper {
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                color: #1e293b;
            }

            /* --- 1. HERO BANNER --- */
            .un-hero {
                background: linear-gradient(135deg, #4f14e2 0%, #6d28d9 45%, #7c3aed 100%);
                border-radius: 20px;
                padding: 24px 30px;
                color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                margin-bottom: 20px;
                box-shadow: 0 10px 25px -5px rgba(109, 40, 217, 0.35);
                position: relative;
                overflow: hidden;
            }
            .un-hero-bg-glow {
                position: absolute;
                right: -40px;
                top: -40px;
                width: 220px;
                height: 220px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                filter: blur(40px);
                pointer-events: none;
            }
            .un-hero-left {
                display: flex;
                align-items: center;
                gap: 16px;
                position: relative;
                z-index: 2;
            }
            .un-hero-icon {
                width: 52px;
                height: 52px;
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.18);
                border: 1px solid rgba(255, 255, 255, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.2);
            }
            .un-hero-title-wrap {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }
            .un-hero-title {
                font-size: 24px;
                font-weight: 800;
                color: #ffffff;
                margin: 0;
                letter-spacing: -0.5px;
            }
            .un-hero-badge {
                background: rgba(255, 255, 255, 0.22);
                border: 1px solid rgba(255, 255, 255, 0.35);
                color: #ffffff;
                font-size: 11px;
                font-weight: 700;
                padding: 2px 10px;
                border-radius: 20px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }
            .un-hero-desc {
                font-size: 13px;
                color: rgba(255, 255, 255, 0.88);
                margin: 4px 0 0 0;
                font-weight: 400;
                max-width: 650px;
                line-height: 1.4;
            }

            /* Toggle Switch Bulanan | Tahunan */
            .un-toggle-group {
                background: rgba(0, 0, 0, 0.25);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 30px;
                padding: 4px;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                position: relative;
                z-index: 2;
                flex-shrink: 0;
            }
            .un-toggle-btn {
                padding: 8px 24px;
                border-radius: 24px;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 0.6px;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                outline: none !important;
            }
            .un-toggle-btn.active {
                background: #ffffff !important;
                color: #5a20cb !important;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15) !important;
            }
            .un-toggle-btn.inactive {
                background: transparent !important;
                color: rgba(255, 255, 255, 0.85) !important;
            }
            .un-toggle-btn.inactive:hover {
                color: #ffffff !important;
                background: rgba(255, 255, 255, 0.1) !important;
            }

            /* --- 2. SEARCH & FILTER BAR --- */
            .un-filter-bar {
                background: #ffffff;
                border: 1px solid #eef2f6;
                border-radius: 18px;
                padding: 10px 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                margin-bottom: 22px;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.025);
                flex-wrap: wrap;
            }
            .un-search-box {
                display: flex;
                align-items: center;
                gap: 10px;
                flex: 1;
                min-width: 260px;
            }
            .un-search-icon {
                color: #94a3b8;
                font-size: 20px;
            }
            .un-search-input {
                border: none !important;
                outline: none !important;
                box-shadow: none !important;
                background: transparent !important;
                font-size: 13.5px;
                color: #1e293b;
                width: 100%;
                padding: 6px 0;
            }
            .un-search-input::placeholder {
                color: #94a3b8;
            }
            .un-filter-actions {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }
            .un-pill {
                padding: 6px 16px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                border: 1px solid #e2e8f0;
                background: #ffffff;
                color: #475569;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                transition: all 0.15s ease;
                outline: none !important;
            }
            .un-pill:hover {
                background: #f8fafc;
                border-color: #cbd5e1;
            }
            .un-pill.active {
                background: #0f172a !important;
                color: #ffffff !important;
                border-color: #0f172a !important;
            }
            .un-btn-add {
                background: linear-gradient(135deg, #6d28d9 0%, #4f46e5 100%);
                color: #ffffff !important;
                border: none;
                border-radius: 14px;
                padding: 8px 18px;
                font-size: 12.5px;
                font-weight: 700;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                box-shadow: 0 4px 12px rgba(109, 40, 217, 0.25);
                transition: all 0.2s ease;
                outline: none !important;
                white-space: nowrap;
            }
            .un-btn-add:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 16px rgba(109, 40, 217, 0.35);
            }

            /* --- 3. DUA KOLOM UTAMA --- */
            .un-layout {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) 300px !important;
                gap: 16px !important;
                align-items: start !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .un-layout > div {
                min-width: 0 !important;
                max-width: 100% !important;
            }
            @media (max-width: 1100px) {
                .un-layout {
                    grid-template-columns: 1fr !important;
                }
            }

            /* --- 4. PERIODE AKADEMIK AKTIF --- */
            .un-active-period {
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
                border-radius: 14px;
                padding: 10px 16px;
                margin-bottom: 14px;
            }
            .un-period-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 8px;
                gap: 10px;
            }
            .un-period-title {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 11.5px;
                font-weight: 800;
                color: #047857;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                margin: 0;
            }
            .un-live-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #10b981;
                display: inline-block;
                animation: un-pulse 2s infinite ease-in-out;
            }
            @keyframes un-pulse {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.4; transform: scale(1.2); }
            }
            .un-btn-toggle-holiday {
                background: #ffffff;
                border: 1px solid #a7f3d0;
                color: #047857;
                font-size: 10.5px;
                font-weight: 700;
                padding: 3px 12px;
                border-radius: 20px;
                cursor: pointer;
                outline: none !important;
                transition: all 0.15s ease;
            }
            .un-btn-toggle-holiday:hover {
                background: #dcfce7;
            }
            .un-period-items {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }
            .un-period-chip {
                background: #ffffff;
                border: 1px solid #bbf7d0;
                border-radius: 10px;
                padding: 5px 12px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 11.5px;
                font-weight: 600;
                color: #1e293b;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            }
            .un-period-date-badge {
                background: #dcfce7;
                color: #047857;
                font-size: 10.5px;
                font-weight: 600;
                padding: 1.5px 6px;
                border-radius: 5px;
            }

            /* --- 5. KALENDER BULANAN (GRID TERPADU) --- */
            .un-cal-nav {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                margin-bottom: 12px;
            }
            .un-nav-arrow {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                border: 1px solid #e2e8f0;
                background: #ffffff;
                color: #64748b;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                outline: none !important;
                transition: all 0.15s ease;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            }
            .un-nav-arrow:hover {
                border-color: #7c3aed;
                color: #7c3aed;
            }
            .un-month-title {
                font-size: 19px;
                font-weight: 800;
                color: #1e293b;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .un-year-badge {
                background: #ede9fe;
                color: #6d28d9;
                font-size: 12.5px;
                font-weight: 800;
                padding: 2px 8px;
                border-radius: 6px;
            }

            .un-cal-card {
                background: #ffffff;
                border: 1px solid #eef2f6;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
                width: 100% !important;
                max-width: 100% !important;
            }
            .un-cal-header-row {
                display: grid !important;
                grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
                padding: 10px 0 !important;
                border-bottom: 1px solid #eef2f6;
                text-align: center;
                background: #ffffff;
                width: 100% !important;
            }
            .un-day-name {
                font-size: 11.5px;
                font-weight: 700;
                color: #64748b;
                letter-spacing: 0.5px;
            }
            .un-day-name.red {
                color: #e11d48 !important;
                font-weight: 800;
            }
            .un-jum-badge {
                background: #ffe4e6;
                color: #e11d48;
                border-radius: 5px;
                padding: 2px 6px;
                font-size: 10.5px;
                font-weight: 800;
                display: inline-flex;
                align-items: center;
                gap: 3px;
            }
            .un-jum-libur-tag {
                background: #e11d48;
                color: #ffffff;
                border-radius: 3px;
                font-size: 8.5px;
                padding: 1px 3px;
                line-height: 1;
                font-weight: 700;
            }

            .un-cal-body {
                display: grid !important;
                grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
                background: #ffffff;
                width: 100% !important;
            }
            .un-cell {
                border-right: 1px solid #f1f5f9;
                border-bottom: 1px solid #f1f5f9;
                min-height: 72px !important;
                max-height: 86px !important;
                padding: 4px 6px !important;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                background: #ffffff;
                position: relative;
                cursor: pointer;
                transition: background 0.15s ease;
                box-sizing: border-box !important;
                min-width: 0 !important;
                width: 100% !important;
                overflow: hidden !important;
            }
            .un-cell:nth-child(7n) {
                border-right: none;
            }
            .un-cell.is-holiday,
            .un-cell.is-friday {
                background: #fff8f8;
            }
            .un-cell.is-holiday:hover,
            .un-cell.is-friday:hover {
                background: #fff1f2;
            }
            .un-cell.other-month {
                background: #fbfcfd;
                opacity: 0.35;
            }
            .un-cell-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 3px;
                width: 100%;
            }
            .un-masehi-num {
                font-size: 12px;
                font-weight: 700;
                color: #1e293b;
            }
            .un-masehi-num.red {
                color: #e11d48 !important;
                font-weight: 800;
            }
            .un-today-badge {
                width: 20px;
                height: 20px;
                border-radius: 6px;
                background: #5a20cb;
                color: #ffffff;
                font-size: 11px;
                font-weight: 800;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 5px rgba(90, 32, 203, 0.3);
            }

            /* Event Chips */
            .un-event-chip {
                border-radius: 5px !important;
                padding: 1.5px 5px !important;
                font-size: 9.5px !important;
                line-height: 1.25 !important;
                font-weight: 600 !important;
                margin-bottom: 2px !important;
                display: flex !important;
                align-items: center !important;
                gap: 3px !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                max-width: 100% !important;
                min-width: 0 !important;
                cursor: pointer;
                transition: all 0.15s ease;
            }
            .un-event-chip span {
                display: block !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
                min-width: 0 !important;
            }
            .un-event-chip:hover {
                filter: brightness(0.95);
            }
            .un-event-chip.akademik {
                background: #ecfdf5;
                border: 1px solid #a7f3d0;
                color: #047857;
            }
            .un-event-chip.penting {
                background: #f5f3ff;
                border: 1px solid #ddd6fe;
                color: #6d28d9;
            }
            .un-event-chip.umum {
                background: #eff6ff;
                border: 1px solid #bfdbfe;
                color: #1d4ed8;
            }
            .un-event-chip.tugas {
                background: #fffbeb;
                border: 1px solid #fde68a;
                color: #b45309;
            }
            .un-event-chip.libur {
                background: #ffe4e6 !important;
                border: 1px solid #fecdd3 !important;
                color: #e11d48 !important;
                font-weight: 700 !important;
            }
            .un-libur-jumat-pill {
                background: #ffe4e6;
                color: #e11d48;
                border-radius: 4px;
                padding: 1px 6px;
                font-size: 9px;
                font-weight: 700;
                display: inline-block;
                margin-top: auto;
                margin-bottom: 2px;
                white-space: nowrap;
                align-self: flex-start;
                max-width: 100%;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* --- 6. KALENDER TAHUNAN (12 BULAN MINI) --- */
            .un-year-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 16px;
            }
            @media (max-width: 768px) {
                .un-year-grid {
                    grid-template-columns: 1fr;
                }
            }
            .un-mini-card {
                background: #ffffff;
                border: 1px solid #eef2f6;
                border-radius: 16px;
                padding: 14px;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .un-mini-card:hover {
                border-color: #7c3aed;
                box-shadow: 0 4px 16px rgba(124, 58, 237, 0.08);
            }
            .un-mini-card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-bottom: 8px;
                margin-bottom: 8px;
                border-bottom: 1px solid #f1f5f9;
            }
            .un-mini-title {
                font-size: 13px;
                font-weight: 800;
                color: #1e293b;
            }
            .un-now-badge {
                background: #5a20cb;
                color: #ffffff;
                font-size: 9px;
                font-weight: 800;
                padding: 2px 6px;
                border-radius: 4px;
            }
            .un-mini-day-headers {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                text-align: center;
                font-size: 10px;
                font-weight: 700;
                color: #94a3b8;
                margin-bottom: 4px;
            }
            .un-mini-day-headers span.red {
                color: #e11d48;
                font-weight: 800;
            }
            .un-mini-days-grid {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 2px;
                text-align: center;
                font-size: 10px;
            }
            .un-mini-cell {
                padding: 4px 0;
                border-radius: 6px;
                font-weight: 600;
                font-size: 10.5px;
                color: #475569;
                cursor: pointer;
                transition: all 0.15s ease;
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                user-select: none;
            }
            .un-mini-cell:hover {
                transform: scale(1.18);
                z-index: 5;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }
            .un-mini-cell.is-holiday,
            .un-mini-cell.red {
                color: #e11d48 !important;
                background: #fff1f2;
                font-weight: 800;
            }
            .un-mini-cell.is-holiday:hover,
            .un-mini-cell.red:hover {
                background: #ffe4e6 !important;
            }
            .un-mini-cell.has-event:not(.is-holiday):not(.red) {
                background: #eff6ff;
                color: #2563eb !important;
                font-weight: 800;
            }
            .un-mini-cell.has-event.is-holiday,
            .un-mini-cell.has-event.red {
                color: #e11d48 !important;
                background: #fee2e2 !important;
                font-weight: 800;
            }
            .un-mini-cell.has-event::after {
                content: '';
                position: absolute;
                bottom: 2px;
                left: 50%;
                transform: translateX(-50%);
                width: 3.5px;
                height: 3.5px;
                border-radius: 50%;
                background: currentColor;
            }
            .un-mini-cell.today {
                background: #5a20cb !important;
                color: #ffffff !important;
                font-weight: 800;
                box-shadow: 0 2px 6px rgba(90, 32, 203, 0.35);
            }
            .un-mini-cell.today::after {
                background: #ffffff;
            }

            /* --- 7. PANEL KANAN (STATISTIK & TIMELINE) --- */
            .un-side-card {
                background: #ffffff;
                border: 1px solid #eef2f6;
                border-radius: 18px;
                padding: 20px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
                margin-bottom: 22px;
            }
            .un-side-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 16px;
            }
            .un-side-header-left {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            .un-side-icon-box {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .un-side-icon-box.green {
                background: #ecfdf5;
                color: #059669;
            }
            .un-side-icon-box.purple {
                background: #f5f3ff;
                color: #7c3aed;
            }
            .un-side-title {
                font-size: 14px;
                font-weight: 800;
                color: #1e293b;
                margin: 0;
            }
            .un-side-subtitle {
                font-size: 11px;
                color: #94a3b8;
                margin: 0;
            }
            .un-live-badge {
                background: #ecfdf5;
                border: 1px solid #a7f3d0;
                color: #047857;
                border-radius: 20px;
                padding: 3px 10px;
                font-size: 11px;
                font-weight: 800;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            /* 3 Statistik Boxes */
            .un-stats-row {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                margin-top: 14px;
            }
            .un-stat-box {
                background: #ffffff;
                border: 1px solid #f1f5f9;
                border-radius: 14px;
                padding: 14px 6px;
                text-align: center;
            }
            .un-stat-icon-circle {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                margin: 0 auto 8px auto;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .un-stat-val {
                font-size: 26px;
                font-weight: 800;
                line-height: 1;
                letter-spacing: -0.5px;
            }
            .un-stat-lbl {
                font-size: 10px;
                font-weight: 700;
                color: #64748b;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                margin-top: 6px;
            }
            .un-stat-bar {
                width: 100%;
                height: 3px;
                border-radius: 2px;
                margin-top: 10px;
            }

            /* Timeline Events */
            .un-timeline-scroll {
                max-height: 440px;
                overflow-y: auto;
                padding-right: 4px;
            }
            .un-timeline-scroll::-webkit-scrollbar {
                width: 4px;
            }
            .un-timeline-scroll::-webkit-scrollbar-track {
                background: #f8fafc;
            }
            .un-timeline-scroll::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }
            .un-timeline-item {
                display: flex;
                align-items: flex-start;
                gap: 16px;
                padding: 12px 0;
                border-bottom: 1px solid #f8fafc;
                position: relative;
            }
            .un-timeline-item:last-child {
                border-bottom: none;
            }
            .un-time-col {
                min-width: 30px;
                text-align: center;
                flex-shrink: 0;
            }
            .un-time-day {
                font-size: 18px;
                font-weight: 800;
                line-height: 1;
                color: #1e293b;
            }
            .un-time-name {
                font-size: 10px;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                margin-top: 2px;
            }
            .un-time-info {
                flex: 1;
                min-width: 0;
            }
            .un-time-title {
                font-size: 13px;
                font-weight: 700;
                color: #1e293b;
                line-height: 1.3;
                margin: 0 0 4px 0;
            }
            .un-time-pill {
                font-size: 10px;
                font-weight: 700;
                padding: 2px 8px;
                border-radius: 6px;
                display: inline-block;
            }
            .un-time-actions {
                display: flex;
                align-items: center;
                gap: 4px;
                opacity: 0.85;
                transition: opacity 0.15s;
            }
            .un-timeline-item:hover .un-time-actions {
                opacity: 1;
            }
            .un-icon-btn {
                background: transparent;
                border: none;
                cursor: pointer;
                padding: 4px;
                border-radius: 6px;
                color: #94a3b8;
                outline: none !important;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: all 0.15s;
            }
            .un-icon-btn:hover {
                background: #f1f5f9;
                color: #6d28d9;
            }
            .un-icon-btn.danger {
                color: #f43f5e;
            }
            .un-icon-btn.danger:hover {
                background: #fff1f2;
                color: #e11d48;
            }

            /* Tombol Hapus pada Modal */
            .un-btn-delete-modal {
                padding: 8px 14px;
                border-radius: 10px;
                border: 1px solid #fecdd3;
                background: #fff1f2;
                color: #e11d48;
                font-weight: 700;
                font-size: 12.5px;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                transition: all 0.15s ease;
            }
            .un-btn-delete-modal:hover {
                background: #ffe4e6;
                border-color: #fda4af;
                color: #be123c;
                box-shadow: 0 2px 8px rgba(225, 29, 72, 0.15);
            }

            /* Banner Notifikasi Glassmorphic */
            .un-alert-success {
                background: #ecfdf5;
                border: 1px solid #a7f3d0;
                border-radius: 14px;
                padding: 12px 18px;
                margin-bottom: 18px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
            }
            .un-alert-error {
                background: #fef2f2;
                border: 1px solid #fecaca;
                border-radius: 14px;
                padding: 12px 18px;
                margin-bottom: 18px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
            }

            /* --- 8. MODAL KACA INTERAKTIF --- */
            .un-modal-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(8px);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 16px;
            }
            .un-modal-box {
                background: #ffffff;
                border-radius: 24px;
                width: 100%;
                max-width: 520px;
                overflow: hidden;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                border: 1px solid #e2e8f0;
            }
            .un-modal-header {
                background: linear-gradient(135deg, #6d28d9 0%, #4f46e5 100%);
                padding: 18px 24px;
                color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .un-modal-body {
                padding: 24px;
            }
            .un-form-label {
                display: block;
                font-size: 11.5px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #475569;
                margin-bottom: 6px;
            }
            .un-form-control {
                width: 100%;
                padding: 10px 14px;
                border-radius: 12px;
                border: 1px solid #cbd5e1 !important;
                background: #f8fafc;
                font-size: 13.5px;
                color: #1e293b;
                outline: none !important;
                transition: border-color 0.15s;
                box-sizing: border-box;
            }
            .un-form-control:focus {
                border-color: #6d28d9 !important;
                background: #ffffff;
            }
        </style>

        <div class="un-wrapper">

            {{-- NOTIFIKASI FLASH SUKSES / ERROR --}}
            @if (session()->has('success_message'))
                <div class="un-alert-success" x-data="{ show: true }" x-show="show" x-transition>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="material-icons" style="font-size: 20px; color: #059669;">check_circle</i>
                        <span style="font-size: 13px; font-weight: 600; color: #065f46;">{{ session('success_message') }}</span>
                    </div>
                    <button type="button" @click="show = false" style="background: transparent; border: none; color: #059669; cursor: pointer; font-size: 20px; line-height: 1;">&times;</button>
                </div>
            @endif

            @if (session()->has('error_message'))
                <div class="un-alert-error" x-data="{ show: true }" x-show="show" x-transition>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="material-icons" style="font-size: 20px; color: #dc2626;">error</i>
                        <span style="font-size: 13px; font-weight: 600; color: #991b1b;">{{ session('error_message') }}</span>
                    </div>
                    <button type="button" @click="show = false" style="background: transparent; border: none; color: #dc2626; cursor: pointer; font-size: 20px; line-height: 1;">&times;</button>
                </div>
            @endif

            {{-- 1. HERO HEADER BANNER --}}
            <div class="un-hero">
                <div class="un-hero-bg-glow"></div>
                <div class="un-hero-left">
                    <div class="un-hero-icon">
                        <i class="material-icons" style="font-size: 28px; color: #ffffff;">calendar_today</i>
                    </div>
                    <div>
                        <div class="un-hero-title-wrap">
                            <h1 class="un-hero-title">Kalender &amp; Agenda</h1>
                            <span class="un-hero-badge">TPQ SISTEM</span>
                        </div>
                        <p class="un-hero-desc">
                            Kelola agenda belajar, pantau libur nasional, dan jadwalkan tugas secara terpadu di dalam kanvas kaca.
                        </p>
                    </div>
                </div>

                {{-- Toggle Mode: BULANAN | TAHUNAN (Dynamic Active Styling) --}}
                <div class="un-toggle-group">
                    <button 
                        type="button" 
                        wire:click="changeViewMode('bulanan')"
                        class="un-toggle-btn {{ $viewMode === 'bulanan' ? 'active' : 'inactive' }}">
                        BULANAN
                    </button>
                    <button 
                        type="button" 
                        wire:click="changeViewMode('tahunan')"
                        class="un-toggle-btn {{ $viewMode === 'tahunan' ? 'active' : 'inactive' }}">
                        TAHUNAN
                    </button>
                </div>
            </div>

            {{-- 2. BILAH PENCARIAN & FILTER KATEGORI --}}
            <div class="un-filter-bar">
                {{-- Input Pencarian --}}
                <div class="un-search-box">
                    <i class="material-icons un-search-icon">search</i>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="searchQuery" 
                        placeholder="Cari kegiatan atau agenda..." 
                        class="un-search-input">
                </div>

                {{-- Kategori Filter Pills & Tombol Tambah --}}
                <div class="un-filter-actions">
                    <button 
                        type="button" 
                        wire:click="setCategory('semua')" 
                        class="un-pill {{ $selectedCategory === 'semua' ? 'active' : '' }}">
                        Semua
                    </button>
                    <button 
                        type="button" 
                        wire:click="setCategory('umum')" 
                        class="un-pill {{ $selectedCategory === 'umum' ? 'active' : '' }}">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #3b82f6; display: inline-block;"></span>
                        Umum
                    </button>
                    <button 
                        type="button" 
                        wire:click="setCategory('penting')" 
                        class="un-pill {{ $selectedCategory === 'penting' ? 'active' : '' }}">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #8b5cf6; display: inline-block;"></span>
                        Penting
                    </button>

                    {{-- Tombol Tambah Agenda (Khusus Admin / Petugas) --}}
                    @if ($isManageable)
                        <button 
                            type="button" 
                            wire:click="openCreateModal" 
                            class="un-btn-add"
                            title="Tambah Agenda Baru">
                            <i class="material-icons" style="font-size: 18px; line-height: 1;">add</i>
                            <span>Tambah Agenda</span>
                        </button>
                    @endif
                </div>
            </div>

            {{-- 3. GRID 2 KOLOM UTAMA --}}
            <div class="un-layout">

                {{-- KOLOM KIRI (KALENDER BULANAN ATAU TAHUNAN) --}}
                <div>
                    @if ($viewMode === 'bulanan')
                        {{-- CARD PERIODE AKADEMIK AKTIF --}}
                        <div class="un-active-period">
                            <div class="un-period-header">
                                <h3 class="un-period-title">
                                    <span class="un-live-dot"></span>
                                    <span>Periode Akademik Aktif — {{ $currentMonthName }} {{ $selectedYear }}</span>
                                </h3>
                                <button 
                                    type="button" 
                                    wire:click="toggleLiburPekan" 
                                    class="un-btn-toggle-holiday">
                                    {{ strtoupper($liburMingguan) }} &amp; LIBUR {{ $showLiburPekan ? 'ON' : 'OFF' }}
                                </button>
                            </div>

                            <div class="un-period-items">
                                @forelse ($activePeriods as $period)
                                    <div class="un-period-chip">
                                        <i class="material-icons" style="font-size: 16px; color: #059669;">school</i>
                                        <span>{{ $period->judul }}</span>
                                        <span class="un-period-date-badge">
                                            {{ $period->tanggal_mulai ? $period->tanggal_mulai->format('d M') : '' }} 
                                            @if ($period->tanggal_selesai && $period->tanggal_selesai != $period->tanggal_mulai)
                                                s/d {{ $period->tanggal_selesai->format('d M Y') }}
                                            @endif
                                        </span>
                                    </div>
                                @empty
                                    <div class="un-period-chip">
                                        <i class="material-icons" style="font-size: 16px; color: #059669;">school</i>
                                        <span>Tahun Pelajaran {{ $selectedYear }}</span>
                                        <span class="un-period-date-badge">Semester Aktif TPQ</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- NAVIGASI BULAN --}}
                        <div class="un-cal-nav">
                            <button type="button" wire:click="prevMonth" class="un-nav-arrow" title="Bulan Sebelumnya">
                                <i class="material-icons" style="font-size: 20px;">chevron_left</i>
                            </button>
                            <div class="un-month-title">
                                <span>{{ $currentMonthName }}</span>
                                <span class="un-year-badge">{{ $selectedYear }}</span>
                            </div>
                            <button type="button" wire:click="nextMonth" class="un-nav-arrow" title="Bulan Berikutnya">
                                <i class="material-icons" style="font-size: 20px;">chevron_right</i>
                            </button>
                        </div>

                        {{-- TABEL KALENDER TERPADU --}}
                        <div class="un-cal-card">
                            {{-- Header 7 Hari --}}
                            <div class="un-cal-header-row">
                                <div class="un-day-name {{ in_array($liburMingguan, ['ahad', 'jumat_ahad']) ? 'red' : '' }}">
                                    @if ($liburMingguan === 'ahad')
                                        <span class="un-jum-badge">
                                            MIN <span class="un-jum-libur-tag">LIBUR</span>
                                        </span>
                                    @else
                                        MIN
                                    @endif
                                </div>
                                <div class="un-day-name">SEN</div>
                                <div class="un-day-name {{ in_array($liburMingguan, ['selasa_jumat', 'jumat_selasa', 'selasa']) ? 'red' : '' }}">
                                    @if (in_array($liburMingguan, ['selasa_jumat', 'jumat_selasa', 'selasa']))
                                        <span class="un-jum-badge">
                                            SEL <span class="un-jum-libur-tag">LIBUR</span>
                                        </span>
                                    @else
                                        SEL
                                    @endif
                                </div>
                                <div class="un-day-name">RAB</div>
                                <div class="un-day-name">KAM</div>
                                <div class="un-day-name red">
                                    @if (in_array($liburMingguan, ['jumat', 'selasa_jumat', 'jumat_selasa', 'jumat_ahad']))
                                        <span class="un-jum-badge">
                                            JUM <span class="un-jum-libur-tag">LIBUR</span>
                                        </span>
                                    @else
                                        JUM
                                    @endif
                                </div>
                                <div class="un-day-name">SAB</div>
                            </div>

                            {{-- Grid Sel Tanggal --}}
                            <div class="un-cal-body">
                                @foreach ($matrix as $cell)
                                    @php
                                        $isJum = $cell['is_weekly_holiday'];
                                        $isCur = $cell['is_current_month'];
                                        $isHoliday = $cell['is_holiday'];
                                    @endphp
                                    <div 
                                        @if ($isManageable) wire:click="openCreateModal('{{ $cell['date_string'] }}')" @endif
                                        class="un-cell {{ $isHoliday ? 'is-holiday' : '' }} {{ $isJum ? 'is-friday' : '' }} {{ !$isCur ? 'other-month' : '' }}">
                                        
                                        {{-- Header Angka Tanggal Masehi --}}
                                        <div class="un-cell-top">
                                            @if ($cell['is_today'])
                                                <div class="un-today-badge">
                                                    {{ $cell['day'] }}
                                                </div>
                                            @else
                                                <span class="un-masehi-num {{ $isHoliday ? 'red' : '' }}">
                                                    {{ $cell['day'] }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Event Pills --}}
                                        @foreach ($cell['events']->take(2) as $ev)
                                            @php
                                                $katClass = $ev->is_libur ? 'libur' : (in_array($ev->kategori, ['akademik', 'penting', 'umum', 'tugas']) ? $ev->kategori : 'umum');
                                            @endphp
                                            <div 
                                                wire:click.stop="{{ $isManageable ? 'editAgenda(' . $ev->id . ')' : 'showDetail(' . $ev->id . ')' }}" 
                                                class="un-event-chip {{ $katClass }}"
                                                title="{{ $ev->judul }}">
                                                @if ($ev->is_libur)
                                                    <span style="width: 5px; height: 5px; border-radius: 50%; background: #e11d48; display: inline-block; flex-shrink: 0;"></span>
                                                @endif
                                                <span>{{ $ev->judul }}</span>
                                            </div>
                                        @endforeach

                                        @if ($cell['events']->count() > 2)
                                            <div style="font-size: 9px; font-weight: 700; color: #6d28d9; padding-left: 2px;">
                                                +{{ $cell['events']->count() - 2 }} agenda
                                            </div>
                                        @endif

                                        {{-- Libur Rutin Mingguan: posisi paling bawah, disembunyikan bila ada kegiatan/agenda --}}
                                        @if ($isJum && $showLiburPekan && $isCur && $cell['events']->isEmpty())
                                            <div class="un-libur-jumat-pill">
                                                Libur {{ $cell['carbon']->isTuesday() ? 'Selasa' : ($cell['carbon']->isFriday() ? "Jum'at" : ($cell['carbon']->isSunday() ? 'Ahad' : 'Pekan')) }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @else
                        {{-- MODE TAHUNAN (12 BULAN MINI) --}}
                        <div>
                            {{-- Navigasi Tahun --}}
                            <div class="un-cal-nav">
                                <button type="button" wire:click="prevYear" class="un-nav-arrow">
                                    <i class="material-icons" style="font-size: 20px;">chevron_left</i>
                                </button>
                                <div class="un-month-title">
                                    <span>Kalender Tahunan</span>
                                    <span class="un-year-badge">{{ $selectedYear }}</span>
                                </div>
                                <button type="button" wire:click="nextYear" class="un-nav-arrow">
                                    <i class="material-icons" style="font-size: 20px;">chevron_right</i>
                                </button>
                            </div>

                            {{-- 12 Mini Cards Grid --}}
                            <div class="un-year-grid">
                                @foreach ($yearMonths as $m)
                                    <div wire:click="jumpToMonth({{ $m['month_num'] }})" class="un-mini-card">
                                        <div class="un-mini-card-header">
                                            <span class="un-mini-title">{{ $m['name'] }} &rsaquo;</span>
                                            @if ($m['is_current'])
                                                <span class="un-now-badge">NOW</span>
                                            @endif
                                        </div>
                                        <div class="un-mini-day-headers">
                                            <span class="{{ in_array($liburMingguan, ['ahad', 'jumat_ahad']) ? 'red' : '' }}">M</span>
                                            <span>S</span>
                                            <span class="{{ in_array($liburMingguan, ['selasa_jumat', 'jumat_selasa', 'selasa']) ? 'red' : '' }}">S</span>
                                            <span>R</span>
                                            <span>K</span>
                                            <span class="{{ in_array($liburMingguan, ['jumat', 'selasa_jumat', 'jumat_selasa', 'jumat_ahad']) ? 'red' : '' }}">J</span>
                                            <span>S</span>
                                        </div>
                                        <div class="un-mini-days-grid">
                                            @foreach ($m['days'] as $dItem)
                                                @if ($dItem['day'] === null)
                                                    <span></span>
                                                @else
                                                    <span 
                                                        wire:click.stop="openDayModal('{{ $dItem['date_string'] }}')"
                                                        class="un-mini-cell {{ $dItem['is_today'] ? 'today' : '' }} {{ $dItem['is_holiday'] && !$dItem['is_today'] ? 'red is-holiday' : '' }} {{ $dItem['has_event'] && !$dItem['is_today'] ? 'has-event' : '' }}"
                                                        title="Tanggal {{ $dItem['day'] }} {{ $m['name'] }}: Klik untuk melihat info kegiatan">
                                                        {{ $dItem['day'] }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- KOLOM KANAN (STATISTIK & TIMELINE AGENDA) --}}
                <div>
                    {{-- 1. KARTU STATISTIK BULAN INI --}}
                    <div class="un-side-card">
                        <div class="un-side-header">
                            <div class="un-side-header-left">
                                <div class="un-side-icon-box green">
                                    <i class="material-icons" style="font-size: 20px;">insights</i>
                                </div>
                                <div>
                                    <h3 class="un-side-title">Statistik Bulan Ini</h3>
                                    <p class="un-side-subtitle">Ringkasan otomatis</p>
                                </div>
                            </div>
                            <span class="un-live-badge">
                                <span class="un-live-dot"></span>
                                LIVE
                            </span>
                        </div>

                        {{-- 3 Metrik Utama --}}
                        <div class="un-stats-row">
                            {{-- Hari Kerja --}}
                            <div class="un-stat-box">
                                <div class="un-stat-icon-circle" style="background: #eff6ff; color: #2563eb;">
                                    <i class="material-icons" style="font-size: 17px;">work_outline</i>
                                </div>
                                <div class="un-stat-val" style="color: #1e293b;">
                                    {{ $stats['hari_kerja'] }}
                                </div>
                                <div class="un-stat-lbl">Hari Kerja</div>
                                <div class="un-stat-bar" style="background: #2563eb;"></div>
                            </div>

                            {{-- Hari Libur --}}
                            <div class="un-stat-box">
                                <div class="un-stat-icon-circle" style="background: #fff1f2; color: #e11d48;">
                                    <i class="material-icons" style="font-size: 17px;">error_outline</i>
                                </div>
                                <div class="un-stat-val" style="color: #e11d48;">
                                    {{ $stats['hari_libur'] }}
                                </div>
                                <div class="un-stat-lbl">Hari Libur</div>
                                <div class="un-stat-bar" style="background: #e11d48;"></div>
                            </div>

                            {{-- Agenda --}}
                            <div class="un-stat-box">
                                <div class="un-stat-icon-circle" style="background: #f5f3ff; color: #7c3aed;">
                                    <i class="material-icons" style="font-size: 17px;">description</i>
                                </div>
                                <div class="un-stat-val" style="color: #7c3aed;">
                                    {{ $stats['total_agenda'] }}
                                </div>
                                <div class="un-stat-lbl">Agenda</div>
                                <div class="un-stat-bar" style="background: #7c3aed;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. KARTU AGENDA & LIBUR TIMELINE --}}
                    <div class="un-side-card">
                        <div class="un-side-header">
                            <div class="un-side-header-left">
                                <div class="un-side-icon-box purple">
                                    <i class="material-icons" style="font-size: 20px;">event_note</i>
                                </div>
                                <div>
                                    <h3 class="un-side-title">Agenda &amp; Libur</h3>
                                    <p class="un-side-subtitle">Timeline bulan ini</p>
                                </div>
                            </div>
                            <span style="background: #f5f3ff; color: #7c3aed; font-size: 11px; font-weight: 700; padding: 2px 10px; border-radius: 8px;">
                                {{ $currentMonthName }}
                            </span>
                        </div>

                        {{-- List Timeline Items --}}
                        <div class="un-timeline-scroll">
                            @forelse ($timelineEvents as $itemIndex => $item)
                                <div class="un-timeline-item" wire:key="timeline-item-{{ $item['agenda_id'] ?? ($item['date_string'].'-'.$itemIndex) }}">
                                    <div class="un-time-col">
                                        <div class="un-time-day" style="{{ $item['is_libur'] ? 'color: #e11d48;' : '' }}">
                                            {{ $item['day_num'] }}
                                        </div>
                                        <div class="un-time-name">
                                            {{ $item['day_name'] }}
                                        </div>
                                    </div>

                                    <div class="un-time-info">
                                        <div class="un-time-title">
                                            {{ $item['judul'] }}
                                        </div>
                                        <span class="un-time-pill" style="{{ $item['is_libur'] ? 'background: #ffe4e6; color: #e11d48;' : 'background: #ecfdf5; color: #047857;' }}">
                                            {{ $item['kategori_label'] }}
                                        </span>
                                    </div>

                                    @if ($isManageable && $item['is_custom'] && !empty($item['agenda_id']))
                                        <div class="un-time-actions">
                                            <button 
                                                type="button" 
                                                wire:click="editAgenda({{ $item['agenda_id'] }})" 
                                                class="un-icon-btn" 
                                                title="Edit Agenda">
                                                <i class="material-icons" style="font-size: 16px;">edit</i>
                                            </button>
                                            <button 
                                                type="button" 
                                                wire:click="confirmDeleteAgenda({{ $item['agenda_id'] }})" 
                                                class="un-icon-btn danger" 
                                                title="Hapus Agenda">
                                                <i class="material-icons" style="font-size: 16px;">delete</i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div style="text-align: center; padding: 30px 0; color: #94a3b8;">
                                    <i class="material-icons" style="font-size: 36px; color: #cbd5e1; margin-bottom: 6px;">event_busy</i>
                                    <p style="font-size: 12px; margin: 0;">Tidak ada agenda di bulan ini</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- MODAL TAMBAH / EDIT AGENDA --}}
    @if ($isOpenModal)
        <div class="un-modal-backdrop">
            <div class="un-modal-box">
                <div class="un-modal-header">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="material-icons" style="font-size: 20px;">event</i>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 800; color: #ffffff;">
                            {{ $agendaId ? 'Edit Agenda Kegiatan' : 'Tambah Agenda Baru' }}
                        </h4>
                    </div>
                    <button type="button" wire:click="closeModals" style="background: transparent; border: none; color: #ffffff; cursor: pointer;">
                        <i class="material-icons">close</i>
                    </button>
                </div>

                <form wire:submit.prevent="saveAgenda" class="un-modal-body">
                    <div style="margin-bottom: 14px;">
                        <label class="un-form-label">Nama Kegiatan / Agenda *</label>
                        <input 
                            type="text" 
                            wire:model="form_judul" 
                            placeholder="Contoh: Imtihan Semester Ganjil" 
                            class="un-form-control" 
                            required>
                        @error('form_judul') <span style="font-size: 11px; color: #e11d48;">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                        <div>
                            <label class="un-form-label">Kategori *</label>
                            <select wire:model="form_kategori" class="un-form-control">
                                <option value="umum">Umum</option>
                                <option value="penting">Penting</option>
                                @if (in_array($form_kategori, ['akademik', 'tugas']))
                                    <option value="{{ $form_kategori }}">{{ ucfirst($form_kategori) }}</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="un-form-label">Aksen Warna</label>
                            <div style="display: flex; align-items: center; gap: 8px; padding-top: 6px;">
                                @foreach (['#10b981' => '#10b981', '#3b82f6' => '#3b82f6', '#8b5cf6' => '#8b5cf6', '#f59e0b' => '#f59e0b', '#f43f5e' => '#f43f5e'] as $hex => $c)
                                    <button 
                                        type="button" 
                                        wire:click="$set('form_warna', '{{ $hex }}')" 
                                        style="width: 24px; height: 24px; border-radius: 50%; background: {{ $hex }}; border: {{ $form_warna === $hex ? '2px solid #0f172a' : 'none' }}; cursor: pointer;"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                        <div>
                            <label class="un-form-label">Tanggal Mulai *</label>
                            <input type="date" wire:model="form_tanggal_mulai" class="un-form-control" required>
                            @error('form_tanggal_mulai') <span style="font-size: 11px; color: #e11d48;">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="un-form-label">Tanggal Selesai</label>
                            <input type="date" wire:model="form_tanggal_selesai" class="un-form-control">
                        </div>
                    </div>

                    <div style="margin-bottom: 14px; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 12px; display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 12px; font-weight: 800; color: #9f1239;">Tetapkan Sebagai Hari Libur TPQ</div>
                            <div style="font-size: 10.5px; color: #be123c;">Sistem presensi tidak akan menghitung alpa pada tanggal ini</div>
                        </div>
                        <input type="checkbox" wire:model="form_is_libur" style="width: 18px; height: 18px; accent-color: #e11d48; cursor: pointer;">
                    </div>

                    <div style="margin-bottom: 18px;">
                        <label class="un-form-label">Deskripsi / Catatan Tambahan</label>
                        <textarea wire:model="form_deskripsi" rows="2" class="un-form-control" placeholder="Tuliskan catatan tambahan jika ada..."></textarea>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f1f5f9; padding-top: 16px; margin-top: 6px;">
                        <div>
                            @if ($agendaId)
                                <button 
                                    type="button" 
                                    wire:click="confirmDeleteAgenda({{ $agendaId }})" 
                                    class="un-btn-delete-modal"
                                    title="Hapus agenda ini">
                                    <i class="material-icons" style="font-size: 16px;">delete</i>
                                    <span>Hapus Agenda</span>
                                </button>
                            @endif
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button 
                                type="button" 
                                wire:click="closeModals" 
                                style="padding: 9px 18px; border-radius: 12px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-weight: 600; font-size: 13px; cursor: pointer;">
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                wire:loading.attr="disabled"
                                style="padding: 9px 24px; border-radius: 12px; border: none; background: linear-gradient(135deg, #6d28d9 0%, #4f46e5 100%); color: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer; box-shadow: 0 4px 12px rgba(109,40,217,0.25); display: inline-flex; align-items: center; gap: 6px;">
                                <span wire:loading.remove>{{ $agendaId ? 'Simpan Perubahan' : 'Simpan Agenda' }}</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- MODAL POPUP INFORMASI LENGKAP TANGGAL & KEGIATAN --}}
    @if ($isOpenDayModal && $selectedDayData)
        <div class="un-modal-backdrop" wire:click.self="closeModals" style="z-index: 10040;">
            <div class="un-modal-box" style="max-width: 520px; border-radius: 22px; overflow: hidden; padding: 0; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);">
                
                {{-- Modal Header --}}
                <div style="background: linear-gradient(135deg, #4f14e2 0%, #6d28d9 60%, #7c3aed 100%); padding: 20px 24px; color: #ffffff; display: flex; align-items: center; justify-content: space-between; position: relative;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="material-icons" style="font-size: 22px; color: #ffffff;">calendar_month</i>
                        </div>
                        <div>
                            <div style="font-size: 11px; font-weight: 700; color: rgba(255, 255, 255, 0.8); text-transform: uppercase; letter-spacing: 0.5px;">
                                Informasi Tanggal &amp; Kegiatan
                            </div>
                            <h3 style="margin: 2px 0 0 0; font-size: 17px; font-weight: 800; color: #ffffff; letter-spacing: -0.3px;">
                                {{ $selectedDayData['formatted_date'] }}
                            </h3>
                        </div>
                    </div>
                    <button type="button" wire:click="closeModals" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255, 255, 255, 0.15); border: none; color: #ffffff; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                        <i class="material-icons" style="font-size: 18px;">close</i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div style="padding: 22px 24px; max-height: 70vh; overflow-y: auto;">
                    
                    {{-- Status Banner (Hari Libur / Hari Kerja) --}}
                    @if ($selectedDayData['is_holiday'])
                        <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 14px; padding: 12px 16px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 10px; background: #ffe4e6; color: #e11d48; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="material-icons" style="font-size: 20px;">event_busy</i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 12.5px; font-weight: 800; color: #9f1239;">
                                    Hari Libur
                                    @if ($selectedDayData['is_weekly_holiday'])
                                        (Libur Rutin {{ $selectedDayData['weekly_holiday_name'] }})
                                    @elseif ($selectedDayData['has_holiday_event'])
                                        (Libur Resmi TPQ)
                                    @endif
                                </div>
                                <div style="font-size: 11px; color: #be123c;">
                                    Presensi santri dan ustadz tidak mencatat alpa pada tanggal ini.
                                </div>
                            </div>
                            @if ($selectedDayData['is_today'])
                                <span style="background: #5a20cb; color: #ffffff; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 6px;">HARI INI</span>
                            @endif
                        </div>
                    @else
                        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 14px; padding: 12px 16px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="material-icons" style="font-size: 20px;">check_circle</i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 12.5px; font-weight: 800; color: #166534;">
                                    Hari Kerja / Belajar Aktif TPQ
                                </div>
                                <div style="font-size: 11px; color: #15803d;">
                                    Kegiatan pembelajaran dan administrasi berjalan normal.
                                </div>
                            </div>
                            @if ($selectedDayData['is_today'])
                                <span style="background: #5a20cb; color: #ffffff; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 6px;">HARI INI</span>
                            @endif
                        </div>
                    @endif

                    {{-- Daftar Agenda Kegiatan --}}
                    <div style="margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-size: 12px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.5px;">
                                Agenda Kegiatan ({{ $selectedDayData['events']->count() }})
                            </span>
                            @if ($isManageable)
                                <button 
                                    type="button" 
                                    wire:click="openCreateFromDayModal('{{ $selectedDayData['date_string'] }}')"
                                    style="background: transparent; border: none; color: #6d28d9; font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; padding: 0;">
                                    <i class="material-icons" style="font-size: 15px;">add_circle</i>
                                    <span>Tambah Agenda</span>
                                </button>
                            @endif
                        </div>

                        @forelse ($selectedDayData['events'] as $ev)
                            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-left: 4px solid {{ $ev->warna ?: ($ev->is_libur ? '#e11d48' : '#6d28d9') }}; border-radius: 12px; padding: 14px; margin-bottom: 10px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02); transition: all 0.15s ease;">
                                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 6px;">
                                    <h4 style="margin: 0; font-size: 14px; font-weight: 800; color: #0f172a; line-height: 1.35;">
                                        {{ $ev->judul }}
                                    </h4>
                                    <div style="display: flex; align-items: center; gap: 4px; flex-shrink: 0;">
                                        @if ($ev->is_libur)
                                            <span style="background: #ffe4e6; color: #e11d48; font-size: 9.5px; font-weight: 800; padding: 2px 7px; border-radius: 6px; text-transform: uppercase;">
                                                Libur
                                            </span>
                                        @endif
                                        <span style="background: {{ $ev->kategori === 'penting' ? '#f5f3ff' : '#eff6ff' }}; color: {{ $ev->kategori === 'penting' ? '#6d28d9' : '#1d4ed8' }}; font-size: 9.5px; font-weight: 800; padding: 2px 7px; border-radius: 6px; text-transform: uppercase;">
                                            {{ ucfirst($ev->kategori) }}
                                        </span>
                                    </div>
                                </div>

                                <div style="font-size: 11.5px; color: #64748b; margin-bottom: 6px; display: flex; align-items: center; gap: 5px;">
                                    <i class="material-icons" style="font-size: 14px; color: #94a3b8;">date_range</i>
                                    <span>
                                        {{ $ev->tanggal_mulai ? $ev->tanggal_mulai->translatedFormat('d M Y') : '' }}
                                        @if ($ev->tanggal_selesai && $ev->tanggal_selesai != $ev->tanggal_mulai)
                                            &mdash; {{ $ev->tanggal_selesai->translatedFormat('d M Y') }}
                                        @endif
                                    </span>
                                </div>

                                @if ($ev->deskripsi)
                                    <div style="background: #f8fafc; border-radius: 8px; padding: 8px 10px; font-size: 12px; color: #475569; line-height: 1.45; margin-bottom: 8px;">
                                        {{ $ev->deskripsi }}
                                    </div>
                                @endif

                                @if ($isManageable)
                                    <div style="display: flex; justify-content: flex-end; gap: 8px; padding-top: 6px; border-top: 1px dashed #f1f5f9;">
                                        <button 
                                            type="button" 
                                            wire:click="editAgenda({{ $ev->id }})" 
                                            style="padding: 4px 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; color: #334155; font-size: 11px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="material-icons" style="font-size: 13px;">edit</i>
                                            <span>Edit</span>
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="confirmDeleteAgenda({{ $ev->id }})" 
                                            style="padding: 4px 10px; border-radius: 8px; border: 1px solid #fecdd3; background: #fff1f2; color: #e11d48; font-size: 11px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="material-icons" style="font-size: 13px;">delete</i>
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 14px; padding: 24px 16px; text-align: center; color: #64748b;">
                                <i class="material-icons" style="font-size: 32px; color: #cbd5e1; margin-bottom: 4px;">event_available</i>
                                <p style="font-size: 12.5px; margin: 0; font-weight: 600;">
                                    Tidak ada agenda kegiatan khusus pada tanggal ini.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 24px; display: flex; align-items: center; justify-content: space-between;">
                    <button 
                        type="button" 
                        wire:click="jumpToMonthFromModal({{ $selectedDayData['month'] }})"
                        style="background: transparent; border: none; color: #5a20cb; font-size: 12.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                        <span>Buka Kalender Bulan {{ \Carbon\Carbon::createFromDate($selectedDayData['year'], $selectedDayData['month'], 1)->translatedFormat('F') }}</span>
                        <i class="material-icons" style="font-size: 16px;">arrow_forward</i>
                    </button>
                    <button 
                        type="button" 
                        wire:click="closeModals" 
                        style="padding: 8px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-weight: 600; font-size: 12.5px; cursor: pointer;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL DETAIL AGENDA --}}
    @if ($isOpenDetailModal && $detailEvent)
        <div class="un-modal-backdrop">
            <div class="un-modal-box" style="max-width: 460px; padding: 22px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <span style="font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 6px; text-transform: uppercase; {{ $detailEvent->is_libur ? 'background: #ffe4e6; color: #e11d48;' : 'background: #ecfdf5; color: #047857;' }}">
                        {{ $detailEvent->is_libur ? 'Hari Libur TPQ' : ucfirst($detailEvent->kategori) }}
                    </span>
                    <button type="button" wire:click="closeModals" style="background: transparent; border: none; color: #94a3b8; cursor: pointer;">
                        <i class="material-icons">close</i>
                    </button>
                </div>

                <h3 style="font-size: 17px; font-weight: 800; color: #1e293b; margin: 0 0 8px 0;">
                    {{ $detailEvent->judul }}
                </h3>

                <div style="font-size: 12px; color: #64748b; margin-bottom: 14px; display: flex; align-items: center; gap: 6px;">
                    <i class="material-icons" style="font-size: 16px; color: #7c3aed;">event</i>
                    <span>
                        {{ $detailEvent->tanggal_mulai ? $detailEvent->tanggal_mulai->translatedFormat('d F Y') : '' }}
                        @if ($detailEvent->tanggal_selesai && $detailEvent->tanggal_selesai != $detailEvent->tanggal_mulai)
                            s/d {{ $detailEvent->tanggal_selesai->translatedFormat('d F Y') }}
                        @endif
                    </span>
                </div>

                @if ($detailEvent->deskripsi)
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; font-size: 12.5px; color: #334155; line-height: 1.5; margin-bottom: 16px;">
                        {{ $detailEvent->deskripsi }}
                    </div>
                @endif

                <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f1f5f9; padding-top: 14px; margin-top: 6px;">
                    <div>
                        @if ($isManageable)
                            <button 
                                type="button" 
                                wire:click="confirmDeleteAgenda({{ $detailEvent->id }})" 
                                class="un-btn-delete-modal"
                                title="Hapus agenda ini">
                                <i class="material-icons" style="font-size: 15px;">delete</i>
                                <span>Hapus</span>
                            </button>
                        @endif
                    </div>
                    <div style="display: flex; gap: 8px;">
                        @if ($isManageable)
                            <button 
                                type="button" 
                                wire:click="editAgenda({{ $detailEvent->id }})" 
                                style="padding: 7px 16px; border-radius: 10px; border: 1px solid #c7d2fe; background: #eef2ff; color: #4338ca; font-weight: 700; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="material-icons" style="font-size: 14px;">edit</i>
                                <span>Edit</span>
                            </button>
                        @endif
                        <button 
                            type="button" 
                            wire:click="closeModals" 
                            style="padding: 7px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-weight: 600; font-size: 12px; cursor: pointer;">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL KONFIRMASI HAPUS AGENDA --}}
    @if ($isDeleteModalOpen)
        <div class="un-modal-backdrop" style="z-index: 10050;">
            <div class="un-modal-box" style="max-width: 440px; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);">
                <div style="padding: 26px 24px 18px 24px; text-align: center;">
                    <div style="width: 58px; height: 58px; border-radius: 18px; background: #fee2e2; color: #dc2626; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; box-shadow: 0 6px 16px rgba(220, 38, 38, 0.2);">
                        <i class="material-icons" style="font-size: 32px;">delete_forever</i>
                    </div>
                    <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">
                        Hapus Agenda Kegiatan?
                    </h4>
                    <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin: 0 0 12px 0;">
                        Apakah Anda yakin ingin menghapus agenda kegiatan berikut?
                    </p>
                    <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 12px 14px; font-size: 13.5px; font-weight: 700; color: #1e293b; margin-bottom: 12px; word-break: break-word;">
                        "{{ $agendaToDeleteTitle }}"
                    </div>
                    <p style="font-size: 11.5px; color: #e11d48; margin: 0; font-weight: 600;">
                        <i class="material-icons" style="font-size: 14px; vertical-align: middle;">warning</i> Tindakan ini permanen dan tidak dapat dibatalkan.
                    </p>
                </div>

                <div style="background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 16px 24px; display: flex; justify-content: flex-end; gap: 10px;">
                    <button 
                        type="button" 
                        wire:click="closeDeleteModal" 
                        style="padding: 9px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-weight: 600; font-size: 13px; cursor: pointer; transition: all 0.15s;">
                        Batal
                    </button>
                    <button 
                        type="button" 
                        wire:click="deleteAgenda" 
                        wire:loading.attr="disabled"
                        style="padding: 9px 22px; border-radius: 10px; border: none; background: #dc2626; color: #ffffff; font-weight: 700; font-size: 13px; cursor: pointer; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3); display: inline-flex; align-items: center; gap: 6px;">
                        <span wire:loading.remove>Ya, Hapus Sekarang</span>
                        <span wire:loading>Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
