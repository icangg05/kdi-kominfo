<table class="table table-hover table-bordered" id="myTable">
	<thead>
		<tr>
			<th>#</th>
			<th>Nama Pegawai</th>
			<th>Tanggal Lahir</th>
			<th>NIP</th>
			<th>Jabatan</th>
			<th>Alamat</th>
			<th>Aksi</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data as $item)
			<tr style="cursor: default" data-id="{{ $item['id'] }}" data-foto="{{ $item['foto'] }}">
				<td>{{ $loop->iteration }}.</td>
				<td>{{ $item->nama }}</td>
				<td>{{ $item->tanggal_lahir ? Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
				</td>
				<td>{{ $item->nip ?? '-' }}</td>
				<td>{{ $item->jabatan->nama }}</td>
				<td>{{ $item->alamat ?? '-' }}</td>
				<td class="btn-group">
					<form class="form-delete">
						@csrf
						@method('delete')
						<input type="hidden" name="dataId" value="{{ $item['id'] }}">
						<button type="submit" class="btn btn-sm btn-danger">
							<i class="bi bi-trash fs-5"></i>
						</button>
					</form>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
