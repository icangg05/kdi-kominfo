<div>
	<x-layouts.page-berita
		:breadcrumb="[['Berita', '#']]"
		:title="'Berita Terkini'"
		:desc="'Satu portal untuk semua berita penting. Temukan informasi terkini dari Dinas Komunikasi dan Informatika Kota Kendari'">

		@if ($search)
			<div class="block lg:hidden mb-10 bg-white/70 backdrop-blur-md rounded-xl p-4 c2Shadow">
				<p class="text-sm text-gray-500 mb-1 font-medium uppercase tracking-wide">Kata Kunci Pencarian</p>
				<div class="text-primary text-sm lg:text-base font-semibold italic">
					<i class="fas fa-magnifying-glass mr-1 text-primary/70"></i> {{ $search }}
				</div>
			</div>
		@endif

		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<!-- Bagian Kiri: List Berita -->
			<div class="lg:col-span-2 space-y-6">
				@forelse ($data as $i => $item)
					<a wire:navigate href="{{ route('berita.show', $item->slug) }}"
						class="block bg-white rounded-xl cShadow c2Shadow transition-shadow overflow-hidden">
						<div class="grid md:grid-cols-3 gap-4">
							<img
								src="{{ asset($item->thumbnail ? "storage/$item->thumbnail" : 'img/berita.webp') }}"
								alt="Berita"
								class="w-full h-52 col-span-2 lg:col-span-1 lg:h-full object-cover lg:rounded-l-xl">
							<div class="p-4 col-span-2">
								<h3 class="text-xl font-bold text-primary mb-1">
									{{ $item->judul }}
								</h3>
								<p class="text-cText text-sm leading-relaxed mb-3 line-clamp-2 lg:line-clamp-3">
									{{ strip_tags($item->konten) }}
								</p>
								<span class="text-xs text-gray-400">
									<i class="fas fa-calendar-alt mr-1"></i>
									{{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
								</span>
							</div>
						</div>
					</a>
				@empty
					<x-empty-card message="Tidak ada berita yang tersedia." />
				@endforelse

				<div>
					{{ $data->links() }}
				</div>
			</div>

			<!-- Bagian Kanan: Sidebar -->
			<aside class="space-y-8">
				<!-- Search -->
				<div class="bg-white p-4 rounded-xl c2Shadow">
					<h4 class="font-semibold text-primary mb-3">Cari Berita</h4>
					<div class="flex items-center gap-2">
						<input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berita..."
							class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-1 focus:ring-primary text-sm" />
						<button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90">
							<i class="fas fa-search"></i>
						</button>
					</div>
				</div>

				<!-- Kategori -->
				<x-card-kategori-berita />

				<!-- Berita Lainnya -->
				<x-card-berita-lainnya />
			</aside>
		</div>

	</x-layouts.page-berita>
</div>
