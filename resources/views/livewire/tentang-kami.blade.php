<div>
	<x-layouts.page
		:breadcrumb="[['fa-solid fa-building-columns', 'Profil Dinas', '#'], ['Tentang Kami', '#']]"
		:title="'Tentang Kami'"
		:desc="'Diskominfo Kota Kendari berperan dalam percepatan transformasi digital melalui pengembangan ekosistem administrasi pemerintahan dan pelayanan publik terintegrasi di Kota Kendari'">

		{{-- Card Title --}}
		<h2 class="text-center font-bold text-xl lg:text-3xl text-gray-800"> Sejarah </h2>
		{{-- Card Title --}}

		{{-- Isi Sejarah --}}
		<div class="mt-6 min-w-full lg:mt-12 prose prose-sm lg:prose prose-li:leading-normal lg:prose-li:leading-tight">
			<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet pariatur a sapiente neque quidem sunt et distinctio
				velit, debitis autem voluptatum error facere id nisi commodi aspernatur, optio quibusdam. Odit molestias beatae at
				quas quos harum voluptate laudantium necessitatibus maxime eum minus totam, aut, nam dignissimos quasi labore? Odit,
				autem.</p>
			<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium, fugiat, nihil eos itaque ex ea perferendis
				quod quasi deserunt sapiente voluptate soluta nemo id modi atque? Nemo unde saepe, iusto dolore labore, inventore
				vel ab veritatis harum, adipisci repellendus ut eum? Natus ab exercitationem perspiciatis illo suscipit ducimus unde
				ipsam aliquid dolore nisi culpa corporis harum magni explicabo provident ut consectetur, facilis laborum cupiditate
				impedit eum blanditiis fugit voluptatem veritatis. Non beatae nam, similique expedita dolorum ducimus consectetur
				voluptatum sequi suscipit ea libero ipsam distinctio labore omnis dolore laborum et delectus commodi enim fuga nihil
				ipsum consequuntur. Perferendis, exercitationem quis.</p>
		</div>
		{{-- Isi Sejarah --}}

		{{-- Isi Visi & Misi --}}
		<div class="max-w-4xl mx-auto px-6 my-12">
			<!-- Header -->
			<div class="font-sen border-4 border-dashed border-primary rounded-2xl p-6 text-center mb-8">
				<h2 class="text-xl leading-[24px] lg:leading-[28px] lg:text-3xl font-bold text-primary	">Visi Misi Pemerintahan Kota
					Kendari</h2>
				<p class="text-sm md:text-base text-gray-500 mt-2">Periode 2025 - Sekarang</p>
			</div>

			<!-- Visi -->
			<div class="bg-primary text-white rounded-2xl px-6 py-6 shadow-md mb-10">
				<h3 class="text-lg lg:text-xl font-semibold mb-2">Visi</h3>
				<p class="text-sm lg:text-base leading-[28px] lg:leading-[24px]">
					"Terwujudnya Kota Kendari Tahun 2029 sebagai Kota Layak Huni yang Semakin Maju, Berdaya Saing, Adil, Sejahtera, dan
					Berkelanjutan.""
				</p>
			</div>

			<!-- Misi -->
			<div>
				<h3 class="text-lg lg:text-2xl font-bold text-primary mb-6">Misi</h3>
				@php
					$misi = [
					    'Menyediakan, memelihara dan mengembangkan berbagai fasilitas yang layak dan mencukupi untuk kebutuhan warga dan pengguna kota lainnya (yakni fasilitas umum, sosial, ruang publik, dan lainnya).',
					    'Mewujudkan Tata Penyelenggaraan Kota yang baik (good city governance), meliputi tatakelola pemerintahan Kota yang baik, partisipasi warga kota yang baik dalam pengelolaan kota, dan juga kenyamanan bagi pengguna kota yang baik.',
					    'Pembangunan Infrastruktur Kota yang terintegrasi, efisien, dan ramah lingkungan, dalam rangka memenuhi pelayanan dasar yang berkualitas.',
					    'Peningkatan Pelayanan Sosial dan Kesejahteraan Warga Kota (Pendidikan, Kesehatan, dan lainnya).',
					    'Membangun Perekonomian yang Kokoh dan Berkeadilan.',
					    'Mengokohkan Kehidupan Sosial Kemasyarakatan.',
					    'Meningkatkan Kualitas Hidup Warga Kota melalui Pemberdayaan Masyarakat dan Pengentasan Kemiskinan.',
					];
				@endphp
				<div class="space-y-3.5 lg:space-y-4">
					@foreach ($misi as $i => $item)
						<div class="flex items-start gap-x-4">
							<div
								class="flex-shrink-0 bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">
								{{ $i + 1 }}
							</div>
							<p class="text-gray-700 text-sm lg:text-base leading-[24px] lg:leading-relaxed">
								{{ $item }}
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
				<div class="md:order-2 relative grow overflow-hidden min-h-96 bg-white rounded-lg">
					<div
						class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700 opacity-0">
						@for ($i = 0; $i < 7; $i++)
							<div class="hs-carousel-slide">
								<img src="https://picsum.photos/800/600?random={{ $i }}" alt="Galeri {{ $i }}"
									alt="Foto Ruangan"
									class="object-cover w-full h-full" />
							</div>
						@endfor
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
						class="scrollbar-modern hs-carousel-pagination max-h-96 flex flex-row md:flex-col gap-2 overflow-x-auto md:overflow-x-hidden md:overflow-y-auto">
						@for ($i = 0; $i < 7; $i++)
							<div
								class="hs-carousel-pagination-item shrink-0 border border-gray-200 rounded-md overflow-hidden cursor-pointer size-20 md:size-32 hs-carousel-active:border-blue-400">
								<img src="https://picsum.photos/800/600?random={{ $i }}" alt="Galeri {{ $i }}"
									alt="Foto Ruangan"
									class="object-cover w-full h-full" />
							</div>
						@endfor
					</div>
				</div>
			</div>
		</div>
		{{-- Foto Ruangan Kominfo --}}
	</x-layouts.page>
</div>
