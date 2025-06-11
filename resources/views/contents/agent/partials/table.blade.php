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
        <tbody id="data-agent" class="text-uppercase" style="height: 45px;font-size:13px;">
            @foreach($data as $data)
            <tr class="{{ $data->status != 'present' ? 'bg-light' : '' }}">
                <td>{{ $data->nik }}</td>
                <td>{{ $data->nama_agent }}</td>
                @can('isIT')
                <td>{{ $data->pic_ticket }}</td>
                <td>{{ $data->sub_divisi }}</td>
                @endcan
                <td>{{ $data->location->nama_lokasi }}</td>
                <td>
                    <span class="badge {{ $data->status == 'present' ? 'bg-primary' : 'bg-secondary' }}">
                        {{ $data->status == 'present' ? 'HADIR' : 'TIDAK HADIR' }}
                    </span>
                </td>
                @can('isActor')
                <td>
                    <label class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" data-id="{{ $data->id }}" 
                               {{ $data->status == 'present' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </td>
                @endcan
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Template Main JS File -->
<script src="{{ asset('dist/js/main.js') }}"></script>