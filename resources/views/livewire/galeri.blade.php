<div>
	<x-layouts.page-berita
		:breadcrumb="[['Galeri', '#']]"
		:title="'Galeri Kegiatan Diskominfo'"
		:desc="'Dokumentasi berbagai kegiatan dan momen penting yang diselenggarakan oleh Dinas Komunikasi dan Informatika Kota Kendari'">

		{{-- Galeri --}}
		<div class="columns-1 sm:columns-2 lg:columns-3 gap-4 px-4 sm:px-6 lg:px-0 space-y-4">
			@php
				$galeri = [
				    [
				        'img' => 'https://picsum.photos/id/1018/800/600',
				        'judul' => 'Rapat Koordinasi Bidang TIK',
				        'tanggal' => '12 Juni 2025',
				    ],
				    [
				        'img' => 'https://picsum.photos/id/1025/600/900',
				        'judul' => 'Workshop Literasi Digital',
				        'tanggal' => '08 Juni 2025',
				    ],
				    [
				        'img' => 'https://picsum.photos/id/1024/800/500',
				        'judul' => 'Sosialisasi Aplikasi Layanan Publik',
				        'tanggal' => '05 Juni 2025',
				    ],
				    [
				        'img' => 'https://picsum.photos/id/1035/700/500',
				        'judul' => 'Diskusi Keamanan Siber',
				        'tanggal' => '01 Juni 2025',
				    ],
				    [
				        'img' => 'https://picsum.photos/id/1031/700/800',
				        'judul' => 'Pelatihan Persandian Daerah',
				        'tanggal' => '28 Mei 2025',
				    ],
				    [
				        'img' => 'https://picsum.photos/id/1041/600/400',
				        'judul' => 'Kunjungan Kerja ke BPS',
				        'tanggal' => '25 Mei 2025',
				    ],
				];
			@endphp

			@foreach ($galeri as $item)
				<div class="relative group overflow-hidden rounded-xl shadow-md">
					<a
						href="javascript:void(0)"
						data-no-navigate
						data-fancybox="galeri"
						data-src="{{ $item['img'] }}"
						data-caption="{{ $item['judul'] }}"
						class="group relative block overflow-hidden rounded-lg shadow">
						<img src="{{ $item['img'] }}" alt="{{ $item['judul'] }}"
							class="w-full h-auto object-cover transition-transform duration-300 group-hover:scale-105">
						<div
							class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-end p-4">
							<h3 class="text-white font-semibold text-sm">{{ $item['judul'] }}</h3>
							<p class="text-xs text-gray-200">{{ $item['tanggal'] }}</p>
						</div>
					</a>
				</div>
			@endforeach
		</div>
		{{-- End Galeri --}}
	</x-layouts.page-berita>
</div>
