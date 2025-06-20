<div>
	<x-layouts.page
		:breadcrumb="[['fa-solid fa-building-columns', 'Profil Dinas', '#'], ['Profil Pimpinan', '#']]"
		:title="'Profil Pimpinan'"
		:desc="'Kepala Dinas Komunikasi dan Informatika Kota Kendari Sulawesi Tenggara Periode 2025 - Sekarang'">

    {{-- Card Title --}}
		<h2 class="text-center font-bold text-xl lg:text-3xl text-gray-800"> Sahuriyanto Meronda, S.P </h2>
    {{-- Card Title --}}

		{{-- Foto Pimpinan --}}
		<div class="mt-4 lg:mt-8 grid grid-cols-2 gap-2 lg:gap-4 lg:grid-cols-3 lg:grid-rows-1">
			<img
				class="aspect-[3/4] lg:aspect-9/10 object-cover rounded w-full h-full"
				src="https://mediakendari.com/wp-content/uploads/2023/03/IMG-20230320-WA0038.jpg"
				alt="img">

			<div class="grid grid-rows-2 gap-2 lg:gap-4 lg:grid-rows-1 lg:grid-cols-2 lg:contents">
				<img
					class="object-cover rounded w-full h-full"
					src="https://cdn.antaranews.com/cache/1200x800/2022/05/31/Kadis-pertanian-kendari.jpg.webp"
					alt="img">
				<img
					class="object-cover rounded w-full h-full"
					src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSlrUG6L3yM8t178XfUJKuHNHPkwYhI-uxvnQ&s"
					alt="img">
			</div>
		</div>
		{{-- Foto Pimpinan --}}

		{{-- Riwayat Pimpinan --}}
		@php
			$riwayatHTML = <<<HTML
			  <h3>Riwayat Jabatan</h3>
			  <ul>
			    <li>Kepala Dinas Komunikasi dan Informatika Provinsi Jawa Barat (2025 - sekarang)</li>
			    <li>Kepala Biro Administrasi Pimpinan Sekretariat Daerah Provinsi Jawa Barat (2023 - 2025)</li>
			    <li>Kepala Bagian Protokol Biro Administrasi Pimpinan Sekretariat Daerah Provinsi Jawa Barat (2020 - 2023)</li>
			    <li>Kepala Bagian Keprotokolan Biro Humas dan Protokol Sekretariat Daerah Provinsi Jawa Barat (2019 - 2020)</li>
			    <li>Kepala Sub Bagian Penata Acara Keprotokolan Biro Humas dan Protokol Sekretariat Daerah Provinsi Jawa Barat (2018 - 2019)</li>
			    <li>Kepala Sub Bagian Kelembagaan Pengelola Urusan Biro Organisasi Sekretariat Daerah Provinsi Jawa Barat (2017 - 2018)</li>
			    <li>Kepala Seksi Operasi dan Pengendalian pada Bidang Ketertiban Umum dan Ketentraman Masyarakat Satpol PP Provinsi Jawa Barat (2013 - 2017)</li>
			    <li>Kepala Sub Bagian Administrasi Pertanahan Biro Pemerintahan Umum Sekretariat Daerah Provinsi Jawa Barat (2012 - 2013)</li>
			    <li>Kepala Sub Bagian Anggaran pada Bagian Keuangan Sekretariat Dewan Kopri Provinsi Jawa Barat (2011 - 2012)</li>
			  </ul>
			  <h3>Riwayat Penugasan/Staf</h3>
			  <ul>
			    <li>Pelaksana pada Sub Bagian Fasilitasi Urusan Pemerintah Kab/Kota Biro Otomnomi Daerah dan Kerjasama (2009 - 2011)</li>
			    <li>Pelaksana Bagian Anggaran Biro Keuangan Sekretariat Daerah Provinsi Jawa Barat (2008 - 2009)</li>
			    <li>Ajudan Gubernur Jawa Barat (2004 - 2008)</li>
			  </ul>
			  <h3>Jenjang Diklat</h3>
			  <ul>
			    <li>Kegiatan Orientasi Kepramukaan (1995)</li>
			    <li>Kursus Bahasa LIA (1997)</li>
			    <li>Kegiatan SIMAK (1998)</li>
			    <li>Latihan Dasar Mental Keprajuritan Praja STPDN (1999)</li>
			    <li>Pelatihan Table Manners (2001)</li>
			    <li>Kursus dan Pelatihan Integrasi Taruna Dewasa Nusantara XXIII (2002)</li>
			    <li>Seminar Laboratorium Unit Kerja (2002)</li>
			  </ul>
			HTML;
		@endphp
		<div class="mt-6 min-w-full lg:mt-12 prose prose-sm lg:prose prose-li:leading-normal lg:prose-li:leading-tight">
			{!! $riwayatHTML !!}
		</div>
		{{-- Riwayat Pimpinan --}}
	</x-layouts.page>
</div>
