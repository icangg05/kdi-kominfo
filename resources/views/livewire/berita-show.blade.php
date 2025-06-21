<div>
	<x-layouts.page-berita
		:breadcrumb="[['fas fa-newspaper', 'Berita', route('berita.index')], ['Lihat', '#']]"
		:title="'Berita Terkini'"
		:desc="'Satu portal untuk semua berita penting. Temukan informasi terkini dari Dinas Komunikasi dan Informatika Kota Kendari.'">

		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-6">
			<!-- Konten Utama -->
			<div class="lg:col-span-2 space-y-6">
				<!-- Gambar -->
				<div class="rounded-xl overflow-hidden shadow-md">
					<img
						src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/news-chanel-template-design-09dd867fae3163ba798dd4bc574a67c6_screen.jpg?ts=1635157235"
						alt="Gambar Berita"
						class="w-full h-auto object-cover" />
				</div>

				<!-- Judul & Meta -->
				<div>
					<h1 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2 leading-tight">Judul Berita Informatif dan Kekinian</h1>
					<div class="text-sm flex flex-wrap gap-4 text-gray-500">
						<span><i class="fas fa-calendar-alt text-primary mr-1"></i> 19 Juni 2025</span>
						<span><i class="fas fa-user text-primary mr-1"></i> Admin Kominfo</span>
						<span><i class="fas fa-eye text-primary mr-1"></i> 1.245 views</span>
					</div>
				</div>

				<!-- Konten -->
				<div class="prose prose-sm lg:prose max-w-none prose-p:text-gray-800 prose-headings:text-gray-900 prose-li:marker:text-primary">
					<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur, molestiae in eveniet reiciendis tempore sed
						dolores dolore facere rem hic, non maiores nihil laborum minus officiis, odit reprehenderit ea impedit. Excepturi
						pariatur molestiae consequatur, quam non odio quae voluptatem ratione!</p>
				</div>

				<!-- Share -->
				<div class="mt-8">
					<p class="text-sm font-semibold text-gray-600 mb-3">Bagikan berita ini:</p>
					<div class="flex flex-wrap gap-3">
						@php
							$shares = [
							    ['url' => '#', 'icon' => 'fab fa-facebook-f', 'label' => 'Facebook'],
							    ['url' => '#', 'icon' => 'fab fa-twitter', 'label' => 'Twitter'],
							    ['url' => '#', 'icon' => 'fab fa-whatsapp', 'label' => 'WhatsApp'],
							    ['url' => '#', 'icon' => 'fas fa-envelope', 'label' => 'Email'],
							];
						@endphp

						@foreach ($shares as $share)
							<a href="{{ $share['url'] }}"
								class="size-8 lg:size-10 flex items-center justify-center rounded-full bg-primary text-white hover:bg-primary/90 transition shadow-md"
								title="Bagikan ke {{ $share['label'] }}">
								<i class="{{ $share['icon'] }} text-sm lg:text-base"></i>
							</a>
						@endforeach
					</div>
				</div>

				<!-- Berita Terkait -->
				<div class="mt-12">
					<h3 class="text-xl font-semibold text-gray-800 mb-4">Berita Terkait</h3>
					<div class="grid md:grid-cols-3 gap-6">
						@for ($i = 1; $i <= 3; $i++)
							<a href="#" class="block bg-white rounded-xl overflow-hidden shadow c2Shadow cShadow	 transition">
								<img src="https://picsum.photos/400/250?random={{ $i }}" class="w-full h-48 object-cover"
									alt="Berita Terkait">
								<div class="p-4 space-y-2">
									<h4 class="text-gray-800 font-semibold text-sm leading-snug line-clamp-2">
										Judul Berita Terkait {{ $i }}
									</h4>
									<div class="text-xs text-gray-500 flex items-center gap-1">
										<i class="fas fa-calendar-alt text-primary"></i>
										{{ now()->subDays($i)->format('d M Y') }}
									</div>
								</div>
							</a>
						@endfor
					</div>
				</div>
			</div>

			<!-- Sidebar Sticky -->
			<aside class="space-y-6 relative lg:sticky lg:top-28 h-fit">
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
							<a wire:navigate href="{{ route('berita.show', $j) }}"
								class="flex items-start gap-3 hover:bg-gray-100 p-2 rounded-lg transition">
								<img src="https://picsum.photos/800/600?random={{ $j }}"
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
