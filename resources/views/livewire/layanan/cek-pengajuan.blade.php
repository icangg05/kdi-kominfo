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
        <x-situs.isian model="whatsapp" jenis="tel" :angka="true" :maks="15" label="Nomor HP (WhatsApp) pemohon" placeholder="Mis. 081234567890" bantuan="Angka saja, nomor yang diisi saat mengajukan." autocomplete="tel" />
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
        @if ($daftar->isNotEmpty())
          <p class="mb-4 text-sm text-teks-lembut">
            {{ $daftar->count() }} pengajuan untuk nomor ini. Tekan <span class="font-semibold text-teks">Tahapan</span> untuk melihat proses persetujuan dan catatan petugas.
          </p>
          <ul class="bayangan-kartu divide-y divide-garis overflow-hidden rounded border border-garis bg-white dark:bg-malam-kartu">
            @foreach ($daftar as $p)
              <li wire:key="{{ $p->nomor }}" class="flex flex-wrap items-center gap-x-4 gap-y-3 px-4 py-3.5 sm:px-5">
                <div class="min-w-0 flex-1 basis-60">
                  <p class="flex items-baseline gap-2">
                    <span class="font-mono text-sm font-bold tracking-wider text-biru-950 dark:text-white">{{ $p->nomor }}</span>
                    <span class="truncate text-xs font-semibold tracking-wide text-biru-600 uppercase dark:text-biru-300">{{ $p->labelLayanan() }}</span>
                  </p>
                  {{-- Nama instansi yang panjang dipotong; tanggal dan jam pengajuan selalu utuh. --}}
                  <p class="mt-0.5 flex min-w-0 flex-wrap gap-x-1 text-xs text-teks-lembut">
                    <span class="max-w-full truncate">{{ $p->instansi }}</span>
                    <span aria-hidden="true">·</span>
                    <time datetime="{{ $p->created_at->toIso8601String() }}" class="whitespace-nowrap">{{ Situs::tanggalJam($p->created_at) }}</time>
                  </p>
                </div>
                <div class="flex shrink-0 items-center gap-2.5">
                  <span class="rounded px-2.5 py-1 text-xs font-semibold {{ $warnaStatus[$p->status] ?? '' }}">{{ $p->labelStatus() }}</span>
                  <button
                    type="button"
                    wire:click="pilih(@js($p->nomor))"
                    wire:loading.class="cursor-wait opacity-60"
                    wire:target="pilih(@js($p->nomor))"
                    aria-haspopup="dialog"
                    aria-label="Lihat tahapan pengajuan {{ $p->nomor }}"
                    class="inline-flex items-center gap-1.5 rounded border border-biru-200 px-3 py-1.5 text-xs font-semibold text-biru-700 transition hover:border-biru-500 hover:bg-langit dark:border-biru-800 dark:text-biru-200 dark:hover:bg-white/5"
                  >
                    <x-situs.ikon nama="mata" class="size-3.5" />
                    Tahapan
                  </button>
                </div>
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

  {{--
    Tahapan satu pengajuan. Dirender server saat $nomor terisi (pilih() atau ?nomor= dari tanda terima) dan
    dibuka begitu masuk halaman; wire:key per nomor supaya x-init berjalan lagi untuk pengajuan lain.
    Menutup (tombol, Escape, atau klik latar lewat situs.js) mengosongkan $nomor.
  --}}
  @if ($pengajuan)
    @php
      $selesai = in_array($pengajuan->status, ['disetujui', 'ditolak'], true);
      $tahap = [
        ['judul' => 'Pengajuan diterima', 'waktu' => $pengajuan->created_at, 'aktif' => true],
        ['judul' => 'Diverifikasi petugas', 'waktu' => null, 'aktif' => $pengajuan->status !== 'menunggu'],
        ['judul' => $pengajuan->status === 'ditolak' ? 'Pengajuan ditolak' : 'Pengajuan disetujui', 'waktu' => $selesai ? $pengajuan->diverifikasi_pada : null, 'aktif' => $selesai],
      ];
    @endphp
    <dialog
      wire:key="dialog-{{ $pengajuan->nomor }}"
      x-data
      x-init="$el.showModal()"
      x-on:close="$wire.tutup()"
      aria-labelledby="dialog-pengajuan-judul"
      class="dialog-halus m-auto max-h-[calc(100dvh-2rem)] w-[min(36rem,calc(100vw-2rem))] max-w-none overflow-y-auto rounded bg-white p-0 text-left text-teks shadow-[0_24px_60px_-20px_rgba(7,31,61,0.5)] dark:bg-malam-kartu"
    >
      <div class="flex items-start gap-4 border-b border-garis p-6">
        <div class="min-w-0 flex-1">
          <p class="text-xs font-semibold tracking-wide text-biru-600 uppercase dark:text-biru-300">{{ $pengajuan->labelLayanan() }}</p>
          <h2 id="dialog-pengajuan-judul" class="mt-1 font-mono text-xl font-bold tracking-wider text-biru-950 dark:text-white">{{ $pengajuan->nomor }}</h2>
          <p class="mt-1 text-sm text-teks-lembut">{{ $pengajuan->instansi }}</p>
          <span class="mt-3 inline-block rounded px-2.5 py-1 text-xs font-semibold {{ $warnaStatus[$pengajuan->status] ?? '' }}">{{ $pengajuan->labelStatus() }}</span>
        </div>
        <form method="dialog">
          <button class="-mt-1 -mr-2 flex size-9 items-center justify-center rounded text-teks-lembut transition hover:bg-biru-50 hover:text-biru-900 dark:hover:bg-white/5 dark:hover:text-white" aria-label="Tutup tahapan">
            <x-situs.ikon nama="silang" class="size-5" />
          </button>
        </form>
      </div>

      <div class="p-6">
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
                  <p class="mt-0.5 text-xs text-teks-lembut">{{ Situs::tanggalJam($t['waktu']) }}</p>
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

        <dl class="mt-7 grid grid-cols-2 gap-x-6 gap-y-4 border-t border-garis pt-6 text-sm">
          <div>
            <dt class="text-xs text-teks-lembut">Pemohon</dt>
            <dd class="mt-0.5 font-semibold">{{ $pengajuan->nama_pemohon }}</dd>
          </div>
          <div>
            <dt class="text-xs text-teks-lembut">Diajukan</dt>
            <dd class="mt-0.5 font-semibold">{{ Situs::tanggalJam($pengajuan->created_at) }}</dd>
          </div>
        </dl>
      </div>
    </dialog>
  @endif
</div>
