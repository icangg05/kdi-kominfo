<header class="bg-primary/90 backdrop-blur-md backdrop-saturate-150 text-sm pt-3 pb-2.5 lg:pb-2 shadow">
	<nav class="container flex flex-wrap basis-full items-center justify-between">
		{{-- Logo --}}
		<a href="{{ route('beranda') }}" wire:navigate.hover>
			<img class="w-40" src="{{ asset('img/kominfo-logo.webp') }}" alt="logo">
		</a>
		{{-- Logo --}}

		{{-- Button Toggle & Search --}}
		<div class="sm:order-3 flex items-center gap-x-2">
			{{-- Toggle collap Nav --}}
			<button type="button"
				class="sm:hidden hs-collapse-toggle relative size-9 flex justify-center items-center gap-x-2 rounded-lg border border-white/10 text-white shadow-2xs disabled:opacity-50 disabled:pointer-events-none"
				id="hs-navbar-alignment-collapse" aria-expanded="false" aria-controls="hs-navbar-alignment"
				aria-label="Toggle navigation" data-hs-collapse="#hs-navbar-alignment">
				<svg class="hs-collapse-open:hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
					height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
					stroke-linejoin="round">
					<line x1="3" x2="21" y1="6" y2="6" />
					<line x1="3" x2="21" y1="12" y2="12" />
					<line x1="3" x2="21" y1="18" y2="18" />
				</svg>
				<svg class="hs-collapse-open:block hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
					height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
					stroke-linejoin="round">
					<path d="M18 6 6 18" />
					<path d="m6 6 12 12" />
				</svg>
				<span class="sr-only">Toggle</span>
			</button>
			{{-- Toggle collap Nav --}}

			{{-- Search Input --}}
			<div class="hidden lg:block relative">
				<input type="text"
					placeholder="Pencarian"
					class="bg-white/85 py-2.5 sm:py-3 px-4 ps-11 block w-full border-gray-300 rounded-lg sm:text-sm focus:z-10 focus:border-primary focus:ring-0 disabled:opacity-50 disabled:pointer-events-none">
				<div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
					<i class="fa-solid fa-magnifying-glass text-gray-400"></i>
				</div>
			</div>
			{{-- Search Input --}}
		</div>
		{{-- Button Toggle & Search --}}

		{{-- List Navigation --}}
		<div id="hs-navbar-alignment"
			class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow sm:grow-0 sm:basis-auto sm:block sm:order-2"
			aria-labelledby="hs-navbar-alignment-collapse">
			<div class="flex flex-col gap-5 mt-5 sm:flex-row sm:items-center sm:mt-0 sm:ps-5">
				<a wire:navigate.hover
					class="relative font-medium text-white hover:text-gray-400 focus:outline-hidden focus:text-gray-400
						{{ request()->routeIs('beranda') ? "lg:after:content-[''] lg:after:absolute lg:after:bottom-0 lg:after:left-1/2 lg:after:-translate-x-1/2 lg:after:w-[80%] lg:after:h-[2px] lg:after:bg-white/50 lg:hover:after:bg-white/30" : '' }}"
					href="{{ route('beranda') }}">Beranda</a>

				<div
					class="hs-dropdown [--strategy:static] sm:[--strategy:fixed] [--adaptive:none] sm:[--adaptive:adaptive]">
					<button id="hs-navbar-example-dropdown"
						type="button"
						class="text-white relative hs-dropdown-toggle flex items-center w-full hover:text-gray-400 focus:outline-hidden focus:text-gray-400 font-medium
						{{ request()->is('profil-dinas*') ? "lg:after:content-[''] lg:after:absolute lg:after:bottom-0 lg:after:left-1/2 lg:after:-translate-x-[64.5%] lg:after:w-[70%] lg:after:h-[2px] lg:after:bg-white/50 lg:hover:after:bg-white/30" : '' }}"
						aria-haspopup="menu" aria-expanded="false" aria-label="Mega Menu">
						Profil Dinas
						<svg class="hs-dropdown-open:-rotate-180 sm:hs-dropdown-open:rotate-0 duration-300 ms-1 shrink-0 size-4"
							xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="m6 9 6 6 6-6" />
						</svg>
					</button>

					<div
						class="mt-2 hs-dropdown-menu transition-[opacity,margin] ease-in-out duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 sm:w-48 z-10 bg-white sm:shadow-md rounded-lg p-1 space-y-1 before:absolute top-full sm:border border-gray-200 before:-top-5 before:start-0 before:w-full before:h-5 hidden"
						role="menu" aria-orientation="vertical" aria-labelledby="hs-navbar-example-dropdown">
						<a wire:navigate.hover
							class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
							href="{{ route('profil-dinas.tentang-kami') }}">
							Tentang Kami
						</a>
						<a wire:navigate.hover
							class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
							href="{{ route('profil-dinas.tupoksi') }}">
							Tupoksi
						</a>
						<a wire:navigate.hover
							class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
							href="{{ route('profil-dinas.profil-pimpinan') }}">
							Profil Pimpinan
						</a>
						<a wire:navigate.hover
							class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
							href="{{ route('profil-dinas.profil-pegawai') }}">
							Profil Pegawai
						</a>
						<a wire:navigate.hover
							class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
							href="{{ route('profil-dinas.struktur-organisasi') }}">
							Struktur Organisasi
						</a>
					</div>
				</div>

				<a wire:navigate.hover
					class="text-white relative font-mediumtext-white hover:text-gray-400 focus:outline-hidden focus:text-gray-400
					{{ request()->is('berita*') ? "text-primary lg:after:content-[''] lg:after:absolute lg:after:bottom-0 lg:after:left-1/2 lg:after:-translate-x-1/2 lg:after:w-[80%] lg:after:h-[2px] lg:after:bg-white/50 lg:hover:after:bg-white/30" : '' }}"
					href="{{ route('berita.index') }}">Berita</a>

				<a wire:navigate.hover
					class="relative font-medium text-white hover:text-gray-400 focus:outline-hidden focus:text-gray-400
					{{ request()->is('dokumen*') ? "text-primary lg:after:content-[''] lg:after:absolute lg:after:bottom-0 lg:after:left-1/2 lg:after:-translate-x-1/2 lg:after:w-[80%] lg:after:h-[2px] lg:after:bg-white/50 lg:hover:after:bg-white/30" : '' }}"
					href="{{ route('dokumen.index') }}">Dokumen</a>
				<div
					class="hs-dropdown [--strategy:static] sm:[--strategy:fixed] [--adaptive:none] sm:[--adaptive:adaptive]">
					<button id="hs-navbar-example-dropdown"
						type="button"
						class="relative hs-dropdown-toggle flex items-center w-full text-white hover:text-gray-400 focus:outline-hidden focus:text-gray-400 font-medium
						{{ request()->is('layanan*') ? "lg:after:content-[''] lg:after:absolute lg:after:bottom-0 lg:after:left-1/2 lg:after:-translate-x-[68%] lg:after:w-[70%] lg:after:h-[2px] lg:after:bg-white/50 lg:hover:after:bg-white/30" : '' }}"
						aria-haspopup="menu" aria-expanded="false" aria-label="Mega Menu">
						Layanan
						<svg class="hs-dropdown-open:-rotate-180 sm:hs-dropdown-open:rotate-0 duration-300 ms-1 shrink-0 size-4"
							xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="m6 9 6 6 6-6" />
						</svg>
					</button>

					<div
						class="mt-2 hs-dropdown-menu transition-[opacity,margin] ease-in-out duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 sm:w-48 z-10 bg-white sm:shadow-md rounded-lg p-1 space-y-1 before:absolute top-full sm:border border-gray-200 before:-top-5 before:start-0 before:w-full before:h-5 hidden"
						role="menu" aria-orientation="vertical" aria-labelledby="hs-navbar-example-dropdown">
						<a wire:navigate.hover
							class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
							href="{{ route('layanan', 'subdomain-hosting') }}">
							Subdomain & Hosting
						</a>
						{{-- <div class="hs-dropdown [--strategy:static] sm:[--strategy:absolute] [--adaptive:none] relative">
							<button id="hs-navbar-example-dropdown-sub" type="button"
								class="hs-dropdown-toggle w-full flex justify-between items-center text-sm text-gray-800 rounded-lg py-2 px-3 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100">
								Sub Menu
								<svg
									class="hs-dropdown-open:-rotate-180 sm:hs-dropdown-open:-rotate-90 sm:-rotate-90 duration-300 ms-2 shrink-0 size-4"
									xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="m6 9 6 6 6-6" />
								</svg>
							</button>

							<div
								class="hs-dropdown-menu transition-[opacity,margin] ease-in-out duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 sm:w-48 hidden z-10 sm:mt-2 bg-white sm:shadow-md rounded-lg before:absolute sm:border border-gray-200 before:-end-5 before:top-0 before:h-full before:w-5 sm:mx-2.5! top-0 end-full"
								role="menu" aria-orientation="vertical" aria-labelledby="hs-navbar-example-dropdown-sub">
								<div class="p-1 space-y-1">
									<a wire:navigate.hover
										class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
										href="#">
										About
									</a>
									<a wire:navigate.hover
										class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
										href="#">
										Downloads
									</a>
									<a wire:navigate.hover
										class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
										href="#">
										Team Account
									</a>
								</div>
							</div>
						</div> --}}
						<a wire:navigate.hover
							class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
							href="{{ route('layanan', 'email-dinas') }}">
							Pembuatan Email Dinas
						</a>
						<a wire:navigate.hover
							class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
							href="{{ route('layanan', 'pengajuan-tte') }}">
							Pengajuan TTE (Tanda Tangan Elektronik)
						</a>
						<a wire:navigate.hover
							class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
							href="{{ route('layanan', 'telpon-darurat-112') }}">
							Layanan Telpon Darurat 112
						</a>
					</div>
				</div>
				<a wire:navigate.hover
					class="relative font-medium text-white hover:text-gray-400 focus:outline-hidden focus:text-gray-400
						{{ request()->routeIs('galeri*') ? "lg:after:content-[''] lg:after:absolute lg:after:bottom-0 lg:after:left-1/2 lg:after:-translate-x-1/2 lg:after:w-[80%] lg:after:h-[2px] lg:after:bg-white/50 lg:hover:after:bg-white/30" : '' }}"
					href="{{ route('galeri.index') }}">Galeri</a>
			</div>
			{{-- List Navigation --}}
		</div>
	</nav>
</header>
