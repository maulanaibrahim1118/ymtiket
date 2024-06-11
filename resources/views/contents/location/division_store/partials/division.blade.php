<div class="table-responsive mt-2">
    <table class="table datatable table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            <tr>
                <th scope="col">NAMA DIVISI</th>
                <th scope="col">LOKASI</th>
                <th scope="col">AKSI</th>
            </tr>
        </thead>
        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
            @foreach($divisions as $division)
            <tr>
                <td>{{ $division->nama_lokasi }}</td>
                <td>{{ $division->wilayah->name }}</td>
                <td class="text-capitalize"><a href="{{ route('location.edit', ['id' => encrypt($division->id)]) }}" class="text-primary"><i class="bi bi-pencil-square"></i> Edit</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>