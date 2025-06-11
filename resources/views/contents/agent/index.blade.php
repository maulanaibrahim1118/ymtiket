@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/agents"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-person-workspace me-2"></i>{{ $title }}</h5>
                                
                                <div id="table-container">
                                    <div class="table-responsive">
                                        <table class="table datatable table-hover">
                                            <thead class="bg-light" style="height: 45px;font-size:14px;">
                                                <tr>
                                                <th scope="col">EMPLOYEE NUMBER</th>
                                                <th scope="col">AGENT NAME</th>
                                                @can('isIT')
                                                <th scope="col">TICKET PIC</th>
                                                <th scope="col">SUB DIVISION</th>
                                                @endcan
                                                <th scope="col">DIVISION</th>
                                                <th scope="col">STATUS</th>
                                                @can('isActor')
                                                <th scope="col">SWITCH</th>
                                                @endcan
                                                </tr>
                                            </thead>
                                            <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                                @foreach($data as $data)
                                                @if($data->status != "present")
                                                <tr class="bg-light">
                                                @else
                                                <tr>
                                                @endif
                                                <td>{{ $data->nik }}</td>
                                                <td>{{ $data->nama_agent }}</td>
                                                @can('isIT')
                                                <td>{{ $data->pic_ticket }}</td>
                                                <td>{{ $data->sub_divisi }}</td>
                                                @endcan
                                                <td>{{ $data->location->nama_lokasi }}</td>
                                                @if($data->status == "present")
                                                <td><span class="badge bg-primary">HADIR</span></td>
                                                @else
                                                <td><span class="badge bg-secondary">TIDAK HADIR</span></td>
                                                @endif
                                                @can('isActor')
                                                <td>
                                                <label class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" data-id="{{ $data->id }}" {{ $data->status == 'present' ? 'checked' : '' }}>
                                                    <input type="text" id="location_id" value="{{ $data->location_id }}" hidden>
                                                    <span class="slider round"></span>
                                                </label>
                                                </td>
                                                @endcan
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection

@section('customScripts')
<script>
    // Update status agent ketika checkbox diubah
    $(document).on('change', '.form-check-input', function () {
        var id = $(this).data('id');
        var status = $(this).prop('checked') ? "present" : "absent";

        $.ajax({
            url: '/agents/update/' + id,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function (response) {
                refreshTable(); // Refresh tabel setelah update
            },
            error: function (xhr) {
                console.error('Error updating status');
            }
        });
    });

    // Fungsi untuk merefresh tabel
    function refreshTable() {
        var locationId = $('#location_id').val(); // Ambil ID lokasi dari input hidden

        $.ajax({
            url: '/agents/refresh/' + locationId,
            method: 'GET',
            success: function(response) {
                $('#table-container').html(response); // Ganti konten dengan tabel baru
            },
            error: function(error) {
                console.log('Error:', error);
            }
        });
    }
</script>
@endsection