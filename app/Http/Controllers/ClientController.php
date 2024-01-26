<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Position;
use App\Location;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients    = Client::all();
        return view('contents.client.index', [
            "url"       => "",
            "title"     => "Data Client",
            "path"      => "Client",
            "clients"   => $clients
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.client.create', [
            "url"       => "",
            "title"     => "Tambah Client",
            "path"      => "Client",
            "path2"     => "Tambah",
            "positions" => Position::orderBy('nama_jabatan', 'ASC')->get(),
            "locations" => Location::orderBy('nama_lokasi', 'ASC')->get()
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
            'nik'           => 'required|min:8|max:8|unique:clients',
            'nama_client'   => 'required|min:2|max:255',
            'position_id'   => 'required',
            'location_id'   => 'required',
            'telp'          => 'required|min:4|max:13',
            'ip_1'          => 'required',
            'ip_2'          => 'required',
            'ip_3'          => 'required',
            'ip_4'          => 'required'
        ],
        // Create custom notification for the validation request
        [
            'nik.required'          => 'NIK harus diisi!',
            'nik.min'               => 'Ketik minimal 8 digit!',
            'nik.max'               => 'Ketik maksimal 8 digit!',
            'unique'                => 'NIK sudah ada!',
            'nama_client.required'  => 'Nama Client harus diisi!',
            'nama_client.min'       => 'Ketik minimal 2 digit!',
            'nama_client.max'       => 'Ketik maksimal 255 digit!',
            'position_id.required'  => 'Jabatan harus dipilih!',
            'location_id.required'  => 'Lokasi harus dipilih!',
            'telp.required'         => 'Nama Client harus diisi!',
            'telp.min'              => 'Ketik minimal 4 digit!',
            'telp.max'              => 'Ketik maksimal 13 digit!',
        ]);

        $data   = $request->all();
        $ip1    = $data['ip_1'];
        $ip2    = $data['ip_2'];
        $ip3    = $data['ip_3'];
        $ip4    = $data['ip_4'];
        $ip_address = $ip1.'.'.$ip2.'.'.$ip3.'.'.$ip4;

        // Saving data to employees table
        $client                 = new Client;
        $client->nik            = $data['nik'];
        $client->nama_client    = $data['nama_client'];
        $client->position_id    = $data['position_id'];
        $client->location_id    = $data['location_id'];
        $client->telp           = $data['telp'];
        $client->ip_address     = $ip_address;
        $client->updated_by     = $data['updated_by'];
        $client->save();

        // Redirect to the employee view if create data succeded
        $nama_client = ucwords($request['nama_client']);
        return redirect('/clients')->with('success', $nama_client.' telah ditambahkan!');
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
    public function edit(Client $client)
    {
        return view('contents.client.edit', [
            "title"     => "Edit Client",
            "path"      => "Client",
            "path2"     => "Edit",
            "positions" => Position::orderBy('nama_jabatan', 'ASC')->get(),
            "locations" => Location::orderBy('nama_lokasi', 'ASC')->get(),
            "client"    => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        // Validating data request
        $rules = [
            'nama_client'   => 'required|min:2|max:40',
            'position_id'   => 'required',
            'location_id'   => 'required',
            'telp'          => 'required|min:4|max:20',
            'ip_address'    => 'required|min:7|max:15',
            'updated_by'    => 'required'
        ];

        if($request->nik != $client->nik){
            $rules['nik'] = 'required|min:8|max:8|unique:clients';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'nik.required'          => 'NIK harus diisi!',
            'nik.min'               => 'Ketik minimal 8 digit!',
            'nik.max'               => 'Ketik maksimal 8 digit!',
            'unique'                => 'NIK sudah ada!',
            'nama_client.required'  => 'Nama Client harus diisi!',
            'nama_client.min'       => 'Ketik minimal 2 digit!',
            'nama_client.max'       => 'Ketik maksimal 255 digit!',
            'position_id.required'  => 'Jabatan harus dipilih!',
            'location_id.required'  => 'Lokasi harus dipilih!',
            'telp.required'         => 'No. Telp/Ext harus diisi!',
            'telp.min'              => 'Ketik minimal 4 digit!',
            'telp.max'              => 'Ketik maksimal 13 digit!',
            'ip_address.required'   => 'IP Address harus diisi!',
            'ip_address.min'        => 'Ketik minimal 7 digit!',
            'ip_address.max'        => 'Ketik maksimal 15 digit!'
        ]);

        // Updating data to branches table
        Client::where('id', $client->id)->update($validatedData);
        
        return redirect('/clients')->with('success', 'Data Client telah diubah!');
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
