<div class="table-responsive mt-2">
    <table class="table datatable table-hover">
        <thead class="bg-light" style="height: 45px;font-size:14px;">
            <tr>
                <th scope="col">SITE</th>
                <th scope="col">INITIAL</th>
                <th scope="col">STORE NAME</th>
                <th scope="col">PHONE</th>
                <th scope="col">IP ADDRESS</th>
                <th scope="col">WILAYAH</th>
                <th scope="col">REGIONAL</th>
                <th scope="col">AREA</th>
                <th scope="col">STATUS</th>
                @can('isServiceDesk')
                <th scope="col">ACTION</th>
                @endcan
            </tr>
        </thead>
        <tbody class="text-uppercase" style="height: 45px;font-size:13px;">
            @foreach($stores as $store)
            <tr>
                <td>{{ $store->site }}</td>
                <td>{{ $store->initial }}</td>
                <td>{{ $store->nama_lokasi }}</td>
                <td>{{ $store->user->telp }}</td>
                <td>{{ $store->user->ip_address }}</td>
                <td>{{ $store->wilayah->name }}</td>
                <td>{{ $store->wilayah->regional->name }}</td>
                <td>{{ $store->wilayah->regional->area->name }}</td>
                @if($store->is_active == '1')
                <td><span class="badge bg-primary">Active</span></td>
                @else
                <td><span class="badge bg-secondary">Closed</span></td>
                @endif
                @can('isServiceDesk')
                <td class="dropdown">
                    <a class="action-icon pe-2" style="font-size:16px;" href="#" data-bs-toggle="dropdown"><i class="bi bi-list"></i></a>
                    <ul class="dropdown-menu">
                        @if($store->is_active == 1)
                            {{-- Tombol Edit --}}
                            <li><a class="dropdown-item text-capitalize text-warning" href="{{ route('location.edit', ['id' => encrypt($store->id)]) }}"><i class="bi bi-pencil-square text-warning"></i>
                                Edit</a>
                            </li>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('location.close', ['id' => encrypt($store->id)]) }}" onsubmit="return confirmAction()" method="POST">
                            @method('put')
                            @csrf
                            <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                            <li><button type="submit" class="dropdown-item text-capitalize text-danger"><i class="bi bi-x-circle text-danger"></i>Close</button></li>
                            </form>
                        @else
                            {{-- Tombol Aktifkan --}}
                            {{-- Tombol Hapus --}}
                            <form action="{{ route('location.activate', ['id' => encrypt($store->id)]) }}" method="POST">
                                @method('put')
                                @csrf
                                <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>
                                <li><button type="submit" class="dropdown-item text-capitalize text-primary"><i class="bi bi-x-circle text-primary"></i>Reactivate</button></li>
                            </form>
                        @endif
                    </ul>
                </td>
                @endcan
            </tr>
            @endforeach
        </tbody>
    </table>
</div>