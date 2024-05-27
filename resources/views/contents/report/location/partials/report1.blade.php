<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            @if($pathFilter[0] != NULL && $pathFilter[1] != NULL)
            <tr>
            <td colspan="5" class="font-monospace ps-3">Filter : {{ ucwords($pathFilter[0]) }} | {{ $pathFilter[1] }}</td>
            </tr>
            @elseif($pathFilter[0] != NULL && $pathFilter[1] == NULL)
            <tr>
            <td colspan="5" class="font-monospace ps-3">Filter : {{ ucwords($pathFilter[0]) }} | Semua Periode</td>
            </tr>
            @elseif($pathFilter[0] == NULL && $pathFilter[1] != NULL)
            <tr>
            <td colspan="5" class="font-monospace ps-3">Filter : Semua Wilayah | {{ $pathFilter[1] }}</td>
            </tr>
            @endif
            <tr class="text-center align-middle">
            <th>NO</th>
            <th>NAMA DIVISI / CABANG</th>
            <th>PERMINTAAN</th>
            <th>KENDALA</th>
            <th>TOTAL TICKET</th>
            </tr>
        </thead>
        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
            @php
                $nomorUrut = 1;
            @endphp
            @foreach($locations as $location)
            <tr>
            <td class="text-center">{{ $nomorUrut++ }}.</td>
            @if( $location->wilayah_id != 1 && $location->wilayah_id != 2 )
            <td><a href="/error-404-underconstruction">{{ $location->site }} - {{ $location->nama_lokasi }}</a></td>
            @else
            <td><a href="/error-404-underconstruction">{{ $location->nama_lokasi }}</a></td>
            @endif
            <td class="text-end">{{ $location->permintaan }}</td>
            <td class="text-end">{{ $location->kendala }}</td>
            <td class="text-end">{{ $location->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>