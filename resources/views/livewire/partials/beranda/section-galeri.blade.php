<div>
	<div class="container">
		<x-section-title
			title="Galeri Kegiatan Diskominfo<br>Kota Kendari"
			desc="Lihat dokumentasi kegiatan dan program Diskominfo Kota Kendari dalam mendukung tranformasi digital." />
	</div>

	<div
		data-hs-carousel='{
			"isAutoPlay": true,
			"loadingClasses": "opacity-0",
			"dotsItemClasses": "hs-carousel-active:bg-white/80 hs-carousel-active:border-gray-300 size-2 lg:size-3 hs-carousel-active:w-3 lg:hs-carousel-active:w-5 border border-gray-400 rounded-sm cursor-pointer",
			"slidesQty": {
				"xs": 1,
				"lg": 3
			}
		}'
		class="relative">

		<div class="hs-carousel w-full overflow-hidden bg-white rounded-lg">
			<div class="relative min-h-72 -mx-1">
				<div
					class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap opacity-0 transition-transform duration-700">

					@foreach ($galeri as $item)
						<div class="hs-carousel-slide px-1">
							<div class="h-full">
								<img src="{{ asset("storage/$item->gambar") }}"
									class="w-full h-72 object-cover rounded-lg shadow border border-gray-100" />
							</div>
						</div>
					@endforeach

				</div>
			</div>
		</div>

		<!-- Prev Button -->
		<button type="button"
			class="hs-carousel-prev hs-carousel-disabled:opacity-50 hs-carousel-disabled:pointer-events-none absolute inset-y-0 start-0 inline-flex justify-center items-center w-11.5 h-full text-white hover:bg-gray-800/10 focus:outline-hidden focus:bg-gray-800/10 rounded-s-lg">
			<svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
				stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="m15 18-6-6 6-6" />
			</svg>
			<span class="sr-only">Previous</span>
		</button>

		<!-- Next Button -->
		<button type="button"
			class="hs-carousel-next hs-carousel-disabled:opacity-50 hs-carousel-disabled:pointer-events-none absolute inset-y-0 end-0 inline-flex justify-center items-center w-11.5 h-full text-white hover:bg-gray-800/10 focus:outline-hidden focus:bg-gray-800/10 rounded-e-lg">
			<svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
				stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="m9 18 6-6-6-6" />
			</svg>
			<span class="sr-only">Next</span>
		</button>

		<!-- Dots -->
		<div class="hs-carousel-pagination flex justify-center absolute bottom-3 start-0 end-0 gap-x-2"></div>
	</div>
</div>
