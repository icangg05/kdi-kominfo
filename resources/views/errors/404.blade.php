{{--
  404 situs publik memakai layout situs lengkap (header, menu, footer) supaya pengunjung bisa langsung
  pindah halaman. Panel admin dan rute berkas tetap memakai halaman polos errors::minimal.
--}}
@if (request()->is('admin', 'admin/*', 'download/*', 'storage/*', 'livewire*'))
  @include('errors::minimal')
@else
  <x-layouts.situs judul="Halaman Tidak Ditemukan" :noindex="true">
    <div class="wadah flex min-h-[50vh] flex-col items-center justify-center py-20 text-center">
      <p class="text-6xl font-extrabold text-biru-600 dark:text-biru-400">404</p>
      <h1 class="mt-4 text-2xl font-bold text-biru-900 dark:text-white">Halaman tidak ditemukan</h1>
      <p class="mt-3 max-w-md text-teks-lembut">
        Alamat yang Anda tuju mungkin sudah dipindahkan atau tidak pernah ada.
      </p>
      <a href="/" wire:navigate class="mt-8 rounded bg-biru-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-biru-700">
        Kembali ke beranda
      </a>
    </div>
  </x-layouts.situs>
@endif
