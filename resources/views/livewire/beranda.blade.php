<div>
	<!-- Slider -->
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
						style="background-image: url({{ asset('img/beranda-carousel-1.jpg') }});"></div>
					<div class="absolute inset-0 bg-gray-700/60 lg:bg-black/60"></div> <!-- Overlay hitam -->
				</div>

				<!-- Slide 2 -->
				<div class="hs-carousel-slide relative w-full">
					<div class="absolute inset-0 bg-cover bg-center"
						style="background-image: url({{ asset('img/beranda-carousel-2.jpg') }});"></div>
					<div class="absolute inset-0 bg-gray-700/60 lg:bg-black/60"></div>
				</div>

				<!-- Slide 3 -->
				<div class="hs-carousel-slide relative w-full">
					<div class="absolute inset-0 bg-cover bg-center"
						style="background-image: url({{ asset('img/beranda-carousel-3.jpg') }});"></div>
					<div class="absolute inset-0 bg-gray-700/60 lg:bg-black/60"></div>
				</div>

			</div>
		</div>

		<!-- Center Content -->
		<div class="absolute container top-[7rem] lg:top-[15rem] left-1/2 -translate-x-1/2">
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

	{{-- Row --}}
	<div class="grid grid-cols-1 mt-16 lg:mt-0 lg:-translate-y-[10rem] gap-y-16 lg:gap-y-20">
		{{-- Three Card --}}
		<div class="container">
			<div class="grid grid-cols-12 gap-x-0 lg:gap-x-9 gap-y-5 lg:gap-y-0">
				@php
	$threeCards = [
		[
			'Layanan Cepat',
			'Layanan Darurat TI',
			'Layanan darurat untuk masalah teknologi informasi, keamanan siber, dan infrastruktur TI.',
			'#',
			'fa-solid fa-triangle-exclamation', // icon darurat
		],
		[
			'Edukasi Masyarakat',
			'Pelatihan Digital',
			'Daftar pelatihan dan workshop literasi digital untuk masyarakat, pelajar, dan pelaku usaha.',
			'#',
			'fa-solid fa-chalkboard-user', // icon pelatihan
		],
		[
			'Layanan Publik',
			'Pengaduan Masyarakat',
			'Layanan pengaduan untuk masalah teknologi informasi dan komunikasi yang dialami oleh masyarakat.',
			'#',
			'fa-solid fa-comments', // icon pengaduan
		],
	];
@endphp

@foreach ($threeCards as $item)
	<div class="col-span-12 lg:col-span-4">
		<div class="h-full relative flex flex-col bg-primary shadow-2xs rounded-lg text-white overflow-hidden">
			<div class="p-5 lg:p-6.5">
				<h6 class="text-[13px] font-extralight">{{ $item[0] }}</h6>
				<h5 class="text-lg font-bold">{{ $item[1] }}</h5>
				<p class="leading-snug font-light mt-3 mb-4 text-sm">{{ $item[2] }}</p>
				<a href="{{ $item[3] }}" class="group inline-flex items-center space-x-2.5 text-sm">
					<span class="font-medium font-sen">LEARN MORE</span>
					<i class="group-hover:translate-x-0.5 transition fa-solid fa-arrow-right"></i>
				</a>
			</div>
			<span class="absolute bottom-2 right-3 text-4xl lg:text-5xl text-white/15 pointer-events-none select-none">
				<i class="{{ $item[4] }}"></i>
			</span>
		</div>
	</div>
@endforeach

			</div>
		</div>
		{{-- Three Card --}}

		{{-- Sambutan Kadis --}}
		<div class="container">
			<x-section-title
				title="Sambutan Kepala Diskominfo<br>Kota Kendari"
				desc="Selamat datang di website resmi Diskominfo Kota kendari. Kami berkomitmen mewujudkan Smart City yang maju, inovatif, dan berdaya saing." />

			{{-- Left Content --}}
			<div class="grid grid-cols-5 gap-x-0 lg:gap-x-9 gap-y-4.5 lg:gap-y-0">
				<div class="col-span-5 lg:col-span-3 order-2 lg:order-1">
					<h6 class="font-bold text-xl lg:text-2xl text-gray-800">Sahuriyanto Meronda, S.P</h6>
					<div class="mt-4 lg:mt-6 inline-flex flex-col gap-y-5 text-sm lg:text-base text-gray-500">
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
							<p class="font-sen text-gray-500 text-sm lg:text-base inline-flex items-center space-x-3">
								<i class="fa-solid fa-caret-right text-primary"></i>
								<span>{{ $item }}</span>
							</p>
						@endforeach
					</div>
				</div>
				{{-- Left Content --}}

				{{-- Right Content --}}
				<div class="col-span-5 lg:col-span-2 order-1 lg:order-2">
					<img class="aspect-3/4 object-cover object-center w-[90%] lg:w-full mx-auto"
						src="https://asset-2.tstatic.net/sultra/foto/bank/images/Kepala-Dinas-Pertanian-Kota-Kendari-Sahuriyanto-Meronda.jpg"
						alt="kadis">
				</div>
				{{-- Right Content --}}
			</div>
		</div>
		{{-- Sambutan Kadis --}}

		{{-- Bidang Kominfo --}}
		<div class="container">
			<x-section-title
				title="Bidang Layanan"
				desc="Diskominfo Kota Kendari memiliki beberapa bidang yang bertanggung dalam mengelola layanan teknologi informasi dan komunikasi." />

			{{-- List Bidang Kominfo --}}
			@php
				$bidangLayanan = [
				    [
				        'fa-solid fa-building-columns',
				        'Sekretariat',
				        'Mengelola administrasi umum, keuangan, kepegawaian, dan koordinasi internal dinas secara efektif',
				    ],
				    [
				        'fa-solid fa-bullhorn',
				        'Bidang Informasi dan Komunikasi Publik',
				        'Mengelola informasi publik, hubungan media, serta strategi komunikasi pemerintah daerah secara efektif dan transparan',
				    ],
				    [
				        'fa-solid fa-network-wired',
				        'Bidang Penyelenggaraan e-Government',
				        'Mengembangkan dan menyelenggarakan sistem pemerintahan berbasis elektronik yang terintegrasi dan efisien',
				    ],
				    [
				        'fa-solid fa-shield-halved',
				        'Bidang Teknologi Informasi, Komunikasi, dan Persandian',
				        'Menyediakan infrastruktur TIK, menjaga keamanan sistem informasi, serta mengelola persandian dan komunikasi dinas',
				    ],
				];
			@endphp
			<div class="grid grid-cols-3 gap-x-0 gap-y-5 lg:gap-x-16 lg:gap-y-8">
				@foreach ($bidangLayanan as $index => $item)
					@php
						// Cek jika item terakhir dan total item tidak habis dibagi 3
						$isLast = $loop->last;
						$needsCenter = count($bidangLayanan) % 3 !== 0 && $loop->last;
					@endphp
					<div class="col-span-3 lg:col-span-1 text-center {{ $needsCenter ? 'lg:col-start-2' : '' }}">
						<div class="mx-auto size-16 lg:size-19 rounded-full border border-gray-300 flex items-center justify-center">
							<i class="{{ $item[0] }} text-2xl lg:text-4xl text-primary"></i>
						</div>
						<h6 class="mx-auto max-w-xs lg:w-full mt-5 mb-2.5 lg:mb-7 font-bold text-lg lg:text-xl text-gray-800 leading-tight">
							{{ $item[1] }}</h6>
						<p class="mx-auto max-w-[340px] lg:w-full text-sm lg:text-base leading-snug text-gray-500">{{ $item[2] }}</p>
					</div>
				@endforeach

			</div>
			{{-- List Bidang Kominfo --}}
		</div>
		{{-- Bidang Kominfo --}}
	</div>
	{{-- Row --}}

</div>
