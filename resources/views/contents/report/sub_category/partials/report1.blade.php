<style>
    .table thead th {
        white-space: nowrap; /* Mencegah wrapping teks di header tabel */
    }
    .table tbody td {
        white-space: nowrap; /* Mencegah wrapping teks di header tabel */
    }
</style>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="bg-light">
            @if($pathFilter[0] != NULL && $pathFilter[1] != NULL)
            <tr>
            <td colspan="7" class="font-monospace ps-3">Kategori : {{ ucwords($pathFilter[0]) }} | Tanggal : {{ $pathFilter[1] }}</td>
            </tr>
            @elseif($pathFilter[0] != NULL && $pathFilter[1] == NULL)
            <tr>
            <td colspan="7" class="font-monospace ps-3">Kategori : {{ ucwords($pathFilter[0]) }}</td>
            </tr>
            @elseif($pathFilter[0] == NULL && $pathFilter[1] != NULL)
            <tr>
            <td colspan="7" class="font-monospace ps-3">Tanggal : {{ $pathFilter[1] }}</td>
            </tr>
            @endif
            <tr class="text-center align-middle">
                <th>No</th>
                <th style="width:500px">Sub Category</th>
                {{-- <th>Category</th> --}}
                @foreach($agents as $agent)
                    <th>{{ ucwords($agent->nama_agent) }}</th>
                @endforeach
                <th>Total Average</th>
            </tr>
        </thead>
        <tbody>
            @php
                $nomorUrut = 1;
            @endphp
            @foreach($data as $categoryName => $subCategories)
                @foreach($subCategories as $subCategoryName => $agentData)
                    <tr>
                        <td class="text-center">{{ $nomorUrut++ }}.</td>
                        <td>{{ $subCategoryName }}</td>
                        {{-- <td>{{ $categoryName }}</td> --}}

                        @foreach($agents as $agent)
                        @php
                            $avgAgent = \Carbon\Carbon::parse(round($agentData[$agent->id]));
                        @endphp

                        @if(round($agentData[$agent->id]) >= 3600)
                        <td class="text-end">{{ str_pad($avgAgent->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgAgent->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgAgent->second, 2, "0", STR_PAD_LEFT) }}</td>
                        @elseif(round($agentData[$agent->id]) >= 60)
                        <td class="text-end">{{ str_pad($avgAgent->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgAgent->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgAgent->second, 2, "0", STR_PAD_LEFT) }}</td>
                        @elseif(round($agentData[$agent->id]) == 0)
                        <td class="text-end">00:00:00</td>
                        @else
                        <td class="text-end">{{ str_pad($avgAgent->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgAgent->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgAgent->second, 2, "0", STR_PAD_LEFT) }}</td>
                        @endif
                        @endforeach

                        @php
                            $avgTotal = \Carbon\Carbon::parse(round($agentData['totalAverage']));
                        @endphp
                        @if(round($agentData['totalAverage']) >= 3600)
                        <td class="text-end">{{ str_pad($avgTotal->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal->second, 2, "0", STR_PAD_LEFT) }}</td>
                        @elseif(round($agentData['totalAverage']) >= 60)
                        <td class="text-end">{{ str_pad($avgTotal->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal->second, 2, "0", STR_PAD_LEFT) }}</td>
                        @elseif(round($agentData['totalAverage']) == 0)
                        <td class="text-end">00:00:00</td>
                        @else
                        <td class="text-end">{{ str_pad($avgTotal->hour, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal->minute, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($avgTotal->second, 2, "0", STR_PAD_LEFT) }}</td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>