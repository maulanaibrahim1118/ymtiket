<?php

namespace App\Http\Controllers;

use App\Location;
use App\Sub_division;
use Illuminate\Http\Request;

class SubDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get semua data lokasi
        $subDivisions  = Sub_division::whereNotIn('location_id', [17])->get();

        return view('contents.location.sub_division.index', [
            "title"         => "Sub Division List",
            "path"          => "Sub Division",
            "path2"         => "Sub Division",
            "subDivisions"  => $subDivisions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get semua data lokasi
        $locations  = Location::whereIn('wilayah_id', [1, 2])->whereNotIn('nama_lokasi', ['operational'])->where('is_active', '1')->get();

        $codes = ["all", "ho", "store"];

        return view('contents.location.sub_division.create', [
            "title"     => "Create Sub Division",
            "path"      => "Sub Division",
            "path2"     => "Create",
            "locations" => $locations,
            "codes"     => $codes
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
            'name'          => 'required|unique:sub_divisions',
            'location_id'   => 'required',
            'code_access'   => 'required',
            'updated_by'    => 'required'
        ],
        
        // Create custom notification for the validation request
        [
            'name.required'         => 'Sub Division Name required!',
            'name.unique'           => 'Sub Division Name already exists!',
            'location_id.required'  => 'Division must be selected!',
            'code_access.required'  => 'Code Access must be selected!',
        ]);

        // Simpan data Sub Divisi sesuai request yang telah di validasi
        Sub_division::create($validatedData);

        // Get Nama Lokasi untuk ditampilkan di notifikasi sukses
        $nama = $request['name'];

        // Redirect ke halaman Sub Division List beserta notifikasi sukses
        return redirect('/location-sub-divisions')->with('success', ucwords($nama).' successfully created!');
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
        $subDivision = Sub_division::where('id', $id)->first();
        $locations  = Location::whereIn('wilayah_id', [1, 2])->whereNotIn('nama_lokasi', ['operational'])->where('is_active', '1')->get();

        // Menyiapkan data array untuk di tampilkan di select option pada view edit location
        $codes = ["all", "ho", "store"];

        return view('contents.location.sub_division.edit', [
            "title"         => "Edit Sub Division",
            "path"          => "Sub Division",
            "path2"         => "Edit",
            "subDivision"   => $subDivision,
            "locations"     => $locations,
            "codes"         => $codes,
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
        $subDivision = Sub_division::where('id', $id)->first();

        // Validating data request
        $rules = [
            'location_id'   => 'required',
            'code_access'   => 'required',
            'updated_by'    => 'required'
        ];

        if($request->name != $subDivision->name){
            $rules['name'] = 'required|unique:sub_divisions';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'name.required'         => 'Sub Division Name required!',
            'name.unique'           => 'Sub Division Name already exists!',
            'location_id.required'  => 'Division must be selected!',
            'code_access.required'  => 'Code Access must be selected!',
        ]);

        // Updating data Location sesuai request yang telah di validasi
        Sub_division::where('id', $id)->update($validatedData);
        
        // Redirect ke halaman Location List beserta notifikasi sukses
        return redirect('/location-sub-divisions')->with('success', 'Sub Division successfully updated!');
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