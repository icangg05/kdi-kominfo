<div>
	<!-- Slider -->
	<div
		data-hs-carousel='{
			"loadingClasses": "opacity-0",
			"dotsItemClasses": "hs-carousel-active:bg-blue-700 hs-carousel-active:border-blue-700 size-3 border border-gray-400 rounded-full cursor-pointer",
			"isAutoPlay": true
		}'
		class="relative">

		<div class="hs-carousel relative overflow-hidden w-full h-[29rem] lg:h-[40rem] bg-white rounded-lg">
			<div
				class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700 opacity-0">

				<!-- Slide 1 -->
				<div class="hs-carousel-slide relative w-full">
					<div class="absolute inset-0 bg-cover bg-center"
						style="background-image: url({{ asset('img/beranda-carousel-1.jpg') }});"></div>
					<div class="absolute inset-0 bg-gray-900/60 lg:bg-black/60"></div> <!-- Overlay hitam -->
				</div>

				<!-- Slide 2 -->
				<div class="hs-carousel-slide relative w-full">
					<div class="absolute inset-0 bg-cover bg-center"
						style="background-image: url({{ asset('img/beranda-carousel-2.jpg') }});"></div>
					<div class="absolute inset-0 bg-gray-900/60 lg:bg-black/60"></div>
				</div>

				<!-- Slide 3 -->
				<div class="hs-carousel-slide relative w-full">
					<div class="absolute inset-0 bg-cover bg-center"
						style="background-image: url({{ asset('img/beranda-carousel-3.jpg') }});"></div>
					<div class="absolute inset-0 bg-gray-900/60 lg:bg-black/60"></div>
				</div>

			</div>
		</div>

		<!-- Optional static content below carousel (if still needed) -->
		<div class="absolute container top-[7rem] lg:top-1/3 left-1/2 -translate-x-1/2">
			<p class="font-extrabold text-2xl lg:text-4xl leading-tight text-white/95">
				Dinas Komunikasi <span class="text-primary">Informatika</span><br>Kota
				<span class="text-primary">Kendari</span>
			</p>
			<p class="mt-5 mb-8 lg:mb-6 text-sm lg:text-base max-w-4xl text-white/90 leading-snug">
				Dinas Kominfo Kota Kendari berkomitmen untuk menyediakan layanan teknologi informasi yang inovatif, aman, dan
				terpercaya guna mendukung transformasi digital menuju Smart City.
			</p>
			<div class="flex flex-col lg:flex-row items-center gap-3">
				<a href="#layanan"
					class="w-full lg:w-fit py-2.5 px-5 text-center text-sm font-medium rounded-lg border border-transparent bg-primary text-white">
					Layanan
				</a>
				<a href="#layanan"
					class="w-full lg:w-fit py-2.5 px-5 text-center text-sm font-medium rounded-lg border border-transparent bg-secondary text-white">
					Tentang Kami
				</a>
			</div>
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
	<!-- End Slider -->

	<div class="grid grid-cols-1 mt-16 lg:mt-0 lg:-translate-y-[8rem] gap-y-16 lg:gap-y-20">
		{{-- Three Card --}}
		<div class="container">
			<div class="grid grid-cols-12 gap-x-0 lg:gap-x-6 gap-y-5 lg:gap-y-0">
				@for ($i = 0; $i < 3; $i++)
					<div class="col-span-12 lg:col-span-4">
						<div
							class="flex flex-col bg-primary shadow-2xs rounded-lg text-white">
							<div class="p-5 lg:p-6.5">
								<h6 class="text-sm">
									Layanan Cepat
								</h6>
								<h5 class="mt-2 text-lg font-bold">
									Layanan Darurat TI
								</h5>
								<p class="leading-snug mt-3 mb-4 text-sm">Layanan darurat untuk masalah teknologi informasi keamanan siber dan
									infrastruktur TI,</p>
								<a href="#" class="group inline-flex items-center space-x-2.5 text-sm">
									<span class="font-medium">LEARN MORE</span>
									<i class="group-hover:translate-x-0.5 transition fa-solid fa-arrow-right"></i>
								</a>
							</div>
						</div>
					</div>
				@endfor
			</div>
		</div>
		{{-- Three Card --}}

		{{-- Sambutan Kadis --}}
		<div class="container">
			<h1 class="text-center text-2xl lg:text-3xl font-bold leading-[28px] lg:leading-[33px] text-gray-800">
				Sambutan Kepala Diskominfo<br>Kota Kendari
			</h1>
			<p class="mx-auto text-center my-3">icon</p>
			<p class="text-gray-500 text-sm lg:text-base max-w-2xl text-center mx-auto">
				Selamat datang di website resmi Diskominfo Kota kendari. Kami berkomitmen mewujudkan Smart
				City yang maju,
				inovatif, dan berdaya saing.</p>

			<div class="mt-7 lg:mt-10 grid grid-cols-5 gap-x-0 lg:gap-x-9 gap-y-4.5 lg:gap-y-0">
				<div class="col-span-5 lg:col-span-3 order-2 lg:order-1">
					<h6 class="font-bold text-xl lg:text-2xl text-gray-800">Sahuriyanto Meronda, S.P</h6>
					<div class="mt-4 lg:mt-6 inline-flex flex-col gap-y-5 text-sm text-gray-500">
						<p>Sebagai institusi yang bertanggung jawab dalam bidang teknologi informasi dan
							komunikasi, kami berkomitmen untuk memberikan layanan terbaik guna mendukung
							transformasi digital dan mewujudkan Kota Kendari sebagai Smart City yang maju, inovatif,
							dan berdaya saing.
						</p>
						<p>Website ini hadir sebagai sarana informasi dan komunikasi antara pemerintah dan
							masyarakat. Kami mengajak seluruh masyarakat Kota Kendari untuk bersama-sama
							memanfaatkan teknologi informasi guna menciptakan kota yang lebih cerdas, terhubung,
							dan sejahtera.
						</p>
						Dengan semangat kolaborasi dan inovasi, mari kita wujudkan visi Kota Kendari sebagai
						pusat pertumbuhan ekonomi digital dan teknologi di wilayah Indonesia Timur.
					</div>
					<div class="mt-7 lg:mt-8 grid grid-cols-2">
						@foreach (['Smart City Terdepan', 'Transformasi Digital', 'Inovasi Teknologi', 'Layanan Publik Prima', 'Keamanan Siber', 'Literasi Digital'] as $item)
								<p class="text-gray-500 text-sm lg:text-base inline-flex items-center space-x-3">
									<i class="fa-solid fa-caret-right text-primary"></i>
									<span>{{ $item }}</span>
								</p>
						@endforeach
					</div>
				</div>
				<div class="col-span-5 lg:col-span-2 order-1 lg:order-2">
					<img class="aspect-3/4 object-cover object-center w-[90%] lg:w-full mx-auto"
						src="https://asset-2.tstatic.net/sultra/foto/bank/images/Kepala-Dinas-Pertanian-Kota-Kendari-Sahuriyanto-Meronda.jpg"
						alt="kadis">
				</div>
			</div>
		</div>
		{{-- Sambutan Kadis --}}

	</div>

</div>
