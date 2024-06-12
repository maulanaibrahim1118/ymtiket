<style>
    .table thead th {
        white-space: nowrap; /* Mencegah wrapping teks di header tabel */
    }
    .table tbody td {
        white-space: nowrap; /* Mencegah wrapping teks di header tabel */
    }
</style>
<div class="table-responsive">
    <table class="table datatable table-hover">
        <thead class="bg-light">
            @if($pathFilter[0] != NULL && $pathFilter[1] != NULL)
            <tr>
            <td colspan="{{ 3+$totalAgents }}" class="font-monospace ps-3">Filter : {{ ucwords($pathFilter[0]) }} | {{ $pathFilter[1] }}</td>
            </tr>
            @elseif($pathFilter[0] != NULL && $pathFilter[1] == NULL)
            <tr>
            <td colspan="{{ 3+$totalAgents }}" class="font-monospace ps-3">Filter : {{ ucwords($pathFilter[0]) }} | Semua Periode</td>
            </tr>
            @elseif($pathFilter[0] == NULL && $pathFilter[1] != NULL)
            <tr>
            <td colspan="{{ 3+$totalAgents }}" class="font-monospace ps-3">Filter : Semua Kategori | {{ $pathFilter[1] }}</td>
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
                        <td>{{ ucwords($categoryName) }} - {{ $subCategoryName }}</td>
                        {{-- <td>{{ $categoryName }}</td> --}}

                        @foreach($agents as $agent)
                            @php
                                $totalSeconds = round($agentData[$agent->id]);
                                $hours = floor($totalSeconds / 3600);
                                $minutes = floor(($totalSeconds % 3600) / 60);
                                $seconds = $totalSeconds % 60;
                            @endphp

                            @if($totalSeconds != 0)
                                <td class="text-end bg-info">
                                    {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                                </td>
                            @else
                                <td class="text-end">00:00:00</td>
                            @endif
                        @endforeach

                        @php
                            $totalSeconds = round($agentData['totalAverage']);
                            $hours = floor($totalSeconds / 3600);
                            $minutes = floor(($totalSeconds % 3600) / 60);
                            $seconds = $totalSeconds % 60;
                        @endphp
                        @if($totalSeconds != 0)
                            <td class="text-end bg-info">
                                {{ str_pad($hours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, "0", STR_PAD_LEFT) }}
                            </td>
                        @else
                            <td class="text-end">00:00:00</td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>