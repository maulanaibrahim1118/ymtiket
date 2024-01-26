<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations  = Location::all();
        return view('contents.location.index', [
            "url"       => "",
            "title"     => "Location List",
            "path"      => "Location",
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
        $regionals  = ["distribution center", "head office", "regional a", "regional b", "regional c", "regional d", "regional e", "regional f"];
        $areas      = ["area 1", "area 2", "distribution center", "head office"];
        $wilayahs   = ["distribution center", "head office", "wilayah 1", "wilayah 2", "wilayah 3", "wilayah 4", "wilayah 5", "wilayah 6", "wilayah 7", "wilayah 8", "wilayah 9", "wilayah 10"];

        return view('contents.location.create', [
            "url"       => "",
            "title"     => "Create Lokasi",
            "path"      => "Lokasi",
            "path2"     => "Tambah",
            "wilayahs"  => $wilayahs,
            "regionals" => $regionals,
            "areas"     => $areas
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
            'inisial'       => 'required|min:2|max:3|unique:locations',
            'nama_lokasi'   => 'required|min:5|max:50|unique:locations',
            'wilayah'       => 'required',
            'regional'      => 'required',
            'area'          => 'required|min:4|max:13',
            'updated_by'    => 'required'
        ],
        // Create custom notification for the validation request
        [
            'inisial.required'      => 'Inisial harus diisi!',
            'inisial.min'           => 'Ketik minimal 2 digit!',
            'inisial.max'           => 'Ketik maksimal 3 digit!',
            'inisial.unique'        => 'Inisial sudah ada!',
            'nama_lokasi.required'  => 'Nama Lokasi harus diisi!',
            'nama_lokasi.min'       => 'Ketik minimal 5 digit!',
            'nama_lokasi.max'       => 'Ketik maksimal 50 digit!',
            'nama_lokasi.unique'    => 'Nama Lokasi sudah ada!',
            'wilayah.required'      => 'Wilayah harus dipilih!',
            'regional.required'     => 'Regional harus dipilih!',
            'area.required'         => 'Area harus dipilih!',
        ]);
        // Saving data to locations table
        Location::create($validatedData);

        // Redirect to the employee view if create data succeded
        $nama_lokasi = ucwords($request['nama_lokasi']);
        return redirect('/locations')->with('success', $nama_lokasi.' telah ditambahkan!');
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
    public function edit(Location $location)
    {
        $regionals  = ["distribution center", "head office", "regional a", "regional b", "regional c", "regional d", "regional e", "regional f"];
        $areas      = ["area 1", "area 2", "distribution center", "head office"];
        $wilayahs   = ["distribution center", "head office", "wilayah 1", "wilayah 2", "wilayah 3", "wilayah 4", "wilayah 5", "wilayah 6", "wilayah 7", "wilayah 8", "wilayah 9", "wilayah 10"];

        return view('contents.location.edit', [
            "title"     => "Edit Location",
            "path"      => "Location",
            "path2"     => "Edit",
            "wilayahs"  => $wilayahs,
            "regionals" => $regionals,
            "areas"     => $areas,
            "location"  => $location
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        // Validating data request
        $rules = [
            'wilayah'       => 'required',
            'regional'      => 'required',
            'area'          => 'required',
            'updated_by'    => 'required'
        ];

        if($request->inisial != $location->inisial){
            $rules['inisial'] = 'required|min:2|max:3|unique:locations';
        }

        if($request->nama_lokasi != $location->nama_lokasi){
            $rules['nama_lokasi'] = 'required|min:5|max:50|unique:locations';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'inisial.required'      => 'Inisial harus diisi!',
            'inisial.min'           => 'Ketik minimal 2 digit!',
            'inisial.max'           => 'Ketik maksimal 3 digit!',
            'inisial.unique'        => 'Inisial sudah ada!',
            'nama_lokasi.required'  => 'Nama Lokasi harus diisi!',
            'nama_lokasi.min'       => 'Ketik minimal 5 digit!',
            'nama_lokasi.max'       => 'Ketik maksimal 50 digit!',
            'nama_lokasi.unique'    => 'Nama Lokasi sudah ada!',
            'wilayah.required'      => 'Wilayah harus dipilih!',
            'regional.required'     => 'Regional harus dipilih!',
            'area.required'         => 'Area harus dipilih!',
            'updated_by.required'   => 'Wajib diisi!'
        ]);

        // Updating data to branches table
        Location::where('id', $location->id)->update($validatedData);
        
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
