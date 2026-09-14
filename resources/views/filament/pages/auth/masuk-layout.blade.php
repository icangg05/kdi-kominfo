@php
    use App\Support\WaktuWita;
    use Filament\Support\Icons\Heroicon;

    use function Filament\Support\generate_icon_html;

    // Modul yang benar-benar ada di navigasi admin.
    $modul = [
        ['Berita', Heroicon::OutlinedNewspaper],
        ['Galeri', Heroicon::OutlinedPhoto],
        ['Dokumen', Heroicon::OutlinedDocumentText],
        ['Profil Dinas', Heroicon::OutlinedBriefcase],
    ];

    // Jam dirender di server dulu, lalu skrip-jam (hook BODY_END panel) melanjutkannya tiap detik.
    $sekarang = WaktuWita::sekarang();
    $sapaan = WaktuWita::sapaan($sekarang);
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    @push('styles')
        <link rel="preload" href="/fonts/ibm-plex-sans-latin.woff2" as="font" type="font/woff2" crossorigin />
        <style>
            /* Font dan sudut 4px datang dari filament.admin.gaya; di sini hanya tata letak login. */
            .masuk {
                --m-latar: #fff;
                --m-judul: #071f3d;
                --m-teks: #1c2536;
                --m-lembut: #4a5568;
                --m-garis: #dbe3ef;

                display: grid;
                min-height: 100dvh;
                background: var(--m-latar);
                color: var(--m-teks);
            }

            html.dark .masuk {
                --m-latar: #071f3d;
                --m-judul: #fff;
                --m-teks: #e2edfa;
                --m-lembut: #8bb9ea;
                --m-garis: rgb(255 255 255 / 0.12);
            }

            .masuk-merek {
                position: relative;
                isolation: isolate;
                overflow: hidden;
                background: #0b2f5c;
                color: #fff;
            }

            .masuk-latar {
                position: absolute;
                inset: 0;
                z-index: -2;
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: 70% 60%;
            }

            .masuk-merek::before {
                content: "";
                position: absolute;
                inset: 0;
                z-index: -1;
                background: linear-gradient(180deg, rgb(7 31 61 / 0.7) 0%, rgb(7 31 61 / 0.25) 45%, rgb(7 31 61 / 0.94) 100%);
            }

            .masuk-merek-isi {
                display: flex;
                flex-direction: column;
                gap: 1.75rem;
                height: 100%;
                padding: 1.25rem 1rem 1.75rem;
            }

            .masuk-atas {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
            }

            .masuk-atas img {
                width: auto;
                height: 2.5rem;
            }

            .masuk-kembali {
                display: inline-flex;
                align-items: center;
                gap: 0.375rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: #c0d8f4;
                transition: color 150ms;
            }

            .masuk-kembali:hover {
                color: #fff;
            }

            .masuk-kembali svg {
                width: 1rem;
                height: 1rem;
            }

            .masuk-label {
                display: flex;
                align-items: center;
                gap: 0.625rem;
                font-size: 0.8125rem;
                font-weight: 600;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: #8bb9ea;
            }

            /* Bilah 3px yang sama dengan garis bawah menu aktif di situs. */
            .masuk-label::before {
                content: "";
                width: 1.5rem;
                height: 3px;
                background: #4f95dc;
            }

            .masuk-sambutan h2 {
                margin-top: 0.75rem;
                max-width: 18ch;
                font-size: clamp(1.5rem, 3.2vw, 3rem);
                font-weight: 700;
                line-height: 1.15;
                letter-spacing: -0.02em;
                text-wrap: balance;
            }

            .masuk-sambutan > p:not(.masuk-label) {
                margin-top: 1.25rem;
                max-width: 44ch;
                line-height: 1.625;
                color: #c0d8f4;
            }

            .masuk-modul {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                margin-top: 2rem;
            }

            .masuk-modul li {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 0.875rem;
                border: 1px solid rgb(255 255 255 / 0.16);
                border-radius: 0.25rem;
                background: rgb(7 31 61 / 0.45);
                font-size: 0.875rem;
                font-weight: 600;
            }

            .masuk-modul svg {
                width: 1.125rem;
                height: 1.125rem;
                color: #8bb9ea;
            }

            .masuk-jam {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding-top: 1.25rem;
                border-top: 1px solid rgb(255 255 255 / 0.14);
                font-size: 0.875rem;
                color: #c0d8f4;
            }

            .masuk-jam strong {
                font-size: 1rem;
                font-weight: 600;
                color: #fff;
                font-variant-numeric: tabular-nums;
            }

            .masuk-jam span[aria-hidden] {
                width: 1px;
                height: 0.875rem;
                background: rgb(255 255 255 / 0.2);
            }

            /* Di ponsel panel merek jadi pita ringkas di atas formulir. */
            .masuk-sambutan > p:not(.masuk-label),
            .masuk-modul,
            .masuk-jam {
                display: none;
            }

            .masuk-utama {
                display: flex;
                flex-direction: column;
                padding: 2.25rem 1rem 1.5rem;
            }

            .masuk-alat {
                display: flex;
                justify-content: flex-end;
            }

            .masuk-tema {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                min-height: 2.5rem;
                padding-inline: 0.75rem;
                border: 1px solid var(--m-garis);
                border-radius: 0.25rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: var(--m-teks);
                transition: background-color 150ms, border-color 150ms;
            }

            .masuk-tema:hover {
                border-color: #8bb9ea;
                background: rgb(11 78 162 / 0.06);
            }

            html.dark .masuk-tema:hover {
                border-color: #2777c8;
                background: rgb(255 255 255 / 0.06);
            }

            .masuk-tema svg {
                width: 1.125rem;
                height: 1.125rem;
            }

            /* Label menyebut aksi berikutnya; kelas `dark` dipasang Filament di <html>. */
            .masuk-tema-ke-gelap,
            html.dark .masuk-tema-ke-terang {
                display: contents;
            }

            .masuk-tema-ke-terang,
            html.dark .masuk-tema-ke-gelap {
                display: none;
            }

            .masuk-kotak {
                width: 100%;
                max-width: 26rem;
                margin: auto;
            }

            .masuk-judul {
                margin-bottom: 2rem;
            }

            .masuk-judul h1 {
                font-size: 1.75rem;
                font-weight: 700;
                line-height: 1.25;
                letter-spacing: -0.01em;
                color: var(--m-judul);
            }

            .masuk-judul p {
                margin-top: 0.5rem;
                color: var(--m-lembut);
            }

            .masuk .fi-fo-field-label-content {
                font-weight: 600;
                color: var(--m-teks);
            }

            .masuk .fi-input {
                padding-block: 0.625rem;
            }

            .masuk .fi-btn.fi-color-primary {
                min-height: 2.75rem;
                background: #0b4ea2;
                color: #fff;
                font-weight: 600;
                letter-spacing: 0.02em;
            }

            .masuk .fi-btn.fi-color-primary:hover {
                background: #0d478e;
            }

            .masuk .fi-btn.fi-color-primary .fi-icon {
                color: #fff;
            }

            .masuk-catatan {
                margin-top: 1.75rem;
                padding-top: 1.25rem;
                border-top: 1px solid var(--m-garis);
                font-size: 0.875rem;
                line-height: 1.5;
                color: var(--m-lembut);
            }

            .masuk-hak {
                margin-top: 2.5rem;
                font-size: 0.75rem;
                text-align: center;
                color: var(--m-lembut);
            }

            @media (min-width: 1024px) {
                .masuk {
                    grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
                }

                .masuk-merek {
                    position: sticky;
                    top: 0;
                    height: 100dvh;
                }

                .masuk-merek-isi {
                    padding: 2.5rem 3.5rem;
                }

                .masuk-atas img {
                    height: 3rem;
                }

                .masuk-sambutan {
                    margin-top: auto;
                }

                .masuk-sambutan > p:not(.masuk-label) {
                    display: block;
                }

                .masuk-modul,
                .masuk-jam {
                    display: flex;
                }

                .masuk-utama {
                    padding: 2.5rem 3.5rem;
                }
            }
        </style>
    @endpush

    <div class="masuk">
        <aside class="masuk-merek">
            <img src="/img/latar-jaringan.webp" alt="" aria-hidden="true" class="masuk-latar" width="2560" height="1440" />

            <div class="masuk-merek-isi">
                <div class="masuk-atas">
                    <a href="/">
                        <img src="/img/kominfo-logo.webp" alt="Diskominfo Kota Kendari" width="176" height="48" />
                    </a>

                    <a href="/" class="masuk-kembali">
                        {{ generate_icon_html(Heroicon::ArrowLeft) }}
                        Kembali ke situs
                    </a>
                </div>

                <div class="masuk-sambutan">
                    <p class="masuk-label">Panel Admin</p>
                    <h2>Kelola informasi publik Kota Kendari</h2>
                    <p>Perbarui berita, galeri, dokumen, dan profil dinas yang tampil di situs resmi Diskominfo Kota Kendari.</p>

                    <ul class="masuk-modul">
                        @foreach ($modul as [$label, $ikon])
                            <li>{{ generate_icon_html($ikon) }} {{ $label }}</li>
                        @endforeach
                    </ul>
                </div>

                <p class="masuk-jam">
                    <strong><time data-jam>{{ $sekarang->format('H.i.s') }}</time> WITA</strong>
                    <span aria-hidden="true"></span>
                    <span data-sapaan>{{ $sapaan }}</span>
                </p>
            </div>
        </aside>

        <main class="masuk-utama">
            <div class="masuk-alat">
                {{-- Kunci `theme` dipakai bersama situs publik; event ini ditangani skrip tema Filament. --}}
                <button
                    type="button"
                    class="masuk-tema"
                    x-data
                    x-on:click="$dispatch('theme-changed', $store.theme === 'dark' ? 'light' : 'dark')"
                >
                    <span class="masuk-tema-ke-gelap">{{ generate_icon_html(Heroicon::OutlinedMoon) }} Mode gelap</span>
                    <span class="masuk-tema-ke-terang">{{ generate_icon_html(Heroicon::OutlinedSun) }} Mode terang</span>
                </button>
            </div>

            {{ $slot }}

            <p class="masuk-hak">&copy; {{ $sekarang->year }} Dinas Komunikasi dan Informatika Kota Kendari</p>
        </main>
    </div>

</x-filament-panels::layout.base>
