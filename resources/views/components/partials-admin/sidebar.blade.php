@php
$sidebar = [
    ['fas fa-house', 'Dashboard', route('admin.dashboard'), request()->routeIs('admin.dashboard')],

    [
        'fas fa-building-columns', // solid style karena tidak ada versi `far`
        'Profil Dinas',
        '#',
        request()->is('dashboard/profil-dinas*'),
        [
            ['far fa-circle', 'Sejarah', route('admin.profil-dinas', 'sejarah'), request()->route('jenis') === 'sejarah'],
            ['far fa-circle', 'Visi', route('admin.profil-dinas', 'visi'), request()->route('jenis') === 'visi'],
            ['far fa-circle', 'Misi', route('admin.profil-dinas', 'misi'), request()->route('jenis') === 'misi'],
            ['far fa-circle', 'Foto Diskominfo', route('admin.profil-dinas', 'foto-diskominfo'), request()->route('jenis') === 'foto-diskominfo'],
            ['far fa-circle', 'Tugas', route('admin.profil-dinas', 'tugas'), request()->route('jenis') === 'tugas'],
            ['far fa-circle', 'Fungsi', route('admin.profil-dinas', 'fungsi'), request()->route('jenis') === 'fungsi'],
            ['far fa-circle', 'Profil Pimpinan', route('admin.profil-dinas', 'profil-pimpinan'), request()->route('jenis') === 'profil-pimpinan'],
            ['far fa-circle', 'Pegawai', route('admin.profil-dinas', 'pegawai'), request()->route('jenis') === 'pegawai'],
            ['far fa-circle', 'Struktur Organisasi', route('admin.profil-dinas', 'struktur-organisasi'), request()->route('jenis') === 'struktur-organisasi'],
        ],
    ],

    ['fas fa-newspaper', 'Berita', route('admin.dashboard'), request()->is('dashboard/berita*')],
    ['fas fa-file-lines', 'Dokumen', route('admin.dashboard'), request()->is('dashboard/dokumen*')],
    ['fas fa-photo-film', 'Galeri Diskominfo', route('admin.dashboard'), request()->is('dashboard/galeri*')],

    [
        'fas fa-database',
        'Data Lainnya',
        '#',
        request()->is('dashboard/data-lainnya*'),
        [
            ['far fa-circle', 'Kategori Berita', route('admin.dashboard'), '#'],
            ['far fa-circle', 'Kategori Dokumen', route('admin.dashboard'), '#'],
            ['far fa-circle', 'Pengaturan', route('admin.dashboard'), '#'],
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
						<i class="app-menu__icon {{ $item[0] }}"></i>&nbsp;&nbsp;
						<span class="app-menu__label">{{ $item[1] }}</span>
					</a>
				</li>
			@else
				<li class="treeview {{ $item[3] ? 'is-expanded' : '' }}">
					<a class="app-menu__item {{ $item[3] ? 'active' : '' }}" href="#" data-toggle="treeview">
						<i class="app-menu__icon {{ $item[0] }}"></i>&nbsp;&nbsp;
						<span class="app-menu__label">{{ $item[1] }}</span>
						<i class="treeview-indicator fa fa-angle-right"></i>
					</a>

					<ul class="treeview-menu">
						@foreach ($item[4] as $subItem)
							<li class="ml-1">
								<a class="treeview-item {{ $subItem[3] ? 'active' : '' }}" href="{{ $subItem[2] }}">
									<i class="icon {{ $subItem[0] }}"></i>&nbsp;&nbsp;
									{{ $subItem[1] }}
								</a>
							</li>
						@endforeach
					</ul>
				</li>
			@endif
		@endforeach
	</ul>
	{{-- far fa-pie-chart --}}
</aside>
