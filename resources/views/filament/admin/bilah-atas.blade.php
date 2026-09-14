@php
    use App\Support\WaktuWita;
    use Filament\Support\Icons\Heroicon;

    use function Filament\Support\generate_icon_html;
@endphp

{{-- Jam WITA dan tautan ke situs publik, seperti bilah informasi di situs. --}}
<div class="kdi-bilah">
    <p class="kdi-jam">
        {{ generate_icon_html(Heroicon::OutlinedClock) }}
        <strong><time data-jam>{{ WaktuWita::sekarang()->format('H.i.s') }}</time> WITA</strong>
    </p>

    <a href="/" target="_blank" rel="noopener" class="kdi-lihat-situs">
        {{ generate_icon_html(Heroicon::OutlinedArrowTopRightOnSquare) }}
        Lihat situs
    </a>
</div>
