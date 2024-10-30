@extends('layouts.main')
@section('content')
<section class="section profile">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="{{ asset('dist/img/profile/user.png') }}" alt="Profile" class="rounded-circle">
                    <h2>{{ ucwords(auth()->user()->nama) }}</h2>
                    @if(auth()->user()->location->wilayah_id == 1 || auth()->user()->location->wilayah_id == 2)
                    <h3>{{ ucwords(auth()->user()->location->nama_lokasi) }}</h3>
                    @else
                    <h3>{{ ucwords(auth()->user()->location->wilayah->name) }}</h3>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pt-3">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered" id="myTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                        </li>
                    </ul>

                    <div class="tab-content pt-2 ps-2">
                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                            <h5 class="card-title">Profile Details</h5>
                            @if(auth()->user()->location->wilayah_id == 1 || auth()->user()->location->wilayah_id == 2)
                                @include('contents.profile.partials.office_overview')
                            @else
                                @include('contents.profile.partials.store_overview')
                            @endif
                        </div>

                        <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                            <!-- Profile Edit Form -->
                            <form class="row g-3 mt-2 mb-3" action="{{ route('profile.update') }}" onsubmit="return validateForm1()" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Photo</label>
                                    <div class="col-md-8 col-lg-9">
                                    <img src="{{ asset('dist/img/profile/user.png') }}" alt="Profile">
                                    <div class="pt-2">
                                        <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                                        <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                                    </div>
                                    </div>
                                </div>
                                <h5 class="card-title">Contact Details</h5>

                                <div class="row mb-3">
                                    <label for="telp" class="col-md-4 col-lg-3 col-form-label">Phone/Ext</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="telp" type="text" class="form-control" id="telp" value="{{ ucwords(auth()->user()->telp ) }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="ip_address" class="col-md-4 col-lg-3 col-form-label">IP Address</label>
                                    <div class="col-md-8 col-lg-9">
                                    <input name="ip_address" type="text" class="form-control" id="ip_address" value="{{ ucwords(auth()->user()->ip_address ) }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-save2 me-1"></i> Save</button>
                                    </div>
                                </div>
                            </form><!-- End Profile Edit Form -->
                        </div>

                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <!-- Change Password Form -->
                            <form class="row g-3 mt-2 mb-3" action="{{ route('change.password') }}" onsubmit="return validateForm2()" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="current_password" type="password" class="form-control" id="current_password" value="{{ old('current_password') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('current_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="new_password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="new_password" type="password" class="form-control" id="new_password" value="{{ old('new_password') }}" required>

                                        <!-- Showing notification error for input validation -->
                                        @error('new_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="new_password_confirmation" class="col-md-4 col-lg-3 col-form-label">Re-Enter New Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="new_password_confirmation" type="password" class="form-control" id="new_password_confirmation" value="{{ old('new_password_confirmation') }}" required>
                                        
                                        <!-- Showing notification error for input validation -->
                                        @error('new_password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <input type="text" name="updated_by" value="{{ auth()->user()->nama }}" hidden>

                                <div class="row mb-3">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-save2 me-1"></i> Save</button>
                                    </div>
                                </div>
                            </form><!-- End Change Password Form -->
                        </div>
                    </div><!-- End Bordered Tabs -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customScripts')
<script>
    function validateForm1() {
        var telp = document.getElementById('telp').value;
        var ipAddress = document.getElementById('ip_address').value;

        if (telp.length < 4) {
            alert('Ketik No. Telp/Ext minimal 4 karakter!');
            return false;
        }

        if (telp.length > 20) {
            alert('Ketik No. Telp/Ext maksimal 20 karakter!');
            return false;
        }

        if (ipAddress.length < 7) {
            alert('Ketik IP Address minimal 7 karakter!');
            return false;
        }

        if (ipAddress.length > 20) {
            alert('Ketik IP Address maksimal 15 karakter!');
            return false;
        }

        return true;
    }

    function validateForm2() {
        var newPassword = document.getElementById('new_password').value;
        var confirmPassword = document.getElementById('new_password_confirmation').value;

        if (newPassword.length < 5) {
            alert('Password baru minimal 5 karakter!');
            return false;
        }

        if (newPassword !== confirmPassword) {
            alert("Password baru tidak sesuai!");
            return false;
        }

        return true;
    }
</script>
@endsection