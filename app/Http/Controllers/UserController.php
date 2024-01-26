<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Position;
use App\Location;
use App\Client;
use App\Agent;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users  = User::all();
        return view('contents.user.index', [
            "url"   => "",
            "title" => "User List",
            "path"  => "User",
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
        $roles  = ["client", "service desk", "agent"];

        return view('contents.user.create', [
            "url"       => "",
            "title"     => "Create User",
            "path"      => "User",
            "path2"     => "Tambah",
            "positions" => Position::orderBy('nama_jabatan', 'ASC')->get(),
            "locations" => Location::orderBy('nama_lokasi', 'ASC')->get(),
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
            'nik'           => 'required|min:8|max:8|unique:users',
            'nama'          => 'required|min:2|max:40',
            'password'      => 'required|min:5|max:191',
            'position_id'   => 'required',
            'location_id'   => 'required',
            'telp'          => 'required|min:4|max:15',
            'ip_1'          => 'required',
            'ip_2'          => 'required',
            'ip_3'          => 'required',
            'ip_4'          => 'required',
            'role'          => 'required',
            'updated_by'    => 'required'
        ],
        // Create custom notification for the validation request
        [
            'nik.required'          => 'NIK harus diisi!',
            'nik.min'               => 'Ketik minimal 8 digit!',
            'nik.max'               => 'Ketik maksimal 8 digit!',
            'unique'                => 'NIK sudah ada!',
            'nama.required'         => 'Nama Pengguna harus diisi!',
            'nama.min'              => 'Ketik minimal 2 digit!',
            'nama.max'              => 'Ketik maksimal 40 digit!',
            'password.required'     => 'Password harus diisi!',
            'password.min'          => 'Ketik minimal 5 digit!',
            'password.max'          => 'Ketik maksimal 191 digit!',
            'position_id.required'  => 'Jabatan harus dipilih!',
            'location_id.required'  => 'Lokasi harus dipilih!',
            'telp.required'         => 'No. Telp/Ext harus diisi!',
            'telp.min'              => 'Ketik minimal 4 digit!',
            'telp.max'              => 'Ketik maksimal 15 digit!',
            'role.required'         => 'Role harus dipilih!',
        ]);

        $data   = $request->all();
        $role   = $data['role'];
        $ip1    = $data['ip_1'];
        $ip2    = $data['ip_2'];
        $ip3    = $data['ip_3'];
        $ip4    = $data['ip_4'];
        $ip_address = $ip1.'.'.$ip2.'.'.$ip3.'.'.$ip4;

        // Saving data to user table
        $user                 = new User;
        $user->nik            = $data['nik'];
        $user->nama           = $data['nama'];
        $user->password       = Hash::make($data['password']);
        $user->position_id    = $data['position_id'];
        $user->location_id    = $data['location_id'];
        $user->telp           = $data['telp'];
        $user->ip_address     = $ip_address;
        $user->role           = $data['role'];
        $user->updated_by     = $data['updated_by'];
        $user->save();

        if($role == "client"){
            $client                 = new Client;
            $client->nik            = $data['nik'];
            $client->nama_client    = $data['nama'];
            $client->position_id    = $data['position_id'];
            $client->location_id    = $data['location_id'];
            $client->telp           = $data['telp'];
            $client->ip_address     = $ip_address;
            $client->updated_by     = $data['updated_by'];
            $client->save();
        }else{
            $agent                         = new Agent;
            $agent->nik                    = $data['nik'];
            $agent->nama_agent             = $data['nama'];
            $agent->location_id            = $data['location_id'];
            $agent->total_ticket           = 0;
            $agent->total_sub_ticket       = 0;
            $agent->total_resolved_time    = 0;
            $agent->rate                   = 0;
            $agent->status                 = 'idle';
            $agent->updated_by             = $data['updated_by'];
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
    public function edit(User $user)
    {
        $roles  = ["service desk", "agent"];

        return view('contents.user.edit', [
            "title"     => "Edit User",
            "path"      => "User",
            "path2"     => "Edit",
            "positions" => Position::orderBy('nama_jabatan', 'ASC')->get(),
            "locations" => Location::orderBy('nama_lokasi', 'ASC')->get(),
            "user"      => $user,
            "roles"     => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $nik    = $user->nik;
        $role   = $user->role;
        $data   = $request->all();

        // Validating data request
        $rules = [
            'nama'          => 'required',
            'password'      => 'required',
            'role'          => 'required',
            'position_id'   => 'required',
            'location_id'   => 'required',
            'telp'          => 'required|min:4|max:20',
            'ip_address'    => 'required|min:7|max:15',
            'updated_by'    => 'required'
        ];

        if($request->nik != $user->nik){
            $rules['nik'] = 'required|min:8|max:8|unique:user';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'nik.required'          => 'NIK harus diisi!',
            'nik.min'               => 'Ketik minimal 8 digit!',
            'nik.max'               => 'Ketik maksimal 8 digit!',
            'unique'                => 'NIK sudah ada!',
            'nama.required'         => 'Nama harus diisi!',
            'password.required'     => 'Password harus diisi!',
            'role.required'         => 'Role harus dipilih!',
            'position_id.required'  => 'Jabatan harus dipilih!',
            'location_id.required'  => 'Lokasi harus dipilih!',
            'telp.required'         => 'No. Telp/Ext harus diisi!',
            'telp.min'              => 'Ketik minimal 4 digit!',
            'telp.max'              => 'Ketik maksimal 13 digit!',
            'ip_address.required'   => 'IP Address harus diisi!',
            'ip_address.min'        => 'Ketik minimal 7 digit!',
            'ip_address.max'        => 'Ketik maksimal 15 digit!',
            'updated_by.required'   => 'Wajib diisi!'
        ]);

        // Updating data to user table
        User::where('id', $user->id)->update($validatedData);

        if($role == "client"){
            // Updating data to client table
            Client::where('nik', $nik)->update([
                'position_id'   => $data['position_id'],
                'location_id'   => $data['location_id'],
                'telp'          => $data['telp'],
                'ip_address'    => $data['ip_address'],
                'updated_by'    => $data['updated_by']
            ]);
        }else{
            // Updating data to agent table
            Agent::where('nik', $nik)->update([
                'location_id'   => $data['location_id'],
                'updated_by'    => $data['updated_by']
            ]);
        }

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
}
