<style>
    /*
     * Gaya panel admin mengikuti DESIGN.md situs (Portal Biru Instansi). Warna datang dari palet primary/gray
     * di AdminPanelProvider; berkas ini hanya mengubah bentuk. CSS Filament ada di @layer, jadi aturan
     * tanpa layer di sini menang tanpa !important. Aset /img dan /fonts disajikan situs Astro lewat Caddy.
     */
    @font-face {
        font-family: "IBM Plex Sans";
        font-style: normal;
        font-weight: 400 700;
        font-display: swap;
        src: url("/fonts/ibm-plex-sans-latin.woff2") format("woff2");
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }

    @font-face {
        font-family: "IBM Plex Sans";
        font-style: normal;
        font-weight: 400 700;
        font-display: swap;
        src: url("/fonts/ibm-plex-sans-latin-ext.woff2") format("woff2");
        unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }

    /* Aturan pemilik: semua sudut 4px (Tailwind `rounded`), termasuk avatar. */
    :root {
        --radius-md: 0.25rem;
        --radius-lg: 0.25rem;
        --radius-xl: 0.25rem;
        --radius-2xl: 0.25rem;
        --radius-3xl: 0.25rem;
    }

    .fi-avatar.fi-circular {
        border-radius: 0.25rem;
    }

    /*
     * Scrollbar tipis tanpa panah: batang 4px di jalur transparan, biru saat disentuh kursor.
     * Chrome/Safari memakai ::-webkit-scrollbar. Firefox tidak mengenalnya, jadi memakai properti standar;
     * keduanya tidak digabung karena Chrome mengabaikan ::-webkit-scrollbar bila scrollbar-color diisi.
     */
    ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    ::-webkit-scrollbar-track,
    ::-webkit-scrollbar-corner {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        border: 3px solid transparent;
        border-radius: 0.25rem;
        background: var(--gray-300) padding-box;
    }

    ::-webkit-scrollbar-thumb:hover {
        border-width: 2px;
        background-color: var(--primary-500);
    }

    html.dark ::-webkit-scrollbar-thumb {
        background-color: var(--gray-700);
    }

    html.dark ::-webkit-scrollbar-thumb:hover {
        background-color: var(--primary-400);
    }

    ::-webkit-scrollbar-button {
        display: none;
    }

    @supports not selector(::-webkit-scrollbar) {
        * {
            scrollbar-width: thin;
        }

        html {
            scrollbar-color: var(--gray-300) transparent;
        }

        html.dark {
            scrollbar-color: var(--gray-700) transparent;
        }
    }

    .fi-btn {
        font-weight: 600;
    }

    /* ---------- Topbar: bilah identitas biru dengan logo resmi, seperti header situs ---------- */
    .fi-topbar {
        gap: 0.5rem;
        background: var(--primary-800);
        box-shadow: 0 8px 24px -16px rgb(7 31 61 / 0.8);
        color: #fff;
    }

    .fi-topbar .fi-icon-btn {
        color: var(--primary-200);
    }

    .fi-topbar .fi-icon-btn:hover {
        background: rgb(255 255 255 / 0.1);
        color: #fff;
    }

    .fi-topbar .fi-global-search-field .fi-input-wrp {
        background: #fff;
        box-shadow: none;
    }

    html.dark .fi-topbar .fi-global-search-field .fi-input-wrp {
        background: rgb(7 31 61 / 0.6);
        box-shadow: inset 0 0 0 1px rgb(255 255 255 / 0.15);
    }

    .fi-topbar .fi-user-avatar {
        box-shadow: 0 0 0 2px rgb(255 255 255 / 0.3);
    }

    .kdi-bilah {
        display: none;
        align-items: center;
        gap: 1.25rem;
        margin-inline-end: 1rem;
        font-size: 0.8125rem;
        color: var(--primary-200);
    }

    .kdi-bilah svg {
        width: 1rem;
        height: 1rem;
    }

    .kdi-jam {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .kdi-jam strong {
        font-size: 0.875rem;
        font-weight: 600;
        font-variant-numeric: tabular-nums;
        color: #fff;
    }

    .kdi-lihat-situs {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        min-height: 2.25rem;
        padding-inline: 0.75rem;
        border: 1px solid rgb(255 255 255 / 0.25);
        border-radius: 0.25rem;
        font-weight: 600;
        color: #fff;
        transition: background-color 150ms;
    }

    .kdi-lihat-situs:hover {
        background: rgb(255 255 255 / 0.1);
    }

    @media (min-width: 1024px) {
        .kdi-bilah {
            display: flex;
        }
    }

    /* ---------- Sidebar: putih bertepi, label grup kapital, item aktif berbilah 3px ---------- */
    .fi-sidebar {
        background: #fff;
        box-shadow: inset -1px 0 0 var(--gray-200);
    }

    html.dark .fi-sidebar {
        background: var(--gray-900);
        box-shadow: inset -1px 0 0 var(--gray-800);
    }

    /* Header sidebar (tampil di ponsel) memuat logo bertulisan putih, jadi ikut biru. */
    .fi-sidebar-header {
        background: var(--primary-800);
    }

    .fi-sidebar-nav {
        row-gap: 1.5rem;
        padding-inline: 1rem;
    }

    .fi-sidebar-group-label {
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--primary-600);
    }

    html.dark .fi-sidebar-group-label {
        color: var(--primary-300);
    }

    .fi-sidebar-group-btn,
    .fi-sidebar-item-btn {
        padding-inline: 0.75rem;
        transition: background-color 150ms;
    }

    .fi-sidebar-item-btn:hover {
        background: var(--gray-50);
    }

    html.dark .fi-sidebar-item-btn:hover {
        background: rgb(255 255 255 / 0.05);
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background: var(--primary-50);
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn::before {
        content: "";
        position: absolute;
        inset-block: 0.375rem;
        inset-inline-start: 0;
        width: 3px;
        background: var(--primary-600);
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-sidebar-item-label {
        font-weight: 600;
        color: var(--primary-700);
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-sidebar-item-icon {
        color: var(--primary-600);
    }

    html.dark .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background: rgb(255 255 255 / 0.06);
    }

    html.dark .fi-sidebar-item.fi-active > .fi-sidebar-item-btn::before {
        background: var(--primary-400);
    }

    html.dark .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-sidebar-item-label {
        color: #fff;
    }

    html.dark .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-sidebar-item-icon {
        color: var(--primary-300);
    }

    /* ---------- Kepala halaman ---------- */
    .fi-header-heading {
        font-size: 1.75rem;
        line-height: 1.25;
        letter-spacing: -0.015em;
    }

    /* Tanpa remah roti, bilah 3px menjadi penanda di atas judul (sama dengan halaman login). */
    .fi-header:not(.fi-header-has-breadcrumbs) .fi-header-heading::before {
        content: "";
        display: block;
        width: 1.5rem;
        height: 3px;
        margin-bottom: 0.75rem;
        background: var(--primary-600);
    }

    .fi-breadcrumbs-item-label {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    /* ---------- Kartu: tepi tipis dan bayangan seperti kartu-timbul di situs ---------- */
    .fi-section:not(.fi-section-not-contained),
    .fi-ta-ctn,
    .fi-wi-stats-overview-stat,
    .fi-resource-create-record-page .fi-sc-form,
    .fi-resource-edit-record-page .fi-sc-form {
        box-shadow: 0 0 0 1px var(--gray-200), 0 1px 2px rgb(7 31 61 / 0.06), 0 10px 24px -12px rgb(7 31 61 / 0.22);
    }

    html.dark .fi-section:not(.fi-section-not-contained),
    html.dark .fi-ta-ctn,
    html.dark .fi-wi-stats-overview-stat,
    html.dark .fi-resource-create-record-page .fi-sc-form,
    html.dark .fi-resource-edit-record-page .fi-sc-form {
        box-shadow: 0 0 0 1px var(--gray-800), 0 10px 24px -12px rgb(0 0 0 / 0.7);
    }

    /* Formulir resource tidak memakai Section, jadi dibungkus kartu agar tidak melayang di latar. */
    .fi-resource-create-record-page .fi-sc-form,
    .fi-resource-edit-record-page .fi-sc-form {
        padding: 1.5rem;
        border-radius: 0.25rem;
        background: #fff;
    }

    html.dark .fi-resource-create-record-page .fi-sc-form,
    html.dark .fi-resource-edit-record-page .fi-sc-form {
        background: var(--gray-900);
    }

    .fi-section-header-heading {
        font-weight: 700;
    }

    /* Judul widget bergaya judul bagian situs: kapital tebal. */
    .fi-wi-stats-overview .fi-section-header-heading {
        font-size: 0.875rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    /* ---------- Tabel: kepala kolom kapital di bidang langit ---------- */
    .fi-ta-table > thead > tr {
        background: var(--gray-100);
    }

    html.dark .fi-ta-table > thead > tr {
        background: rgb(255 255 255 / 0.03);
    }

    .fi-ta-header-cell,
    .fi-ta-header-cell-sort-btn {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--gray-600);
    }

    html.dark .fi-ta-header-cell,
    html.dark .fi-ta-header-cell-sort-btn {
        color: var(--gray-300);
    }

    /* ---------- Statistik: bilah biru di atas, label kapital, angka tebal ---------- */
    .fi-wi-stats-overview-stat {
        position: relative;
        overflow: hidden;
    }

    .fi-wi-stats-overview-stat::before {
        content: "";
        position: absolute;
        inset-inline: 0;
        top: 0;
        height: 3px;
        background: var(--primary-600);
    }

    .fi-wi-stats-overview-stat .fi-wi-stats-overview-stat-label {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--gray-600);
    }

    html.dark .fi-wi-stats-overview-stat .fi-wi-stats-overview-stat-label {
        color: var(--gray-300);
    }

    .fi-wi-stats-overview-stat .fi-wi-stats-overview-stat-value {
        font-size: 2.25rem;
        font-weight: 700;
    }

    /* ---------- Pita sambutan dasbor ---------- */
    .kdi-sambutan {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        border-radius: 0.25rem;
        background: var(--primary-900);
        color: #fff;
        box-shadow: 0 24px 48px -28px rgb(7 31 61 / 0.7);
    }

    .kdi-sambutan-latar {
        position: absolute;
        inset: 0;
        z-index: -2;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: 70% 55%;
    }

    .kdi-sambutan::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -1;
        background: linear-gradient(90deg, rgb(7 31 61 / 0.95) 0%, rgb(11 47 92 / 0.85) 50%, rgb(15 60 117 / 0.45) 100%);
    }

    .kdi-sambutan-isi {
        display: grid;
        gap: 1.75rem;
        padding: 1.75rem 1.25rem;
    }

    .kdi-label {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--primary-300);
    }

    .kdi-label::before {
        content: "";
        flex-shrink: 0;
        width: 1.5rem;
        height: 3px;
        background: var(--primary-400);
    }

    .kdi-sambutan h2 {
        margin-top: 0.625rem;
        font-size: clamp(1.5rem, 2.5vw, 2rem);
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -0.015em;
        text-wrap: balance;
    }

    .kdi-sambutan-teks {
        margin-top: 0.5rem;
        max-width: 56ch;
        font-size: 0.9375rem;
        line-height: 1.6;
        color: var(--primary-100);
    }

    .kdi-pintasan {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(10.5rem, 1fr));
        gap: 0.625rem;
    }

    .kdi-pintasan a {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        height: 100%;
        padding: 0.75rem;
        border: 1px solid rgb(255 255 255 / 0.15);
        border-radius: 0.25rem;
        background: rgb(255 255 255 / 0.08);
        font-size: 0.875rem;
        font-weight: 600;
        transition: background-color 150ms, border-color 150ms, transform 150ms;
    }

    .kdi-pintasan a:hover {
        border-color: rgb(255 255 255 / 0.35);
        background: rgb(255 255 255 / 0.15);
    }

    .kdi-pintasan-ikon {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 0.25rem;
        background: #fff;
        color: var(--primary-700);
    }

    .kdi-pintasan-ikon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .kdi-pintasan-panah {
        width: 1rem;
        height: 1rem;
        margin-inline-start: auto;
        opacity: 0.7;
    }

    @media (min-width: 1280px) {
        .kdi-sambutan-isi {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr);
            align-items: center;
            padding: 2.25rem 2.5rem;
        }
    }

    @media (prefers-reduced-motion: no-preference) {
        .kdi-pintasan a:hover {
            transform: translateY(-2px);
        }
    }
</style>
