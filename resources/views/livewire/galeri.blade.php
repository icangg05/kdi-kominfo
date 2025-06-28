<div>
	<x-layouts.page-berita
		:breadcrumb="[['Galeri', '#']]"
		:title="'Galeri Kegiatan Diskominfo'"
		:desc="'Dokumentasi berbagai kegiatan dan momen penting yang diselenggarakan oleh Dinas Komunikasi dan Informatika Kota Kendari'">

		@if ($data->count() > 0)
			{{-- Galeri --}}
			<div class="columns-1 sm:columns-2 lg:columns-3 gap-4 px-4 sm:px-6 lg:px-0 space-y-4">
				@foreach ($data as $item)
					<div class="relative group overflow-hidden rounded-xl shadow-md">
						<a
							href="javascript:void(0)"
							data-no-navigate
							data-fancybox="galeri"
							data-src="{{ asset("storage/$item->gambar") }}"
							data-caption="{{ $item['judul'] }}"
							class="group relative block overflow-hidden rounded-lg shadow">
							<img src="{{ asset("storage/$item->gambar") }}" alt="{{ $item['judul'] }}"
								class="w-full h-auto object-cover transition-transform duration-300 group-hover:scale-105">
							<div
								class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-end p-4">
								<h3 class="text-white font-semibold text-sm">{{ $item['judul'] }}</h3>
								<p class="mt-2.5 text-xs text-gray-200">{{ Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
								</p>
							</div>
						</a>
					</div>
				@endforeach
			</div>
			{{-- End Galeri --}}
		@else
			<x-empty-card message="Tidak ada galeri yang tersedia." />
		@endif
	</x-layouts.page-berita>
</div>
