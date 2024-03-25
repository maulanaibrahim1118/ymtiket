<?php

namespace App\Http\Controllers;

use App\Wilayah;
use App\Location;
use Illuminate\Http\Request;

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
        $locations  = Location::all();

        return view('contents.location.index', [
            "title"     => "Location List",
            "path"      => "Location",
            "path2"     => "Location",
            "locations" => $locations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Menyiapkan data array untuk di tampilkan di select option pada view create location
        $areas      = ["area 1", "area 2", "distribution center", "head office"];
        $regionals  = ["distribution center", "head office", "regional a", "regional b", "regional c", "regional d", "regional e", "regional f"];

        return view('contents.location.create', [
            "title"     => "Create Lokasi",
            "path"      => "Lokasi",
            "path2"     => "Tambah",
            "areas"     => $areas,
            "regionals" => $regionals,
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
            'nama_lokasi'   => 'required|min:5|max:50|unique:locations',
            'wilayah'       => 'required',
            'regional'      => 'required',
            'area'          => 'required|min:4|max:13',
            'updated_by'    => 'required'
        ],
        
        // Create custom notification for the validation request
        [
            'nama_lokasi.required'  => 'Nama Lokasi harus diisi!',
            'nama_lokasi.min'       => 'Ketik minimal 5 digit!',
            'nama_lokasi.max'       => 'Ketik maksimal 50 digit!',
            'nama_lokasi.unique'    => 'Nama Lokasi sudah ada!',
            'wilayah.required'      => 'Wilayah harus dipilih!',
            'regional.required'     => 'Regional harus dipilih!',
            'area.required'         => 'Area harus dipilih!',
        ]);

        // Simpan data Location sesuai request yang telah di validasi
        Location::create($validatedData);

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
        $areas      = ["area 1", "area 2", "distribution center", "head office"];
        $regionals  = ["distribution center", "head office", "regional a", "regional b", "regional c", "regional d", "regional e", "regional f"];
        $wilayah    = Wilayah::all();

        return view('contents.location.edit', [
            "title"     => "Edit Location",
            "path"      => "Location",
            "path2"     => "Edit",
            "areas"     => $areas,
            "location"  => $location,
            "regionals" => $regionals,
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
            'wilayah'       => 'required',
            'regional'      => 'required',
            'area'          => 'required',
            'updated_by'    => 'required'
        ];

        if($request->nama_lokasi != $location->nama_lokasi){
            $rules['nama_lokasi'] = 'required|min:5|max:50|unique:locations';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'nama_lokasi.required'  => 'Nama Lokasi harus diisi!',
            'nama_lokasi.min'       => 'Ketik minimal 5 digit!',
            'nama_lokasi.max'       => 'Ketik maksimal 50 digit!',
            'nama_lokasi.unique'    => 'Nama Lokasi sudah ada!',
            'wilayah.required'      => 'Wilayah harus dipilih!',
            'regional.required'     => 'Regional harus dipilih!',
            'area.required'         => 'Area harus dipilih!',
            'updated_by.required'   => 'Wajib diisi!'
        ]);

        // Updating data Location sesuai request yang telah di validasi
        Location::where('id', $id)->update($validatedData);
        
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
}