<x-layouts.admin
	icon="fa fa-book"
	title="Pegawai"
	desc="Pegawai Diskominfo Kota Kendari"
	:nav-menu="[['Dashboard'], ['Pegawai', '#']]">

	<div class="row">
		<div class="col-md-8">
			<div class="tile">
				<form id="form" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="dataId" id="dataId">

					<h3 class="tile-title">Form Pegawai</h3>
					<div class="tile-body">

						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.input
										label="Nama Pegawai"
										placeholder="Tulis nama pegawai..."
										key="nama" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.input
										label="Nama NIP"
										placeholder="Tulis NIP pegawai..."
										key="nip" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.input
										label="Tanggal Lahir"
										placeholder="Tulis nama pegawai..."
										key="tanggal_lahir"
										type="date" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.select
										label="Jabatan"
										placeholder="Pilh..."
										key="jabatan_id"
										required />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.textarea
										label="Alamat"
										placeholder="Tulis alamat..."
										key="alamat" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<x-admin-textinput.input
										label="Foto"
										type="file"
										key="foto" />
								</div>
							</div>
						</div>
					</div>
					<div class="tile-footer">
						<button class="btn btn-primary" type="submit" id="btnSubmit">Tambah</button>&nbsp;&nbsp;&nbsp;
						<button class="btn btn-secondary" type="reset" id="btnReset">Reset</button>
					</div>
				</form>
			</div>
		</div>

		<div class="col-md-4">
			<div class="tile">
				<img id="previewFoto" style="aspect-ratio: 1/1;" class="img-thumbnail" src="{{ asset('img/user.svg') }}"
					alt="user.svg">
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-body table-responsive" id="container-table">
					@include('components.partials-admin.pegawai-table')
				</div>
			</div>
		</div>
	</div>


	@push('scripts')
		<script>
			// Setup klik table to paste
			function setupTableClick() {
				// Delegated event setelah refresh
				$('#container-table').on('click', '#myTable tbody tr', function(e) {
					if ($(e.target).closest('td').is(':last-child')) return;

					const $row = $(this);
					const dataId = $row.data('id');
					const nama = $row.find('td:nth-child(2)').text().trim();
					const tanggalLahir = $row.find('td:nth-child(3)').text().trim();
					const nip = $row.find('td:nth-child(4)').text().trim();
					const jabatan = $row.find('td:nth-child(5)').text().trim();
					const alamat = $row.find('td:nth-child(6)').text().trim();

					const jabatanOption = $(`#jabatan_id option`).filter(function() {
						return $(this).text().trim() == jabatan;
					});

					$('#dataId').val(dataId);
					$('#nama').val(nama);
					$('#nip').val(nip);
					if (tanggalLahir != '-')
						$('#tanggal_lahir').val(moment(tanggalLahir, 'D MMMM YYYY').format('YYYY-MM-DD'));
					else
						$('#tanggal_lahir').val('');
					$('#alamat').val(alamat);
					$('#jabatan_id').val(jabatanOption.val());

					$('#btnSubmit').text('Ubah');

					let fotoPath = $row.data('foto');

					if (fotoPath) {
						$('#previewFoto').attr('src', `/storage/${fotoPath}`);
					} else {
						$('#previewFoto').attr('src', `/img/user.svg`);
					}

					$('html').animate({
						scrollTop: $('#form').offset().top - 90
					}, 150);
					$('.err-message').text('');
				});
			}

			// Refresh table
			function refreshTable() {
				$.get('{{ route('dashboard.pegawai.refresh') }}', function(data) {
					$('#container-table').html(data); // inject table
					setupTableClick(); // rebind klik baris
					$('#myTable').DataTable(); // re-init datatable
				});
			}

			// Show alert
			function showToast(message) {
				Toastify({
					text: message,
					duration: 3000,
					close: false,
					style: {
						background: "#10B222",
					},
				}).showToast();
			}

			$(document).ready(function() {
				moment.locale('id');
				setupTableClick();

				// Handle add/update data
				$('#form').on('submit', function(e) {
					e.preventDefault();
					$('.err-message').text('');

					let formData = new FormData(this);

					let $btn = $('#btnSubmit');
					let oldText = $btn.html();
					$btn.html('<i class="fas fa-spinner fa-spin"></i> Proses...').prop('disabled', true);
					$('#btnReset').prop('disabled', true);


					$.ajax({
						url: '{{ route('dashboard.pegawai.save') }}',
						method: 'POST',
						data: formData,
						processData: false,
						contentType: false,
						success: function(res) {
							showToast(res.message);
							refreshTable();

							$('#form')[0].reset();
							$('#previewFoto').attr('src', `/img/user.svg`);
							$('.err-message').text('');
						},
						error: function(xhr) {
							if (xhr.status === 422) {
								let errors = xhr.responseJSON.errors;
								$.each(errors, function(field, messages) {
									$('#err-' + field).text(messages[0]);
								});
							}
						},
						complete: function() {
							$btn.html(oldText).prop('disabled', false);
							$('#btnReset').prop('disabled', false);
						}
					});
				});

				$(document).on('submit', '.form-delete', function(e) {
					e.preventDefault();

					if (!confirm('Yakin ingin menghapus data ini?')) return;

					const $form = $(this);

					$.ajax({
						url: '{{ route('dashboard.pegawai.delete') }}',
						method: 'POST',
						data: $form.serialize(),
						success: function(res) {
							showToast(res.message);
							refreshTable();
							resetForm();
						},
						error: function(xhr) {
							alert('Gagal menghapus data.');
						}
					});
				});

				function resetForm() {
					$('#btnSubmit').text('Tambah');
					$('#form')[0].reset();
					$('#previewFoto').attr('src', '{{ asset('img/user.svg') }}');
					$('.err-message').text('');
				}
				// Klik reset button
				$('#btnReset').on('click', function() {
					resetForm();
				});
			});
		</script>
	@endpush
</x-layouts.admin>
