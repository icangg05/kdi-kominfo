<header class="app-header">
	<a class="app-header__logo" href="{{ route('dashboard.index') }}">Diskominfo</a>

	<!-- Sidebar toggle button -->
	<a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar" style="position: relative;">
		<i class="fas fa-bars" style="font-size: 1.1rem; position: absolute; left: 50%; transform: translate(-50%, -55%); top: 50%"></i>
	</a>

	<!-- Navbar Right Menu -->
	<ul class="app-nav">
		<li class="app-search">
			<input class="app-search__input" type="search" placeholder="Search">
			<button class="app-search__button"><i class="fas fa-magnifying-glass"></i></button>
		</li>

		<!-- User Menu -->
		<li class="dropdown">
			<a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">
				<i class="fas fa-user"></i>
			</a>
			<ul class="dropdown-menu settings-menu dropdown-menu-right">
				<li>
					<a class="dropdown-item" href="page-user.html">
						<i class="fas fa-gear"></i> Pengaturan
					</a>
				</li>
				<li>
					<a class="dropdown-item" href="page-user.html">
						<i class="fas fa-user"></i> Profil
					</a>
				</li>
				<li>
					<a class="dropdown-item" href="{{ route('logout') }}">
						<i class="fas fa-right-from-bracket"></i> Logout
					</a>
				</li>
			</ul>
		</li>
	</ul>
</header>
