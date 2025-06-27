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
						src="{{ asset($data->thumbnail ? "storage/$data->thumbnail" : 'img/berita.webp') }}"
						alt="Gambar Berita"
						class="w-full h-auto object-cover" />
				</div>

				<!-- Judul & Meta -->
				<div>
					<h1 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2 leading-tight">{{ $data->judul }}</h1>
					<div class="text-xs lg:text-sm flex flex-col lg:flex-row gap-1 justify-between text-gray-500">
						<div class="flex flex-wrap gap-4">
							<span>
								<i class="fas fa-calendar-alt text-primary mr-1"></i>
								{{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}
							</span>
							<span>
								<i class="fas fa-user text-primary mr-1"></i> Admin Kominfo
							</span>
							<span>
								<i class="fas fa-eye text-primary mr-1"></i> {{ number_format($data->total_lihat, 0, ',', '.') }} views
							</span>
						</div>
						<span>
							<i class="fas fa-tag text-primary mr-1"></i>
							{{ $data->kategori->nama ?? 'Tanpa Kategori' }}
						</span>
					</div>
				</div>

				<!-- Konten -->
				<div
					class="prose prose-sm lg:prose max-w-none prose-p:text-gray-800 prose-headings:text-gray-900 prose-li:marker:text-primary">
					{!! $data->konten !!}
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
					@php
						$beritaTerkait = App\Models\Berita::where('kategori_berita_id', $data->kategori_berita_id)
						    ->whereNot('id', $data->id)
						    ->latest()
						    ->limit(3)
						    ->get();
					@endphp

					<h3 class="text-xl font-semibold text-gray-800 mb-4">Berita Terkait</h3>
					<div class="grid md:grid-cols-3 gap-6">
						@forelse ($beritaTerkait as $item)
							<a wire:navigate href="{{ route('berita.show', $item->slug) }}"
								class="block bg-white rounded-xl overflow-hidden shadow c2Shadow cShadow	 transition">
								<img src="{{ asset($item->thumbnail ? "storage/$item->thumbnail" : 'img/berita.webp') }}"
									class="w-full h-48 object-cover"
									alt="Berita Terkait">
								<div class="p-4 space-y-2">
									<h4 class="text-gray-800 font-semibold text-sm leading-snug line-clamp-2">
										{{ $item->judul }}
									</h4>
									<div class="text-xs text-gray-500 flex items-center gap-1">
										<i class="fas fa-calendar-alt text-primary"></i>
										{{ Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}
									</div>
								</div>
							</a>
						@empty
							<x-empty-card message="Tidak ada berita terkait." />
						@endforelse
					</div>
				</div>
			</div>

			<!-- Sidebar Sticky -->
			<aside class="space-y-6 relative lg:sticky lg:top-28 h-fit">
				<!-- Kategori -->
				<x-card-kategori-berita />

				<!-- Berita Lainnya -->
				<x-card-berita-lainnya />
			</aside>
		</div>

	</x-layouts.page-berita>
</div>
