<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Agent;
use App\Ticket;
use App\Location;
use App\Position;
use App\Sub_division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereHas('location', function ($query) {
                $query->whereIn('wilayah_id', [1, 2]);
            })
            ->get();
        
        return view('contents.user.index', [
            "title" => "User List",
            "path"  => "User",
            "path2" => "User",
            "users" => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $positions = Position::whereNotIn('nama_jabatan', ['Kepala Toko'])->orderBy('nama_jabatan', 'ASC')->get();
        $locations = Location::whereIn('wilayah_id', [1, 2])->orderBy('nama_lokasi', 'ASC')->get();

        return view('contents.user.create', [
            "title"     => "Create User",
            "path"      => "User",
            "path2"     => "Tambah",
            "positions" => $positions,
            "locations" => $locations,
            "roles"     => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validating data request
        $validatedData = $request->validate([
            'nik'               => 'required|min:5|max:8|unique:users',
            'nama'              => 'required|min:2|max:40',
            'password'          => 'required|min:5|max:191',
            'position_id'       => 'required',
            'location_id'       => 'required',
            'sub_division'      => 'required',
            'telp'              => 'required|min:4|max:15',
            'ip_1'              => 'required',
            'ip_2'              => 'required',
            'ip_3'              => 'required',
            'ip_4'              => 'required',
            'role'              => 'required',
            'updated_by'        => 'required'
        ],
        // Create custom notification for the validation request
        [
            'nik.required'              => 'NIK harus diisi!',
            'nik.min'                   => 'Ketik minimal 5 digit!',
            'nik.max'                   => 'Ketik maksimal 8 digit!',
            'unique'                    => 'NIK sudah ada!',
            'nama.required'             => 'Nama Pengguna harus diisi!',
            'nama.min'                  => 'Ketik minimal 2 digit!',
            'nama.max'                  => 'Ketik maksimal 40 digit!',
            'password.required'         => 'Password harus diisi!',
            'password.min'              => 'Ketik minimal 5 digit!',
            'password.max'              => 'Ketik maksimal 191 digit!',
            'position_id.required'      => 'Jabatan harus dipilih!',
            'location_id.required'      => 'Lokasi harus dipilih!',
            'sub_division.required'     => 'Sub Divisi harus dipilih!',
            'telp.required'             => 'No. Telp/Ext harus diisi!',
            'telp.min'                  => 'Ketik minimal 4 digit!',
            'telp.max'                  => 'Ketik maksimal 15 digit!',
            'role.required'             => 'Role harus dipilih!',
        ]);

        $data   = $request->all();
        $role   = $data['role'];
        $ip1    = $data['ip_1'];
        $ip2    = $data['ip_2'];
        $ip3    = $data['ip_3'];
        $ip4    = $data['ip_4'];
        $ip_address = $ip1.'.'.$ip2.'.'.$ip3.'.'.$ip4;

        $subDivisionName = strtolower($data['sub_division']);
        
        if($subDivisionName == "tidak ada"){
            if ($role == 3) {
                $codeAccess = "tidak ada";
            } else {
                $codeAccess = "all";
            }
        }else{
            $subDivisi = Sub_division::where('name', $subDivisionName)->first();
            $codeAccess = $subDivisi->code_access;
        }
        
        // Saving data to user table
        $user                 = new User;
        $user->nik            = $data['nik'];
        $user->nama           = $data['nama'];
        $user->password       = Hash::make($data['password']);
        $user->position_id    = $data['position_id'];
        $user->location_id    = $data['location_id'];
        $user->telp           = $data['telp'];
        $user->ip_address     = $ip_address;
        $user->sub_divisi     = $subDivisionName;
        $user->code_access    = $codeAccess;
        $user->role_id        = $data['role'];
        $user->updated_by     = $data['updated_by'];
        $user->save();
        
        if($role != 3){
            $positionId = $data['position_id'];
            $isActive = "1";

            if($role == 1){ // Role Service Desk
                $picTicket  = "all";
            }elseif($role == 2){ // Role Agent
                $picTicket  = $codeAccess;
            }

            if($positionId == 2 || $positionId == 7){
                $isActive = "0";
            }

            $agent                  = new Agent;
            $agent->nik             = $data['nik'];
            $agent->nama_agent      = $data['nama'];
            $agent->location_id     = $data['location_id'];
            $agent->sub_divisi      = $subDivisionName;
            $agent->pic_ticket      = $picTicket;
            $agent->status          = 'present';
            $agent->is_active       = $isActive;
            $agent->updated_by      = $data['updated_by'];
            $agent->save();
        }

        // Redirect to the user view if create data succeded
        $nama = $request['nama'];
        return redirect('/users')->with('success', ucwords($nama).' telah ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // Get id User dari request parameter
        $id = decrypt($request['id']);

        // Get data User berdasarkan id User
        $user = User::where('id', $id)->first();

        $roles = Role::all();
        $positions = Position::whereNotIn('nama_jabatan', ['Kepala Toko'])->orderBy('nama_jabatan', 'ASC')->get();
        $locations = Location::whereIn('wilayah_id', [1, 2])->orderBy('nama_lokasi', 'ASC')->get();
        $subDivisions = Sub_division::where('location_id', $user->location_id)->get();

        return view('contents.user.edit', [
            "title"             => "Edit User",
            "path"              => "User",
            "path2"             => "Edit",
            "subDivisions"      => $subDivisions,
            "positions"         => $positions,
            "locations"         => $locations,
            "roles"             => $roles,
            "user"              => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);

                
        // Get data User berdasarkan id User
        $user = User::where('id', $id)->first();

        // Validating data request
        $rules = [
            'nama'              => 'required',
            'password'          => 'required',
            'role'              => 'required',
            'position_id'       => 'required',
            'location_id'       => 'required',
            'sub_division'      => 'required',
            'telp'              => 'required|min:4|max:20',
            'ip_address'        => 'required|min:7|max:15',
            'updated_by'        => 'required'
        ];

        if($request->nik != $user->nik){
            $rules['nik'] = 'required|min:5|max:8|unique:users';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'nik.required'              => 'NIK harus diisi!',
            'nik.min'                   => 'Ketik minimal 5 digit!',
            'nik.max'                   => 'Ketik maksimal 8 digit!',
            'unique'                    => 'NIK sudah ada!',
            'nama.required'             => 'Nama harus diisi!',
            'role.required'             => 'Role harus dipilih!',
            'position_id.required'      => 'Jabatan harus dipilih!',
            'location_id.required'      => 'Lokasi harus dipilih!',
            'sub_division.required'     => 'Sub Divisi harus dipilih!',
            'telp.required'             => 'No. Telp/Ext harus diisi!',
            'telp.min'                  => 'Ketik minimal 4 digit!',
            'telp.max'                  => 'Ketik maksimal 13 digit!',
            'ip_address.required'       => 'IP Address harus diisi!',
            'ip_address.min'            => 'Ketik minimal 7 digit!',
            'ip_address.max'            => 'Ketik maksimal 15 digit!',
            'updated_by.required'       => 'Wajib diisi!'
        ]);

        $data   = $request->all();

        $subDivisionName = strtolower($data['sub_division']);
        
        if($subDivisionName == "tidak ada"){
            $codeAccess = "tidak ada";
        }else{
            $subDivisi = Sub_division::where('name', $subDivisionName)->first();
            $codeAccess = $subDivisi->code_access;
        }

        $role = $data['role'];

        if($role == 1){ // Role Service Desk
            $picTicket  = "all";
        }elseif($role == 2){ // Role Agent
            $picTicket  = $codeAccess;
        }
        
        if($user->role_id == 3){
            // Perubahan dari Client ke Agent. Create data agent / service desk baru.
            if($role != 3){ 
                $agent                  = new Agent;
                $agent->nik             = $data['nik'];
                $agent->nama_agent      = $data['nama'];
                $agent->location_id     = $data['location_id'];
                $agent->sub_divisi      = $subDivisionName;
                $agent->pic_ticket      = $picTicket;
                $agent->status          = 'present';
                $agent->updated_by      = $data['updated_by'];
                $agent->save();
            }
        }else{
            // Jika role sebelumnya = agent/service desk, kemudian di edit dan role nya masih sama
            if($role != 3){
                // Updating data to agent table
                Agent::where('nik', $user->nik)->update([
                    'nik'           => $data['nik'],
                    'nama_agent'    => $data['nama'],
                    'location_id'   => $data['location_id'],
                    'sub_divisi'    => $subDivisionName,
                    'pic_ticket'    => $picTicket,
                    'updated_by'    => $data['updated_by']
                ]);
            }else{ 
                // Perubahan dari Agent ke Client. Non aktifkan agent / service desk.
                Agent::where('nik', $user->nik)->update([
                    'is_active'     => '0',
                    'updated_by'    => $data['updated_by']
                ]);
            }
        }

        // Updating data to user table
        User::where('id', $user->id)->update([
            'nik'               => $data['nik'],
            'nama'              => $data['nama'],
            'role_id'           => $data['role'],
            'position_id'       => $data['position_id'],
            'location_id'       => $data['location_id'],
            'sub_divisi'        => $subDivisionName,
            'code_access'       => $codeAccess,
            'telp'              => $data['telp'],
            'ip_address'        => $data['ip_address'],
            'updated_by'        => $data['updated_by']
        ]);

        return redirect('/users')->with('success', 'Data User telah diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function switch(Request $request)
    {
        // Get id User dari request parameter
        $id = decrypt($request['id']);
        
        $user       = User::where('id', $id)->first();
        $nama       = $user->nama;
        $nik        = $user->nik;
        $role       = $user->role_id;
        $isActive   = $user->is_active;

        if ($isActive == '0') {
            $switch = '1';
            $status = 'aktifkan';
        } else {
            $switch = '0';
            $status = 'non aktifkan';
        }

        User::where('id', $id)->update([
            'is_active' => $switch,
            'updated_by' => $request['updated_by']
        ]);

        if ($role != 3) {
            Agent::where('nik', $nik)->update([
                'is_active' => $switch,
                'updated_by' => $request['updated_by']
            ]);
        }

        return back()->with('success', 'User '.ucwords($nama).' berhasil di '.$status.'!');
    }

    // Get data asset untuk JQuery Select Option
    public function getSubDivisions($id = 0)
    {
        $data = Sub_division::where('location_id', $id)->get();

        if ($data->isEmpty()) {
            return response()->json([
                'error' => 'No subdivisions found for the given location ID.',
            ], 404); // Menggunakan 404 Not Found untuk respons error
        }
        
        return response()->json($data);
    }

    public function profile()
    {
        return view('contents.profile.index', [
            "title" => "Profile",
            "path"  => "Profile",
            "path2" => "Profile"
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'telp'          => 'required|min:4|max:20',
            'ip_address'    => 'required|min:7|max:15',
        ]);

        $user->telp = $request->telp;
        $user->ip_address = $request->ip_address;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password lama tidak valid.');
        }

        $request->validate([
            'current_password'  => 'required',
            'new_password'      => 'required|min:5|confirmed',
        ],
        // Create custom notification for the validation request
        [
            'new_password.min'          => 'Ketik minimal 5 karakter!',
            'new_password.confirmed'    => 'Password baru tidak terkonfirmasi!',
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        Auth::logout();

        return redirect()->route('login.index')->with('success', 'Password telah diubah. Silakan login kembali.');
    }
}