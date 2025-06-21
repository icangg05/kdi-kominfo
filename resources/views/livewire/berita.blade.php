<div>
	<x-layouts.page-berita
		:breadcrumb="[['Berita', '#']]"
		:title="'Berita Terkini'"
		:desc="'Satu portal untuk semua berita penting. Temukan informasi terkini dari Dinas Komunikasi dan Informatika Kota Kendari'">

		<div class="mb-10 bg-white/70 backdrop-blur-md rounded-xl p-4 c2Shadow">
			<p class="text-sm text-gray-500 mb-1 font-medium uppercase tracking-wide">Kata Kunci Pencarian</p>
			<div class="text-primary text-sm lg:text-base font-semibold italic">
				<i class="fas fa-magnifying-glass mr-1 text-primary/70"></i> kominfo kendari
			</div>
		</div>

		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<!-- Bagian Kiri: List Berita -->
			<div class="lg:col-span-2 space-y-6">
				@for ($i = 0; $i < 5; $i++)
					<a wire:navigate href="{{ route('berita.show', $i) }}"
						class="block bg-white rounded-xl cShadow c2Shadow transition-shadow overflow-hidden">
						<div class="grid md:grid-cols-3 gap-4">
							<img
								src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/news-chanel-template-design-09dd867fae3163ba798dd4bc574a67c6_screen.jpg?ts=1635157235"
								alt="Berita"
								class="w-full h-52 col-span-2 lg:col-span-1 lg:h-full object-cover lg:rounded-l-xl">
							<div class="p-4 col-span-2">
								<h3 class="text-xl font-bold text-primary mb-1">
									Judul Berita Utama ke-{{ $i + 1 }}
								</h3>
								<p class="text-gray-600 text-sm leading-relaxed mb-3 line-clamp-3">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus luctus urna sed urna ultricies ac tempor dui
									sagittis...
								</p>
								<span class="text-xs text-gray-400">
									<i class="fas fa-calendar-alt mr-1"></i> {{ now()->subDays($i)->format('d M Y') }}
								</span>
							</div>
						</div>
					</a>
				@endfor
			</div>

			<!-- Bagian Kanan: Sidebar -->
			<aside class="space-y-8">
				<!-- Search -->
				<div class="bg-white p-4 rounded-xl c2Shadow">
					<h4 class="font-semibold text-primary mb-3">Cari Berita</h4>
					<div class="flex items-center gap-2">
						<input type="text" placeholder="Cari berita..."
							class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-1 focus:ring-primary text-sm" />
						<button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90">
							<i class="fas fa-search"></i>
						</button>
					</div>
				</div>

				<!-- Kategori -->
				<div class="bg-white p-4 rounded-xl c2Shadow">
					<h4 class="font-semibold text-primary mb-3">Kategori</h4>
					<ul class="space-y-2 text-sm text-gray-700">
						@foreach (['Teknologi', 'Pemerintahan', 'Kominfo', 'Digitalisasi', 'Umum'] as $kategori)
							<li>
								<a href="#" class="hover:text-primary transition">
									<i class="fas fa-tag mr-1 text-xs text-primary"></i> {{ $kategori }}
								</a>
							</li>
						@endforeach
					</ul>
				</div>

				<!-- Berita Lainnya -->
				<div class="bg-white p-4 rounded-xl c2Shadow">
					<h4 class="font-semibold text-primary mb-3">Berita Lainnya</h4>
					<div class="space-y-1">
						@for ($j = 6; $j < 9; $j++)
							<a wire:navigate href="{{ route('berita.show', $i) }}"
								class="flex items-start gap-3 hover:bg-gray-100 p-2 rounded-lg transition">
								<img src="https://picsum.photos/800/600?random={{ $i }}"
									class="w-16 h-16 rounded-lg object-cover" />
								<div>
									<h5 class="text-sm font-medium text-gray-800 leading-tight">
										Berita Tambahan ke-{{ $j }}
									</h5>
									<span class="text-xs text-gray-400">
										<i class="fas fa-calendar-alt mr-1"></i> {{ now()->subDays($j)->format('d M Y') }}
									</span>
								</div>
							</a>
						@endfor
					</div>
				</div>
			</aside>
		</div>

	</x-layouts.page-berita>
</div>
