@php
	$sidebar = [
	    ['fa fa-home', 'Dashboard', route('admin.dashboard'), request()->routeIs('admin.dashboard')],

	    [
	        'fa fa-building', // cocok untuk instansi/dinas
	        'Profil Dinas',
	        '#',
	        request()->is('dashboard/profil-dinas*'),
	        [
	            ['fa fa-circle-o', 'Sejarah', route('admin.dashboard')],
	            ['fa fa-circle-o', 'Visi', route('admin.dashboard')],
	            ['fa fa-circle-o', 'Misi', route('admin.dashboard')],
	            ['fa fa-circle-o', 'Foto Diskominfo', route('admin.dashboard')],
	            ['fa fa-circle-o', 'Tupoksi', route('admin.dashboard')],
	            ['fa fa-circle-o', 'Profil Pimpinan', route('admin.dashboard')],
	            ['fa fa-circle-o', 'Pegawai', route('admin.dashboard')],
	            ['fa fa-circle-o', 'Struktur Organisasi', route('admin.dashboard')],
	        ],
	    ],

	    ['fa fa-files-o', 'Berita', route('admin.dashboard'), request()->is('dashboard/berita*')],
	    ['fa fa-file-text-o', 'Dokumen', route('admin.dashboard'), request()->is('dashboard/dokumen*')],
	    ['fa fa-picture-o', 'Galeri Diskominfo', route('admin.dashboard'), request()->is('dashboard/galeri*')],

	    [
	        'fa fa-database',
	        'Data Lainnya',
	        '#',
	        request()->is('dashboard/data-lainnya*'),
	        [
	            ['fa fa-circle-o', 'Kategori Berita', route('admin.dashboard')],
	            ['fa fa-circle-o', 'Kategori Dokumen', route('admin.dashboard')],
	            ['fa fa-circle-o', 'Pengaturan', route('admin.dashboard')],
	        ],
	    ],
	];
@endphp

<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
	<div class="app-sidebar__user">
		<img class="app-sidebar__user-avatar" width="40" src="{{ asset('img/user.svg') }}" alt="user.png">
		<div>
			<p class="app-sidebar__user-name" style="font-size: 1rem">{{ auth()->user()->name }}</p>
			<p class="app-sidebar__user-designation" style="font-size: .7rem">{{ auth()->user()->email }}</p>
		</div>
	</div>
	<ul class="app-menu">
		@foreach ($sidebar as $item)
			@if (!isset($item[4]))
				<li>
					<a class="app-menu__item {{ $item[3] ? 'active' : '' }}" href="{{ $item[2] }}">
						<i class="app-menu__icon {{ $item[0] }}"></i>
						<span class="app-menu__label">{{ $item[1] }}</span>
					</a>
				</li>
			@else
				<li class="treeview">
					<a class="app-menu__item" href="#" data-toggle="treeview">
						<i class="app-menu__icon {{ $item[0] }}"></i>
						<span class="app-menu__label">{{ $item[1] }}</span>
						<i class="treeview-indicator fa fa-angle-right"></i>
					</a>

					<ul class="treeview-menu">
						@foreach ($item[4] as $subItem)
							<li>
								<a class="treeview-item" href="{{ $subItem[2] }}">
									<i class="icon {{ $subItem[0] }}"></i> {{ $subItem[1] }}
								</a>
							</li>
						@endforeach
					</ul>
				</li>
			@endif
		@endforeach
	</ul>
	{{-- fa fa-pie-chart --}}
</aside>
