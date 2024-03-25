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
                                                <th scope="col">NIK</th>
                                                <th scope="col">NAMA AGENT</th>
                                                @can('isIT')
                                                <th scope="col">PIC TICKET</th>
                                                <th scope="col">SUB DIVISI</th>
                                                @endcan
                                                <th scope="col">DIVISI</th>
                                                <th scope="col">STATUS</th>
                                                <th scope="col">SWITCH</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                                @foreach($data as $data)
                                                <tr>
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
                                </div>

                                <script>
                                    $(document).ready(function () {
                                        $('.form-check-input').change(function () {
                                            var id = $(this).data('id');
                                            var status = $(this).prop('checked') ? 1 : 0;
                                
                                            $.ajax({
                                                url: '/agents/update/' + id,
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
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>
@endsection