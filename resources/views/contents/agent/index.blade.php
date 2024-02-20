@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">
                            <div class="filter">
                                <a class="icon" href="/clients"><i class="bx bx-revision"></i></a>
                            </div> <!-- End Filter -->

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-people me-2"></i>{{ $title }}</h5>
                                
                                <table class="table datatable" id="agentTable">
                                    <thead class="bg-light" style="height: 45px;font-size:14px;">
                                        <tr>
                                        <th scope="col">NIK</th>
                                        <th scope="col">NAMA AGENT</th>
                                        <th scope="col">DIVISI</th>
                                        <th scope="col">TOTAL TICKET</th>
                                        <th scope="col">TOTAL WAKTU KERJA</th>
                                        <th scope="col">RATA-RATA RESOLVED</th>
                                        <th scope="col">UPDATE TERAKHIR</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
                                        @foreach($data as $agent)
                                        <tr>
                                        <td>{{ $agent->nik }}</td>
                                        <td>{{ $agent->nama_agent }}</td>
                                        <td>{{ $agent->location->nama_lokasi }}</td>
                                        <td>{{ $agent->total_ticket }}</td>
                                        @php
                                            $workload = \Carbon\Carbon::parse($agent->processed_time-$agent->pending_time);
                                            $average = \Carbon\Carbon::parse($agent->avg);
                                        @endphp
                                        @if( $agent->processed_time-$agent->pending_time >= 3600)
                                        <td>{{ $workload->hour }} Jam {{ $workload->minute }} Menit {{ $workload->second }} Detik</td>
                                        @elseif( $agent->processed_time-$agent->pending_time >= 60)
                                        <td>{{ $workload->minute }} Menit {{ $workload->second }} Detik</td>
                                        @else
                                        <td>{{ $workload->second }} Detik</td>
                                        @endif
                                        @if( $agent->avg >= 3600)
                                        <td>{{ $average->hour }} Jam {{ $average->minute }} Menit {{ $average->second }} Detik</td>
                                        @elseif( $agent->avg >= 60)
                                        <td>{{ $average->minute }} Menit {{ $average->second }} Detik</td>
                                        @else
                                        <td>{{ $average->second }} Detik</td>
                                        @endif
                                        <td>{{ date('d-M-Y H:i:s', strtotime($agent->updated_at)) }}</td>
                                        @if($agent->status == "present")
                                        <td><span class="badge bg-primary">HADIR</span></td>
                                        @else
                                        <td><span class="badge bg-secondary">TIDAK HADIR</span></td>
                                        @endif
                                        <td>
                                        <label class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" data-id="{{ $agent->id }}" {{ $agent->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                        </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <script>
                                    $(document).ready(function () {
                                        function refreshTable() {
                                            $.ajax({
                                                url: '/agents-refresh', 
                                                method: 'GET',
                                                success: function(data) {
                                                    // Memperbarui tabel dengan data terbaru
                                                    $('#agentTable').html(data);
                                                },
                                                error: function(error) {
                                                    console.log(error);
                                                }
                                            });
                                        }
                                
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