<table class="table table-bordered">
    <thead class="bg-light text-center" style="height: 45px;font-size:14px;">
        <tr class="align-middle">
        <th rowspan="2">NO</th>
        <th rowspan="2">NIK</th>
        <th rowspan="2">NAMA AGENT</th>
        <th rowspan="2">SUB DIVISI</th>
        <th colspan="3">STATUS TICKET</th>
        </tr>
        <tr>
        <th>PENDING</th>
        <th>ONPROCESS</th>
        <th>RESOLVED</th>
        </tr>
    </thead>
    <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
        @php
            $nomorUrut = 1;
        @endphp
        @foreach($data1 as $data)
        <tr>
        <td>{{ $nomorUrut++ }}</td>
        <td>{{ $data->nik }}</td>
        <td>{{ $data->nama_agent }}</td>
        <td>{{ $data->sub_divisi }}</td>
        <td class="text-end">{{ $data->ticket_pending }}</td>
        <td class="text-end">{{ $data->ticket_onprocess }}</td>
        <td class="text-end">{{ $data->ticket_finish }}</td>
        </tr>
        @endforeach
        <tr class="bg-light text-end">
            <th class="text-center" colspan="4">TOTAL</th>
            <th>{{ $total1[0] }}</th>
            <th>{{ $total1[1] }}</th>
            <th>{{ $total1[2] }}</th>
        </tr>
    </tbody>
</table>