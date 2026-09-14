@php
    use Filament\Support\Icons\Heroicon;

    use function Filament\Support\generate_icon_html;
@endphp

<x-filament-widgets::widget>
    <section class="kdi-sambutan" aria-labelledby="kdi-sambutan-judul">
        <img src="/img/latar-jaringan.webp" alt="" aria-hidden="true" class="kdi-sambutan-latar" width="2560" height="1440" />

        <div class="kdi-sambutan-isi">
            <div>
                <p class="kdi-label">Panel Admin Diskominfo Kota Kendari</p>
                <h2 id="kdi-sambutan-judul">{{ $sapaan }}{{ $nama ? ', '.$nama : '' }}</h2>
                <p class="kdi-sambutan-teks">{{ $tanggal }}. Kelola berita, galeri, dokumen, dan profil dinas yang tampil di situs resmi.</p>
            </div>

            <ul class="kdi-pintasan">
                @foreach ($pintasan as [$label, $url, $ikon])
                    <li>
                        <a href="{{ $url }}">
                            <span class="kdi-pintasan-ikon">{{ generate_icon_html($ikon) }}</span>
                            {{ $label }}
                            {{ generate_icon_html(Heroicon::ArrowRight, attributes: new \Illuminate\View\ComponentAttributeBag(['class' => 'kdi-pintasan-panah'])) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
</x-filament-widgets::widget>
