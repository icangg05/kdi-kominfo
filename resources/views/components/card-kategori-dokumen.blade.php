@php
	$kategoriDokumen = App\Models\KategoriDokumen::pluck('nama', 'slug');
@endphp

<div class="bg-white p-4 rounded-xl c2Shadow">
	<h4 class="font-semibold text-primary mb-3">Kategori Dokumen</h4>
	<ul class="space-y-2 text-sm text-cText">
		<li class="font-medium">
			<p wire:click="changeKategori('')"
				class="hover:text-primary transition cursor-pointer
            {{ $this->kategori == '' ? '!text-primary font-semibold' : '' }}">
				<i class="fas fa-folder mr-1 text-xs text-primary"></i> Semua Dokumen
			</p>
		</li>
		@foreach ($kategoriDokumen as $slug => $nama)
			<li>
				<p wire:click="changeKategori('{{ $slug }}')"
					class="hover:text-primary transition cursor-pointer
            {{ $this->kategori == $slug ? '!text-primary font-semibold' : '' }}">
					<i class="fas fa-folder mr-1 text-xs text-primary"></i> {{ $nama }}
				</p>
			</li>
		@endforeach
	</ul>
</div>
