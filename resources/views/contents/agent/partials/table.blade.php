<div id="table-container">
    <table class="table datatable table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            <tr>
            <th scope="col">NIK</th>
            <th scope="col">NAMA AGENT</th>
            <th scope="col">PIC TICKET</th>
            <th scope="col">TOTAL TICKET</th>
            <th scope="col">TOTAL WAKTU KERJA</th>
            <th scope="col">RATA-RATA RESOLVED</th>
            <th scope="col">STATUS</th>
            <th scope="col">AKSI</th>
            </tr>
        </thead>
        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
            @foreach($data as $data)
            <tr>
            <td>{{ $data->nik }}</td>
            <td>{{ $data->nama_agent }}</td>
            <td>{{ $data->pic_ticket }}</td>
            <td>{{ $data->total_ticket }}</td>
            @php
                $workload = \Carbon\Carbon::parse($data->processed_time-$data->pending_time);
                $average = \Carbon\Carbon::parse($data->avg);
            @endphp
            @if( $data->processed_time-$data->pending_time >= 3600)
            <td>{{ $workload->hour }} Jam {{ $workload->minute }} Menit {{ $workload->second }} Detik</td>
            @elseif( $data->processed_time-$data->pending_time >= 60)
            <td>{{ $workload->minute }} Menit {{ $workload->second }} Detik</td>
            @else
            <td>{{ $workload->second }} Detik</td>
            @endif

            @if( $data->avg >= 3600)
            <td>{{ $average->hour }} Jam {{ $average->minute }} Menit {{ $average->second }} Detik</td>
            @elseif( $data->avg >= 60)
            <td>{{ $average->minute }} Menit {{ $average->second }} Detik</td>
            @elseif( $data->avg == 0)
            <td>0 Detik</td>
            @else
            <td>{{ $average->second }} Detik</td>
            @endif
            @if($data->status == "present")
            <td><span class="badge bg-primary">HADIR</span></td>
            @else
            <td><span class="badge bg-secondary">TIDAK HADIR</span></td>
            @endif
            <td>
            <label class="form-check form-switch">
                <input type="checkbox" class="form-check-input" data-id="{{ $data->id }}" {{ $data->status ? 'checked' : '' }}>
                <input type="text" id="location_id" value="{{ $data->location_id }}" hidden>
                <span class="slider round"></span>
            </label>
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('.form-check-input').change(function () {
            var id = $(this).data('id');
            var status = $(this).prop('checked') ? 1 : 0;

            $.ajax({
                url: '/agents-update' + id,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function (response) {
                    // Handle success, jika diperlukan
                    refreshTable();
                },
                error: function (xhr) {
                    // Handle error, jika diperlukan
                    console.error('Error updating status');
                }
            });
        });

        function refreshTable() {
            var id = document.getElementById('location_id').value;
            $.ajax({
                url: '/agents/refresh/' + id, 
                method: 'GET',
                success: function(response) {
                    // Memperbarui tabel dengan data terbaru
                    $('#table-container').html(response);
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
    });
</script>

<!-- Template Main JS File -->
<script src="{{ asset('dist/js/main.js') }}"></script>