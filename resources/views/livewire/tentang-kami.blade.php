<div>
	<x-layouts.page
		:breadcrumb="[['fa-solid fa-building-columns', 'Profil Dinas', '#'], ['Tentang Kami', '#']]"
		:title="'Tentang Kami'"
		:desc="'Diskominfo Kota Kendari berperan dalam percepatan transformasi digital melalui pengembangan ekosistem administrasi pemerintahan dan pelayanan publik terintegrasi di Kota Kendari'">

		{{-- Card Title --}}
		<h2 class="text-center font-bold text-xl lg:text-3xl text-gray-800"> Sejarah </h2>
		{{-- Card Title --}}

		{{-- Isi Sejarah --}}
		<div
			class="[&_*]:text-cText mt-6 min-w-full lg:mt-12 prose prose-sm lg:prose prose-li:leading-normal lg:prose-li:leading-tight">
			{!! $sejarah !!}
		</div>
		{{-- Isi Sejarah --}}

		{{-- Isi Visi & Misi --}}
		<div class="max-w-4xl mx-auto px-6 my-12">
			<!-- Header -->
			<div class="font-sen border-4 border-dashed border-primary rounded-2xl p-6 text-center mb-8">
				<h2 class="text-xl leading-[24px] lg:leading-[28px] lg:text-3xl font-bold text-primary	">Visi Misi Diskominfo Kota
					Kendari</h2>
				<p class="text-sm md:text-base text-gray-500 mt-2">Periode {{ $awalPeriode }} - Sekarang</p>
			</div>

			<!-- Visi -->
			<div class="bg-primary text-white rounded-2xl px-6 py-6 shadow-md mb-10">
				<h3 class="text-lg lg:text-xl font-semibold mb-2">Visi</h3>
				<p class="text-sm lg:text-base leading-[28px] lg:leading-[24px]">
					{{ $visi }}
				</p>
			</div>

			<!-- Misi -->
			<div>
				<h3 class="text-lg lg:text-2xl font-bold text-primary mb-6">Misi</h3>
				<div class="space-y-3.5 lg:space-y-4">
					@foreach ($misi as $i => $item)
						<div class="flex items-start gap-x-4">
							<div
								class="flex-shrink-0 bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">
								{{ $i + 1 }}
							</div>
							<p class="text-cText text-sm lg:text-base leading-[24px] lg:leading-relaxed">
								{{ $item['misi'] }}
							</p>
						</div>
					@endforeach
				</div>

			</div>
		</div>

		{{-- Foto Ruangan Kominfo --}}
		<div data-hs-carousel='{
    "loadingClasses": "opacity-0"
  }' class="relative">
			<div class="hs-carousel flex flex-col md:flex-row gap-2">
				<div class="md:order-2 relative grow overflow-hidden min-h-55 lg:min-h-120 bg-white rounded-lg">
					<div
						class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700 opacity-0">
						@foreach ($fotoDiskominfo as $i => $item)
							<div class="hs-carousel-slide">
								<img src="{{ asset("storage/$item") }}" alt="foto-{{ $i }}"
									alt="Foto Ruangan"
									class="object-cover w-full h-full" />
							</div>
						@endforeach
					</div>

					<button type="button"
						class="hs-carousel-prev hs-carousel-disabled:opacity-50 hs-carousel-disabled:pointer-events-none absolute inset-y-0 start-0 inline-flex justify-center items-center w-11.5 h-full text-white hover:bg-gray-800/10 focus:outline-hidden focus:bg-gray-800/10 rounded-s-lg">
						<span class="text-2xl" aria-hidden="true">
							<svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
								fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="m15 18-6-6 6-6"></path>
							</svg>
						</span>
						<span class="sr-only">Previous</span>
					</button>
					<button type="button"
						class="hs-carousel-next hs-carousel-disabled:opacity-50 hs-carousel-disabled:pointer-events-none absolute inset-y-0 end-0 inline-flex justify-center items-center w-11.5 h-full text-white hover:bg-gray-800/10 focus:outline-hidden focus:bg-gray-800/10 rounded-e-lg">
						<span class="sr-only">Next</span>
						<span class="text-2xl" aria-hidden="true">
							<svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
								fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="m9 18 6-6-6-6"></path>
							</svg>
						</span>
					</button>
				</div>

				<div class="md:order-1 flex-none">
					<div
						class="scrollbar-modern hs-carousel-pagination max-h-120 flex flex-row md:flex-col gap-2 overflow-x-auto md:overflow-x-hidden md:overflow-y-auto">
						@foreach ($fotoDiskominfo as $i => $item)
							<div
								class="hs-carousel-pagination-item shrink-0 border border-gray-200 rounded-md overflow-hidden cursor-pointer size-20 md:size-32 hs-carousel-active:border-blue-400">
								<img src="{{ asset("storage/$item") }}" alt="foto-{{ $i }}"
									alt="Foto Ruangan"
									class="object-cover w-full h-full" />
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
		{{-- Foto Ruangan Kominfo --}}
	</x-layouts.page>
</div>
