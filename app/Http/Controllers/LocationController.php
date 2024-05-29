<?php

namespace App\Http\Controllers;

use App\User;
use App\Wilayah;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get semua data lokasi
        $divisions  = Location::whereIn('wilayah_id', [1, 2])->get();
        $stores  = Location::whereNotIn('wilayah_id', [1, 2])->get();

        return view('contents.location.division_store.index', [
            "title"     => "Store & Division List",
            "path"      => "Store & Division",
            "path2"     => "Store & Division",
            "divisions" => $divisions,
            "stores"    => $stores
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.location.division_store.create', [
            "title"     => "Create Store & Division",
            "path"      => "Store & Division",
            "path2"     => "Tambah",
            "wilayahs"  => Wilayah::all()
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
            'site'          => 'required|max:5|unique:locations',
            'nama_lokasi'   => 'required|min:3|max:50|unique:locations',
            'wilayah_id'    => 'required',
            'updated_by'    => 'required'
        ],
        
        // Create custom notification for the validation request
        [
            'site.required'         => 'Isi!',
            'site.max'              => 'Maks 5!',
            'site.unique'           => 'Exist!',
            'nama_lokasi.required'  => 'Nama Lokasi harus diisi!',
            'nama_lokasi.min'       => 'Ketik minimal 3 karakter!',
            'nama_lokasi.max'       => 'Ketik maksimal 50 karakter!',
            'nama_lokasi.unique'    => 'Nama Lokasi sudah ada!',
            'wilayah_id.required'   => 'Wilayah harus dipilih!'
        ]);

        $data   = $request->all();
        $ip1    = $data['ip_1'];
        $ip2    = $data['ip_2'];
        $ip3    = $data['ip_3'];
        $ip4    = $data['ip_4'];
        $ip_address = $ip1.'.'.$ip2.'.'.$ip3.'.'.$ip4;
        
        // Simpan data Location sesuai request yang telah di validasi
        $location               = new Location;
        $location->site         = $data['site'];
        $location->nama_lokasi  = $data['nama_lokasi'];
        $location->wilayah_id   = $data['wilayah_id'];
        $location->updated_by   = $data['updated_by'];
        $location->save();

        $getWilayah = Wilayah::find($request['wilayah_id']);

        $area       = substr($request['area'], -1);
        $regional   = substr($request['regional'], -1);
        $wilayah    = substr($getWilayah['name'], -2);
        $codeAccess = $area.$regional.$wilayah;

        // Jika yang di simpan adalah data cabang, otomatis create user
        if ($data['wilayah_id'] != 1 && $data['wilayah_id'] != 2){
            // Saving data to user table
            $user                 = new User;
            $user->nik            = $data['site'];
            $user->nama           = $data['nama_lokasi'];
            $user->password       = Hash::make('password');
            $user->position_id    = 5;
            $user->location_id    = $location->id;
            $user->telp           = $data['telp'];
            $user->ip_address     = $ip_address;
            $user->sub_divisi     = 'tidak ada';
            $user->code_access    = $codeAccess;
            $user->role_id        = 3;
            $user->updated_by     = $data['updated_by'];
            $user->save();
        }

        // Get Nama Lokasi untuk ditampilkan di notifikasi sukses
        $namaLokasi = $request['nama_lokasi'];

        // Redirect ke halaman Category Ticket List beserta notifikasi sukses
        return redirect('/locations')->with('success', ucwords($namaLokasi).' telah ditambahkan!');
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
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);

        // Get data Asset berdasarkan id Asset
        $location = Location::where('id', $id)->first();

        // Menyiapkan data array untuk di tampilkan di select option pada view edit location
        $wilayah    = Wilayah::all();

        return view('contents.location.division_store.edit', [
            "title"     => "Edit Store & Division",
            "path"      => "Store & Division",
            "path2"     => "Edit",
            "location"  => $location,
            "wilayahs"  => $wilayah
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
        // Get id Location dari request parameter
        $id = decrypt($request['id']);
        
        // Get data Location berdasarkan id Location
        $location = Location::where('id', $id)->first();

        // Validating data request
        $rules = [
            'wilayah_id'    => 'required',
            'updated_by'    => 'required'
        ];

        if($request->nama_lokasi != $location->nama_lokasi){
            $rules['nama_lokasi'] = 'required|min:3|max:50|unique:locations';
        }

        if($request->site != $location->site){
            $rules['site'] = 'required|max:5|unique:locations';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'site.required'         => 'Isi!',
            'site.max'              => 'Maks 5!',
            'site.unique'           => 'Exist!',
            'nama_lokasi.required'  => 'Nama Lokasi harus diisi!',
            'nama_lokasi.min'       => 'Ketik minimal 3 karakter!',
            'nama_lokasi.max'       => 'Ketik maksimal 50 karakter!',
            'nama_lokasi.unique'    => 'Nama Lokasi sudah ada!',
            'wilayah_id.required'   => 'Wilayah harus dipilih!',
            'updated_by.required'   => 'Wajib diisi!'
        ]);

        $data = $request->all();

        // Updating data Location sesuai request yang telah di validasi
        Location::where('id', $id)->update([
            'site'          => $data['site'],
            'nama_lokasi'   => $data['nama_lokasi'],
            'wilayah_id'    => $data['wilayah_id'],
            'updated_by'    => $data['updated_by']
        ]);

        $getWilayah = Wilayah::find($request['wilayah_id']);

        $area       = substr($request['area'], -1);
        $regional   = substr($request['regional'], -1);
        $wilayah    = substr($getWilayah['name'], -2);
        $codeAccess = $area.$regional.$wilayah;

        if ($data['wilayah_id'] != 1 && $data['wilayah_id'] != 2){
            // Saving data to user table
            User::where('location_id', $id)->update([
                'nik'           => $data['site'],
                'nama'          => $data['nama_lokasi'],
                'telp'          => $data['telp'],
                'ip_address'    => $data['ip_address'],
                'code_access'   => $codeAccess,
                'updated_by'    => $data['updated_by']
            ]);
        }
        
        // Redirect ke halaman Location List beserta notifikasi sukses
        return redirect('/locations')->with('success', 'Data Location telah diubah!');
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

    public function close(Request $request)
    {
        // Get id User dari request parameter
        $id = decrypt($request['id']);
        
        $location   = Location::where('id', $id)->first();
        $site       = $location->site;
        $nama       = $location->nama_lokasi;

        Location::where('id', $id)->update([
            'is_active' => '0',
            'updated_by' => $request['updated_by']
        ]);

        User::where('nik', $site)->update([
            'is_active' => '0',
            'updated_by' => $request['updated_by']
        ]);

        return back()->with('success', 'Cabang '.ucwords($nama).' telah ditutup!');
    }

    public function activate(Request $request)
    {
        // Get id User dari request parameter
        $id = decrypt($request['id']);
        
        $location   = Location::where('id', $id)->first();
        $site       = $location->site;
        $nama       = $location->nama_lokasi;

        Location::where('id', $id)->update([
            'is_active' => '1',
            'updated_by' => $request['updated_by']
        ]);

        User::where('nik', $site)->update([
            'is_active' => '1',
            'updated_by' => $request['updated_by']
        ]);

        return back()->with('success', 'Cabang '.ucwords($nama).' telah diaktifkan kembali!');
    }

    public function getDetailWilayah($id)
    {
        $wilayah = Wilayah::with('regional.area')->find($id);
        return response()->json($wilayah);
    }
}