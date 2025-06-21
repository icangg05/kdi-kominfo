<footer class="mt-18 lg:mt-24 relative bg-cover bg-center bg-no-repeat rounded-t-lg"
	style="background-image: url('{{ asset('img/bg-earth.jpg') }}');">
	<div class="absolute inset-0 bg-primary/70 backdrop-brightness-90 rounded-t-lg"></div>

	<div>
		<div class="container text-white/90 relative z-10 mx-auto px-4 pt-12 pb-10 grid grid-cols-1 md:grid-cols-3 gap-10">

			<!-- Logo & Deskripsi -->
			<div>
				<div class="mb-3 flex items-center gap-2 text-xl">
					<i class="fa-solid fa-network-wired"></i>
          <h1 class="font-bold">Diskominfo Kendari</h1>
				</div>
				<p class="text-sm leading-relaxed">
					Mendukung transformasi digital dan pelayanan publik berbasis teknologi informasi di Kota Kendari.
				</p>
			</div>

			<!-- Navigasi -->
			<div>
				<h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
					<i class="fa-solid fa-link"></i>
					Navigasi
				</h3>
				<ul class="space-y-2 text-sm">
					<li><a href="#" class="hover:text-white transition">Beranda</a></li>
					<li><a href="#" class="hover:text-white transition">Layanan</a></li>
					<li><a href="#" class="hover:text-white transition">Galeri</a></li>
					<li><a href="#" class="hover:text-white transition">Kontak</a></li>
				</ul>
			</div>

			<!-- Sosial Media -->
			<div>
				<h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
					<i class="fa-solid fa-share-nodes mr-2"></i>
					Ikuti Kami
				</h3>
				<div class="flex gap-4 text-xl">
					<a href="#" class="hover:text-white/70 transition"><i class="fa-brands fa-facebook-f"></i></a>
					<a href="#" class="hover:text-white/70 transition"><i class="fa-brands fa-instagram"></i></a>
					<a href="#" class="hover:text-white/70 transition"><i class="fa-brands fa-tiktok"></i></a>
					<a href="#" class="hover:text-white/70 transition"><i class="fa-brands fa-youtube"></i></a>
				</div>
			</div>
		</div>

		<!-- Footer Bottom -->
		<div class="relative z-10 px-3 lg:px-0 text-center text-white/90 text-sm border-t border-white/20 py-6">
			&copy; {{ date('Y') }} Dinas Komunikasi dan Informatika Kota Kendari. All rights reserved.
		</div>
	</div>
</footer>
