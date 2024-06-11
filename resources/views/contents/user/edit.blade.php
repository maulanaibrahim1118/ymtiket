@extends('layouts.main')
@section('content')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card info-card">

                            <div class="card-body pb-0">
                                <h5 class="card-title border-bottom mb-3"><i class="bi bi-person-check me-2"></i>{{ $title }}</h5>
                                
                                <form class="row g-3 mb-3" action="{{ route('user.update', ['id' => encrypt($user->id)]) }}" method="POST">
                                    @method('put')
                                    @csrf
                                    <div class="col-md-2">
                                        <label for="nik" class="form-label">Username</label>
                                        <input type="text" name="nik" pattern="[0-9]+" class="form-control text-capitalize @error('nik') is-invalid @enderror" id="nik" value="{{ old('nik', $user->nik) }}" maxlength="8" title="Tolong di input dalam bentuk nomor." placeholder="No. Induk Karyawan...">
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('nik')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="nama" class="form-label">Full Name</label>
                                        <input type="text" name="nama" class="form-control text-capitalize @error('nama') is-invalid @enderror" id="nama" value="{{ old('nama', $user->nama) }}">

                                        <!-- Showing notification error for input validation -->
                                        @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2" hidden>
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control text-capitalize @error('password') is-invalid @enderror" id="password" value="{{ old('password', $user->password) }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-select @error('role') is-invalid @enderror" name="role" id="role">
                                            <option selected disabled>Choose...</option>
                                            @foreach($roles as $role)
                                                @if(old('role', $user->role_id) == $role->id)
                                                <option selected value="{{ $role->id }}">{{ ucwords($role->role_name) }}</option>
                                                @else
                                                <option value="{{ $role->id }}">{{ ucwords($role->role_name) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('role')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-8">
                                    </div>

                                    <div class="col-md-12">
                                        <p class="border-bottom"></p>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="position_id" class="form-label">Position</label>
                                        <select class="form-select @error('position_id') is-invalid @enderror" name="position_id" id="position_id" value="{{ old('position_id') }}">
                                            <option selected disabled>Choose...</option>
                                            @foreach($positions as $position)
                                                @if(old('position_id', $user->position_id) == $position->id)
                                                <option selected value="{{ $position->id }}">{{ ucwords($position->nama_jabatan) }}</option>
                                                @else
                                                <option value="{{ $position->id }}">{{ ucwords($position->nama_jabatan) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('position_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="location_id" class="form-label">Division</label>
                                        <select class="form-select @error('location_id') is-invalid @enderror" name="location_id" id="location" value="{{ old('location_id') }}">
                                            <option selected disabled>Choose...</option>
                                            @foreach($locations as $location)
                                                @if(old('location_id', $user->location_id) == $location->id)
                                                <option selected value="{{ $location->id }}">{{ ucwords($location->nama_lokasi) }}</option>
                                                @else
                                                <option value="{{ $location->id }}">{{ ucwords($location->nama_lokasi) }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('location_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="sub_division" class="form-label">Sub Division / Area / Regional / Wilayah</label>
                                        <select class="form-select @error('sub_division_id') is-invalid @enderror" name="sub_division" id="sub_division">
                                            <option value="" disabled>Choose...</option>
                                            @if(old('sub_division', $user->sub_divisi) == "tidak ada")
                                            <option selected value="tidak ada">Tidak Ada</option>
                                            @else
                                                @foreach($subDivisions as $subDivision)
                                                @if(old('sub_division', ucwords($user->sub_divisi)) == $subDivision->name)
                                                <option selected value="{{ $subDivision->name }}">{{ $subDivision->name }}</option>
                                                @else
                                                <option value="{{ $subDivision->name }}">{{ $subDivision->name }}</option>
                                                @endif
                                                @endforeach
                                            @endif
                                        </select>

                                        <!-- Showing notification error for input validation -->
                                        @error('sub_division')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3"></div>

                                    <div class="col-md-12"></div>
                                    
                                    <div class="col-md-2">
                                        <label for="nama_client" class="form-label">Phone / Ext</label>
                                        <input type="text" name="telp" pattern="[0-9]+" class="form-control text-capitalize @error('telp') is-invalid @enderror" id="telp" value="{{ old('telp', $user->telp) }}" maxlength="15" title="Tolong di input dalam bentuk nomor." required>

                                        <!-- Showing notification error for input validation -->
                                        @error('telp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="ip_address" class="form-label">IP Address</label>
                                        <input type="text" name="ip_address" class="form-control text-capitalize @error('ip_address') is-invalid @enderror" id="ip_address" value="{{ old('ip_address', $user->ip_address) }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('ip_address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>

                                    <div class="col-md-12 pt-2">
                                        <p class="border-bottom mt-2 mb-0"></p>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary float-end ms-2"><i class="bi bi-save2 me-1"></i> Save</button>
                                        <button type="reset" class="btn btn-warning float-end ms-2"><i class="bi bi-trash me-1"></i> Reset</button>
                                        <a href="/users"><button type="button" class="btn btn-secondary float-start"><i class="bi bi-arrow-return-left me-1"></i> Back</button></a>
                                    </div>
                                </form><!-- End Input Form -->
                            </div><!-- End Card Body -->
                        </div><!-- End Info Card -->
                    </div><!-- End col-12 -->
                </div> <!-- End row -->
            </div> <!-- End col-lg-12 -->
        </div> <!-- End row -->
    </section>

    <script>
        $('#role').change(function() {
            var location = $('#location');
            location.val(''); // Set value to default (disabled) option
            location.prop('selectedIndex', 0); // Ensure the first option is selected
            location.change();
        });
        
        $('#position_id').change(function() {
            var location = $('#location');
            location.val(''); // Set value to default (disabled) option
            location.prop('selectedIndex', 0); // Ensure the first option is selected
            location.change();
        });

        $('#location').change(function() {
            var locationId = $(this).val();
            var subDivisiDropdown = $('#sub_division');
            var jabatan = $('#position_id').val();
            var role = $('#role').val();

            if (jabatan == 2 && role == 1 || jabatan == 7 && role == 1) {
                setEmpty();
            } else {
                var SubDivisi = '{{ route("getSubDivisions", ":id") }}';
                var url = SubDivisi.replace(':id', locationId);
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        subDivisiDropdown.empty();
                        subDivisiDropdown.append('<option selected value="" disabled>Choose...</option>');
                        $.each(response, function(key, value) {
                            subDivisiDropdown.append('<option class="text-capitalize" value="' + value.name + '">' + value.name + '</option>');
                        });
                        subDivisiDropdown.prop('disabled', false);
                    },
                    error: function(xhr) {
                        // Jika error, tambahkan nilai default
                        if (xhr.status === 404) {
                            setEmpty();
                        } else {
                            // Tangani error lainnya jika perlu
                            console.error('An error occurred:', xhr.statusText);
                        }
                    }
                });
            }

            function setEmpty() {
                subDivisiDropdown.empty();
                subDivisiDropdown.append('<option value="" disabled>Choose...</option>');
                subDivisiDropdown.append('<option selected value="tidak ada">Tidak Ada</option>');
                subDivisiDropdown.prop('disabled', false);
            }
        });
    </script>

    <script>
        function formValidation(){
            var telp = document.getElementById('telp').value;

            if (telp.length < 4) {
                alert('Type Phone / Ext at least 4 characters!');
                return false;
            }

            var lanjut      = confirm('Are you sure the data is correct?');

            if(lanjut){
                return true;
            }else{
                return false;
            }
        }
    </script>
@endsection