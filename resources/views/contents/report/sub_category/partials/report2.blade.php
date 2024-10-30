<!-- Tabel Total Tiket -->
<div class="table-responsive">
    <table class="table datatable table-hover">
        <thead class="bg-light">
            @if($pathFilter[0] != NULL && $pathFilter[1] != NULL)
            <tr>
                <td colspan="{{ 3+$totalAgents }}" class="font-monospace ps-3">Filter : {{ ucwords($pathFilter[0]) }} | {{ $pathFilter[1] }}</td>
            </tr>
            @elseif($pathFilter[0] != NULL && $pathFilter[1] == NULL)
            <tr>
                <td colspan="{{ 3+$totalAgents }}" class="font-monospace ps-3">Filter : {{ ucwords($pathFilter[0]) }} | All Period</td>
            </tr>
            @elseif($pathFilter[0] == NULL && $pathFilter[1] != NULL)
            <tr>
                <td colspan="{{ 3+$totalAgents }}" class="font-monospace ps-3">Filter : All Category | {{ $pathFilter[1] }}</td>
            </tr>
            @endif
            <tr class="align-middle">
                <th class="text-center">#</th>
                <th style="width:500px">Sub Category</th>
                <th>Total Tickets</th>
                @foreach($agents as $agent)
                    <th>{{ ucwords($agent->nama_agent) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $nomorUrut = 1;
            @endphp
            @foreach($ticketCounts as $categoryName => $subCategories)
                @foreach($subCategories as $subCategoryName => $agentData)
                    <tr>
                        <td class="text-center">{{ $nomorUrut++ }}.</td>
                        <td>{{ ucwords($categoryName) }} - {{ $subCategoryName }}</td>
                        <td>{{ $agentData['totalTickets'] ?? 0 }}</td>
                        @foreach($agents as $agent)
                            @php
                                $ticketCount = $agentData[$agent->id] ?? 0;
                            @endphp
                            <td>{{ $ticketCount }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>