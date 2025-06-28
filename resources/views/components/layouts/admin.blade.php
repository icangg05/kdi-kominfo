<!DOCTYPE html>
<html lang="en">

<head>
	@include('components.partials-admin.head')
</head>

<body class="app sidebar-mini rtl">
	<!-- Navbar-->
	@include('components.partials-admin.header')
	<!-- Sidebar menu-->
	@include('components.partials-admin.sidebar')

	<main class="app-content">
		<x-partials-admin.breadcrumb
			:icon="$icon"
			:title="$title"
			:desc="$desc"
			:nav-menu="$navMenu" />

		{{ $slot }}
	</main>


	@if (session('success'))
		@push('scripts')
			<script>
				Toastify({
					text: "{{ session('success') }}",
					duration: 3000,
					close: false,
					style: {
						background: "#10B222",
					},
				}).showToast();
			</script>
		@endpush
	@endif

	@push('scripts')
		<script>
			$('#container-table').on('click', '#myTable tbody tr', function(e) {
				if ($(e.target).closest('td').is(':last-child')) return;
				$('html').animate({
					scrollTop: $('#form').offset().top - 90
				}, 150);
			})

			$('#container-table').on('click', '#sampleTable tbody tr', function(e) {
				if ($(e.target).closest('td').is(':last-child')) return;
				$('html').animate({
					scrollTop: $('#form').offset().top - 90
				}, 150);
			})
		</script>
	@endpush

	<!-- Essential javascripts for application to work-->
	@include('components.partials-admin.script')

</body>

</html>
