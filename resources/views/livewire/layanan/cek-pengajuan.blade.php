@use('App\Support\Situs')
@php
  $warnaStatus = [
    'menunggu' => 'border border-biru-200 bg-langit text-biru-800 dark:border-biru-700 dark:text-biru-100',
    'diproses' => 'bg-nila text-white',
    'disetujui' => 'bg-toska text-white',
    'ditolak' => 'bg-darurat text-white',
  ];
@endphp

<div>
  <x-situs.judul-halaman
    judul="Cek Status Pengajuan"
    keterangan="Masukkan nomor HP yang Anda isi saat mengajukan layanan subdomain, email dinas, atau TTE. Semua pengajuan dengan nomor itu akan tampil."
    :remah="[['label' => 'Layanan'], ['label' => 'Cek Status Pengajuan']]"
  />

  <div class="wadah py-12">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,26rem)_minmax(0,1fr)] lg:gap-12">
      <form wire:submit="cari" novalidate class="bayangan-kartu h-fit space-y-5 rounded border border-garis bg-white p-6 sm:p-8 dark:bg-malam-kartu">
        <x-situs.isian model="whatsapp" jenis="tel" label="Nomor HP (WhatsApp) pemohon" placeholder="Mis. 0812 3456 7890" bantuan="Nomor yang diisi saat mengajukan." autocomplete="tel" />
        <button type="submit" wire:loading.attr="disabled" wire:target="cari" class="inline-flex w-full items-center justify-center gap-2 rounded bg-biru-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-biru-700 disabled:opacity-60">
          <x-situs.ikon nama="cari" class="size-4" />
          <span wire:loading.remove wire:target="cari">Cek status</span>
          <span wire:loading wire:target="cari">Mencari…</span>
        </button>
        <p class="text-xs leading-relaxed text-teks-lembut">
          Belum punya nomor? Ajukan lewat halaman
          <a href="/layanan/subdomain-hosting#ajukan" wire:navigate class="font-semibold text-biru-600 hover:underline dark:text-biru-300">Subdomain</a>,
          <a href="/layanan/email-dinas#ajukan" wire:navigate class="font-semibold text-biru-600 hover:underline dark:text-biru-300">Email Dinas</a>, atau
          <a href="/layanan/pengajuan-tte#ajukan" wire:navigate class="font-semibold text-biru-600 hover:underline dark:text-biru-300">TTE</a>.
        </p>
      </form>

      <div aria-live="polite">
        @if ($pengajuan)
          @if ($daftar->count() > 1)
            <button type="button" wire:click="kembali" class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-biru-600 hover:underline dark:text-biru-300">
              <x-situs.ikon nama="panah-kiri" class="size-4" />
              Semua pengajuan ({{ $daftar->count() }})
            </button>
          @endif
          @php
            $selesai = in_array($pengajuan->status, ['disetujui', 'ditolak'], true);
            $tahap = [
              ['judul' => 'Pengajuan diterima', 'waktu' => $pengajuan->created_at, 'aktif' => true],
              ['judul' => 'Diverifikasi petugas', 'waktu' => null, 'aktif' => $pengajuan->status !== 'menunggu'],
              ['judul' => $pengajuan->status === 'ditolak' ? 'Pengajuan ditolak' : 'Pengajuan disetujui', 'waktu' => $selesai ? $pengajuan->diverifikasi_pada : null, 'aktif' => $selesai],
            ];
          @endphp
          <article class="bayangan-kartu overflow-hidden rounded border border-garis bg-white dark:bg-malam-kartu">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-garis p-6 sm:p-8">
              <div>
                <p class="text-xs font-semibold tracking-wide text-biru-600 uppercase dark:text-biru-300">{{ $pengajuan->labelLayanan() }}</p>
                <h2 class="mt-1 font-mono text-xl font-bold tracking-wider text-biru-950 dark:text-white">{{ $pengajuan->nomor }}</h2>
                <p class="mt-1 text-sm text-teks-lembut">{{ $pengajuan->instansi }}</p>
              </div>
              <span class="rounded px-3 py-1.5 text-sm font-semibold {{ $warnaStatus[$pengajuan->status] ?? '' }}">{{ $pengajuan->labelStatus() }}</span>
            </div>

            <div class="p-6 sm:p-8">
              <ol class="space-y-5">
                @foreach ($tahap as $t)
                  <li class="flex gap-4">
                    <span @class([
                      'flex size-8 shrink-0 items-center justify-center rounded',
                      $t['aktif'] ? ($pengajuan->status === 'ditolak' && $loop->last ? 'bg-darurat text-white' : 'bg-biru-600 text-white') : 'border border-garis text-teks-lembut',
                    ])>
                      <x-situs.ikon :nama="$t['aktif'] ? ($pengajuan->status === 'ditolak' && $loop->last ? 'silang' : 'centang') : 'jam'" class="size-4" />
                    </span>
                    <div class="pt-1">
                      <p @class(['text-sm font-semibold', $t['aktif'] ? 'text-biru-950 dark:text-white' : 'text-teks-lembut'])>{{ $t['judul'] }}</p>
                      @if ($t['waktu'])
                        <p class="mt-0.5 text-xs text-teks-lembut">{{ Situs::tanggal($t['waktu']) }}, {{ $t['waktu']->timezone('Asia/Makassar')->format('H.i') }} WITA</p>
                      @endif
                    </div>
                  </li>
                @endforeach
              </ol>

              @if ($pengajuan->catatan_admin)
                <div class="mt-7 rounded bg-langit p-4 dark:bg-white/5">
                  <p class="text-xs font-semibold tracking-wide text-biru-700 uppercase dark:text-biru-300">Catatan petugas</p>
                  <p class="mt-1.5 text-sm leading-relaxed whitespace-pre-line text-teks">{{ $pengajuan->catatan_admin }}</p>
                </div>
              @endif

              <dl class="mt-7 grid gap-x-6 gap-y-4 border-t border-garis pt-6 text-sm sm:grid-cols-2">
                <div>
                  <dt class="text-xs text-teks-lembut">Pemohon</dt>
                  <dd class="mt-0.5 font-semibold">{{ $pengajuan->nama_pemohon }}</dd>
                </div>
                <div>
                  <dt class="text-xs text-teks-lembut">Diajukan</dt>
                  <dd class="mt-0.5 font-semibold">{{ Situs::tanggal($pengajuan->created_at) }}</dd>
                </div>
              </dl>
            </div>
          </article>
        @elseif ($daftar->isNotEmpty())
          <p class="mb-4 text-sm text-teks-lembut">
            {{ $daftar->count() }} pengajuan untuk nomor ini. Pilih salah satu untuk melihat tahapan dan catatan petugas.
          </p>
          <ul class="space-y-3">
            @foreach ($daftar as $p)
              <li wire:key="{{ $p->nomor }}">
                <button type="button" wire:click="pilih(@js($p->nomor))" class="bayangan-kartu group flex w-full items-center gap-4 rounded border border-garis bg-white p-4 text-left transition hover:border-biru-400 sm:p-5 dark:bg-malam-kartu dark:hover:border-biru-600">
                  <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold tracking-wide text-biru-600 uppercase dark:text-biru-300">{{ $p->labelLayanan() }}</p>
                    <p class="mt-0.5 font-mono font-bold tracking-wider text-biru-950 dark:text-white">{{ $p->nomor }}</p>
                    <p class="mt-0.5 truncate text-sm text-teks-lembut">{{ $p->instansi }} · {{ Situs::tanggal($p->created_at) }}</p>
                  </div>
                  <span class="shrink-0 rounded px-2.5 py-1 text-xs font-semibold {{ $warnaStatus[$p->status] ?? '' }}">{{ $p->labelStatus() }}</span>
                  <x-situs.ikon nama="panah-kanan" class="size-4 shrink-0 text-teks-lembut transition group-hover:translate-x-0.5 max-sm:hidden" />
                </button>
              </li>
            @endforeach
          </ul>
        @elseif ($dicari)
          <div class="rounded border border-dashed border-biru-200 bg-biru-50/50 px-6 py-14 text-center dark:border-biru-800 dark:bg-white/5">
            <x-situs.ikon nama="silang-lingkar" class="mx-auto size-10 text-biru-300 dark:text-biru-700" />
            <p class="mt-3 font-semibold text-biru-950 dark:text-white">Belum ada pengajuan dengan nomor ini</p>
            <p class="mx-auto mt-1 max-w-md text-sm text-teks-lembut">Pastikan nomor HP sama dengan yang diisi saat mengajukan.</p>
          </div>
        @else
          <div class="rounded border border-dashed border-biru-200 bg-biru-50/50 px-6 py-14 text-center dark:border-biru-800 dark:bg-white/5">
            <x-situs.ikon nama="berkas" class="mx-auto size-10 text-biru-300 dark:text-biru-700" />
            <p class="mx-auto mt-3 max-w-md text-sm text-teks-lembut">Daftar pengajuan Anda beserta status, tahapan verifikasi, dan catatan petugas akan tampil di sini.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
