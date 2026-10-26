{{--
  Bagan struktur organisasi. Panggilan teratas memakai `bagan` (akar = pimpinan); panggilan rekursif memakai
  `rel` (sub unit yang digambar sebagai rel menurun). Tiap simpul: nama, pejabat, posisi (staf/lini/bawah), anak.
  Gayanya di resources/css/situs.css bagian "Bagan struktur organisasi".
--}}
@props(['bagan' => null, 'rel' => null])

@if ($rel !== null)
  <ul class="anak">
    @foreach ($rel as $x)
      <li>
        <p class="kotak kecil">{{ $x['nama'] ?? '' }}@if (filled($x['pejabat'] ?? null))<span class="pejabat">{{ $x['pejabat'] }}</span>@endif</p>
        @if (! empty($x['anak']))
          <x-situs.bagan :rel="$x['anak']" />
        @endif
      </li>
    @endforeach
  </ul>
@else
  @php
    // Unit langsung di bawah pimpinan: staf di samping batang, lini berjajar, bawah di ujung batang.
    $anak = collect($bagan['anak'] ?? [])->map(fn ($x) => [...$x, 'letak' => in_array($x['posisi'] ?? null, ['staf', 'bawah'], true) ? $x['posisi'] : 'lini']);
    $lini = $anak->where('letak', 'lini')->values();
    $susunan = $anak->where('letak', 'staf')->concat($lini)->concat($anak->where('letak', 'bawah'))->values();
  @endphp
  <div class="bagan" style="--kolom: {{ max($lini->count(), 1) }}">
    <p class="kotak utama">{{ filled($bagan['nama'] ?? null) ? $bagan['nama'] : 'Kepala Dinas' }}@if (filled($bagan['pejabat'] ?? null))<span class="pejabat">{{ $bagan['pejabat'] }}</span>@endif</p>
    @if ($susunan->isNotEmpty())
      <ul @class(['cabang', 'ada-bawah' => $susunan->last()['letak'] === 'bawah'])>
        @php $urutLini = 0; @endphp
        @foreach ($susunan as $x)
          @php $keLini = $x['letak'] === 'lini' ? $urutLini++ : null; @endphp
          <li @class([$x['letak'], 'awal' => $keLini === 0, 'akhir' => $keLini !== null && $keLini === $lini->count() - 1])>
            <p class="kotak tengah">{{ $x['nama'] ?? '' }}@if (filled($x['pejabat'] ?? null))<span class="pejabat">{{ $x['pejabat'] }}</span>@endif</p>
            @if (! empty($x['anak']))
              <x-situs.bagan :rel="$x['anak']" />
            @endif
          </li>
        @endforeach
      </ul>
    @endif
  </div>
@endif
