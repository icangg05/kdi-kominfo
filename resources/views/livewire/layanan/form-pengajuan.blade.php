@use('App\Models\PengajuanLayanan')
@php
  $domain = $layanan === 'subdomain-hosting';
  $langkah = [
    $domain ? 'Isi data subdomain dan penanggung jawab' : 'Isi data pemohon',
    $jenis['ktp'] ? 'Unggah KTP dan surat permohonan' : 'Unggah surat permohonan',
    'Kirim, lalu pantau status dengan nomor HP',
  ];
@endphp

<section id="ajukan" class="mt-16 scroll-mt-20" aria-labelledby="judul-ajukan">
  <div class="bayangan-kartu overflow-hidden rounded border border-garis bg-white dark:bg-malam-kartu">
    <div class="grid lg:grid-cols-[20rem_minmax(0,1fr)] xl:grid-cols-[22rem_minmax(0,1fr)]">
      <div class="motif-komdigi bg-biru-900 p-7 text-white lg:p-8">
        <p class="text-xs font-bold tracking-wide text-biru-300 uppercase">Pengajuan daring</p>
        <h2 id="judul-ajukan" class="mt-2 text-2xl leading-tight font-bold text-balance">Ajukan {{ $jenis['label'] }}</h2>
        <p class="mt-3 text-sm leading-relaxed text-biru-100">
          Lengkapi data di formulir ini dan unggah surat permohonan yang sudah ditandatangani pimpinan perangkat
          daerah. Tim Diskominfo memverifikasi pengajuan Anda, dan hasilnya bisa dipantau dengan nomor HP yang Anda isi.
        </p>

        <ol class="mt-7 space-y-3 text-sm">
          @foreach ($langkah as $i => $l)
            <li class="flex items-center gap-3">
              <span class="flex size-7 shrink-0 items-center justify-center rounded bg-white/15 text-xs font-bold tabular-nums">{{ $i + 1 }}</span>
              {{ $l }}
            </li>
          @endforeach
        </ol>

        <a href="/layanan/cek-pengajuan" wire:navigate class="group mt-8 inline-flex items-center gap-2.5 text-sm font-semibold">
          <span class="flex size-7 items-center justify-center rounded border border-white/40 transition group-hover:bg-white group-hover:text-biru-900">
            <x-situs.ikon nama="cari" class="size-3.5" />
          </span>
          Sudah mengajukan? Cek status
        </a>
      </div>

      <div class="p-6 sm:p-8 lg:p-10">
        @if ($terkirim)
          <div class="flex flex-col items-start" role="status">
            <span class="flex size-14 items-center justify-center rounded bg-toska text-white">
              <x-situs.ikon nama="centang" class="size-7" />
            </span>
            <h3 class="mt-5 text-2xl font-bold text-biru-950 dark:text-white">Pengajuan terkirim</h3>
            <p class="mt-2 max-w-xl text-sm leading-relaxed text-teks-lembut">
              Pengajuan Anda sudah kami terima dan menunggu verifikasi. Petugas akan menghubungi Anda lewat WhatsApp.
              Status bisa dicek kapan saja dengan nomor HP yang Anda isi; nomor di bawah ini menandai pengajuan ini.
            </p>

            <div class="mt-6 flex w-full max-w-md items-center gap-3 rounded border border-biru-200 bg-langit p-4 dark:border-biru-800" x-data="{ disalin: false }">
              <div class="min-w-0 flex-1">
                <p class="text-xs text-teks-lembut">Nomor pengajuan</p>
                <p class="mt-0.5 font-mono text-xl font-bold tracking-wider text-biru-900 dark:text-white">{{ $terkirim }}</p>
              </div>
              <button
                type="button"
                x-on:click="navigator.clipboard.writeText(@js($terkirim)).then(() => { disalin = true; setTimeout(() => disalin = false, 2000) })"
                class="flex h-10 shrink-0 items-center gap-2 rounded border border-biru-300 bg-white px-3 text-sm font-semibold text-biru-700 transition hover:border-biru-600 dark:border-biru-700 dark:bg-malam-kartu dark:text-biru-200"
              >
                <x-situs.ikon nama="tautan" class="size-4" />
                <span x-text="disalin ? 'Disalin' : 'Salin'" aria-live="polite">Salin</span>
              </button>
            </div>

            <div class="mt-7 flex flex-wrap gap-3">
              <a href="/layanan/cek-pengajuan?nomor={{ urlencode($terkirim) }}" wire:navigate class="inline-flex items-center gap-2 rounded bg-biru-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-biru-700">
                <x-situs.ikon nama="cari" class="size-4" />
                Cek status pengajuan
              </a>
              <button type="button" wire:click="ajukanLagi" class="inline-flex items-center gap-2 rounded border border-garis px-5 py-3 text-sm font-semibold text-biru-700 transition hover:bg-biru-50 dark:text-biru-200 dark:hover:bg-white/5">
                <x-situs.ikon nama="ulang" class="size-4" />
                Ajukan lagi
              </button>
            </div>
          </div>
        @else
          <form wire:submit="kirim" novalidate class="space-y-5">
            @if ($errors->any())
              <div class="flex items-start gap-3 rounded border border-darurat/30 bg-darurat/5 p-4 text-sm text-darurat dark:text-[#ff8a80]" role="alert">
                <x-situs.ikon nama="peringatan" class="mt-0.5 size-5 shrink-0" />
                <p>Ada {{ $errors->count() }} isian yang perlu diperbaiki. Periksa bagian yang ditandai merah.</p>
              </div>
            @endif

            @if ($domain)
              <x-situs.isian model="instansi" label="Nama instansi/OPD" placeholder="Mis. Dinas Kesehatan Kota Kendari" autocomplete="organization" />
              <x-situs.isian model="rincian.subdomain" jenis="subdomain" label="Nama subdomain" placeholder="Mis. dinkes" bantuan="Huruf kecil, angka, dan tanda hubung." />
              <x-situs.isian model="rincian.jenis_layanan" jenis="select" label="Jenis layanan" :pilihan="['' => 'Pilih jenis layanan'] + PengajuanLayanan::JENIS_LAYANAN_DOMAIN" />
              <div class="grid gap-5 sm:grid-cols-2">
                <x-situs.isian model="rincian.ip_tujuan" label="IP address tujuan" placeholder="Mis. 103.150.12.34" :wajib="false" bantuan="Kosongkan bila server/hosting disiapkan Diskominfo." class="[&_input]:font-mono" />
                <x-situs.isian model="rincian.lokasi_server" label="Lokasi hosting/server" placeholder="Mis. Pusat Data Diskominfo, server OPD" />
              </div>
              <x-situs.isian model="rincian.deskripsi" jenis="textarea" label="Deskripsi fungsi aplikasi/website" placeholder="Mis. portal informasi layanan kesehatan dan jadwal puskesmas untuk masyarakat" />
              <x-situs.isian model="nama_pemohon" label="Nama penanggung jawab" placeholder="Nama lengkap" autocomplete="name" />
            @else
              <div @class(['grid gap-5', 'sm:grid-cols-2' => $layanan === 'email-dinas'])>
                <x-situs.isian model="nama_pemohon" label="Nama lengkap" placeholder="Nama lengkap" autocomplete="name" :bantuan="$jenis['ktp'] ? 'Sesuai KTP.' : null" />
                @if ($layanan === 'email-dinas')
                  <x-situs.isian model="rincian.nip" label="NIP" placeholder="18 digit" inputmode="numeric" class="[&_input]:font-mono" />
                @endif
              </div>
              @if ($jenis['ktp'])
                <div class="grid gap-5 sm:grid-cols-2">
                  <x-situs.isian model="rincian.nik" label="NIK" placeholder="16 digit" inputmode="numeric" class="[&_input]:font-mono" />
                  <x-situs.isian model="rincian.nip" label="NIP" placeholder="18 digit" inputmode="numeric" class="[&_input]:font-mono" />
                </div>
              @endif
              <div class="grid gap-5 sm:grid-cols-2">
                <x-situs.isian model="rincian.tempat_lahir" label="Tempat lahir" placeholder="Mis. Kendari" />
                <x-situs.isian model="rincian.tanggal_lahir" jenis="date" label="Tanggal lahir" autocomplete="bday" />
              </div>
              <div @class(['grid gap-5', 'sm:grid-cols-2' => $jenis['ktp']])>
                <x-situs.isian model="instansi" label="Instansi" placeholder="Mis. Dinas Pendidikan Kota Kendari" autocomplete="organization" />
                @if ($jenis['ktp'])
                  <x-situs.isian model="rincian.jabatan" label="Jabatan" placeholder="Mis. Kepala Bidang Pelayanan" autocomplete="organization-title" />
                @endif
              </div>
            @endif

            <x-situs.isian
              model="whatsapp"
              jenis="tel"
              label="Nomor HP (WhatsApp)"
              placeholder="Mis. 0812 3456 7890"
              autocomplete="tel"
              bantuan="Dipakai petugas Diskominfo untuk menghubungi Anda bila ada kekurangan berkas dan untuk mengabarkan hasil verifikasi, serta untuk cek status pengajuan."
            />

            @if ($jenis['ktp'])
              <x-situs.unggah
                model="ktp"
                label="KTP"
                :berkas="$ktp"
                :jenis="PengajuanLayanan::BERKAS_KTP"
                bantuan="Foto atau pindaian KTP yang terbaca jelas."
                :maks-mb="PengajuanLayanan::BERKAS_MAKS_KB / 1024"
              />
            @endif

            <x-situs.unggah
              model="surat"
              label="Surat permohonan"
              :berkas="$surat"
              :jenis="PengajuanLayanan::BERKAS_SURAT"
              :bantuan="$jenis['isi_surat']"
              :maks-mb="PengajuanLayanan::BERKAS_MAKS_KB / 1024"
            />

            {{-- Perangkap bot: digeser keluar layar (bukan display:none, yang dilewati sebagian bot). --}}
            <div class="absolute -left-[9999px] size-px overflow-hidden" aria-hidden="true">
              <label>Situs web <input type="text" wire:model="situs_web" tabindex="-1" autocomplete="off" /></label>
            </div>

            <div class="flex flex-col gap-3 border-t border-garis pt-6 sm:flex-row sm:items-center sm:justify-between">
              <p class="text-xs leading-relaxed text-teks-lembut">
                @if ($jenis['ktp'])
                  Data, KTP, dan surat hanya dapat dibuka petugas<br class="max-sm:hidden" />
                  yang memverifikasi pengajuan.
                @else
                  Tidak perlu melampirkan KTP atau NPWP.<br class="max-sm:hidden" />
                  Surat hanya dapat dibuka petugas yang memverifikasi pengajuan.
                @endif
              </p>
              <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="kirim, surat, ktp"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded bg-biru-600 px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-biru-700 active:translate-y-px disabled:cursor-wait disabled:opacity-60"
              >
                <span wire:loading.remove wire:target="kirim" class="inline-flex items-center gap-2">
                  <x-situs.ikon nama="panah-kanan" class="size-4" />
                  Kirim pengajuan
                </span>
                <span wire:loading wire:target="kirim">Mengirim…</span>
              </button>
            </div>
          </form>
        @endif
      </div>
    </div>
  </div>
</section>
