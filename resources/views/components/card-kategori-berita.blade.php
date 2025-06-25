<div class="bg-white p-4 rounded-xl c2Shadow">
	@php
		$kategoriBerita = App\Models\KategoriBerita::pluck('nama', 'slug');
	@endphp

	<h4 class="font-semibold text-primary mb-3">Kategori</h4>
	<ul class="space-y-2 text-sm text-cText">
		<li class="font-semibold">
			@if (request()->routeIs('berita.show'))
				<a wire:navigate href="{{ route('berita.index') }}"
					class="hover:text-primary transition cursor-pointer">
					<i class="fas fa-tag mr-1 text-xs text-primary"></i> Semua Berita
				</a>
			@else
				<span wire:click="changeKategori('')"
					class="hover:text-primary transition cursor-pointer
            {{ $this->kategori == '' ? '!text-primary' : '' }}">
					<i class="fas fa-tag mr-1 text-xs text-primary"></i> Semua Berita
				</span>
			@endif
		</li>
		@foreach ($kategoriBerita as $slug => $nama)
			<li>
				@if (request()->routeIs('berita.show'))
					<a wire:navigate href="{{ route('berita.index', ['kategori' => $slug]) }}"
						class="hover:text-primary transition cursor-pointer">
						<i class="fas fa-tag mr-1 text-xs text-primary"></i> {{ $nama }}
					</a>
				@else
					<p wire:click="changeKategori('{{ $slug }}')"
						class="hover:text-primary transition cursor-pointer
									{{ $this->kategori == $slug ? '!text-primary font-semibold' : '' }}">
						<i class="fas fa-tag mr-1 text-xs text-primary"></i> {{ $nama }}
					</a>
				@endif
			</li>
		@endforeach
	</ul>
</div>
