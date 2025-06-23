<div
	data-hs-carousel='{
			"loadingClasses": "opacity-0",
			"dotsItemClasses": "hs-carousel-active:bg-blue-700 hs-carousel-active:border-blue-700 size-3 border border-gray-400 rounded-full cursor-pointer",
			"isAutoPlay": true
		}'
	class="relative">

	<div class="hs-carousel relative overflow-hidden w-full h-[29rem] lg:h-[44rem] bg-white rounded-lg">
		<div
			class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700 opacity-0">

			<!-- Slide 1 -->
			<div class="hs-carousel-slide relative w-full">
				<div class="absolute inset-0 bg-cover bg-center"
					style="background-image: url({{ asset('img/slider-1.webp') }});"></div>
				<div class="absolute inset-0 bg-gray-700/80 lg:bg-black/60"></div> <!-- Overlay hitam -->
			</div>

			<!-- Slide 2 -->
			<div class="hs-carousel-slide relative w-full">
				<div class="absolute inset-0 bg-cover bg-center"
					style="background-image: url({{ asset('img/slider-2.webp') }});"></div>
				<div class="absolute inset-0 bg-gray-700/80 lg:bg-black/60"></div>
			</div>

			<!-- Slide 3 -->
			<div class="hs-carousel-slide relative w-full">
				<div class="absolute inset-0 bg-cover bg-center"
					style="background-image: url({{ asset('img/slider-3.webp') }});"></div>
				<div class="absolute inset-0 bg-gray-700/80 lg:bg-black/60"></div>
			</div>

		</div>
	</div>

	<!-- Center Content -->
	<div class="z-10 absolute container top-[7rem] lg:top-[14.5rem] left-1/2 -translate-x-1/2">
		<p class="font-extrabold text-2xl lg:text-4xl leading-tight text-white/95">
			Dinas Komunikasi
			<span class="text-acsent">Informatika</span>
			<br>Kota
			<span class="text-acsent">Kendari</span>
		</p>
		<p class="mt-5 lg:mt-6 mb-8 lg:mb-8 text-sm lg:text-base max-w-4xl text-white/90">
			Dinas Kominfo Kota Kendari berkomitmen untuk menyediakan layanan teknologi informasi yang inovatif, aman, dan
			terpercaya guna mendukung transformasi digital menuju Smart City.
		</p>
		<div class="flex flex-col lg:flex-row items-center gap-3">
			<a href="#layanan-kominfo"
				class="w-full lg:w-fit py-2.5 px-5 text-center text-sm font-medium rounded-lg border border-transparent bg-primary text-white">
				Layanan
			</a>
			<a wire:navigate href="{{ route('profil-dinas.tentang-kami') }}"
				class="w-full lg:w-fit py-2.5 px-5 text-center text-sm font-medium rounded-lg border border-transparent bg-secondary text-white">
				Tentang Kami
			</a>
		</div>

		{{-- Three Card --}}
		@include('livewire.partials.beranda.three-cards')
		{{-- Three Card --}}
	</div>

	<!-- Previous Button -->
	<button type="button"
		class="hidden ml-3 translate-y-10 hs-carousel-prev hs-carousel-disabled:opacity-50 hs-carousel-disabled:pointer-events-none absolute inset-y-0 start-0 lg:inline-flex justify-center items-center z-20">
		<span class="text-3xl text-primary">
			<i class="fa-solid fa-circle-chevron-left bg-white rounded-full border border-primary"></i>
		</span>
	</button>

	<!-- Next Button -->
	<button type="button"
		class="hidden mr-3 translate-y-10 hs-carousel-next hs-carousel-disabled:opacity-50 hs-carousel-disabled:pointer-events-none absolute inset-y-0 end-0 lg:inline-flex justify-center items-center z-20">
		<span class="text-3xl text-primary">
			<i class="fa-solid fa-circle-chevron-right bg-white rounded-full border border-primary"></i>
		</span>
	</button>

</div>
