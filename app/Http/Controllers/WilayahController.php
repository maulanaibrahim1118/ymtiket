<?php

namespace App\Http\Controllers;

use App\Wilayah;
use App\Location;
use App\Regional;
use App\Sub_division;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get data Area
        $wilayahs = Wilayah::orderBy('name', 'ASC')->get();

        return view('contents.location.wilayah.index', [
            "title"     => "Wilayah List",
            "path"      => "Wilayah",
            "path2"     => "Wilayah",
            "wilayahs"  => $wilayahs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regionals = Regional::orderBy('name', 'ASC')->get();

        return view('contents.location.wilayah.create', [
            "title"     => "Create Wilayah",
            "path"      => "Wilayah",
            "path2"     => "Create",
            "regionals" => $regionals
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
            'name'          => 'required|max:50|unique:wilayahs',
            'regional_id'   => 'required',
            'updated_by'    => 'required',
        ],
        // Create custom notification for the validation request
        [
            'name.required'         => 'Wilayah Name required!',
            'name.max'              => 'type maximum 50 characters!',
            'unique'                => 'Wilayah Name already exists!',
            'regional_id.required'  => 'Regional must be selected!',
        ]);

        // Saving data to Regional table
        Wilayah::create($validatedData);

        $codeAccess = substr($request['name'], -2);

        // Saving data to Sub Divisi table
        $sub_division               = new Sub_division;
        $sub_division->name         = $request['name'];
        $sub_division->location_id  = 17;
        $sub_division->code_access  = $codeAccess;
        $sub_division->updated_by   = $request['updated_by'];
        $sub_division->save();

        // Redirect to the area view if create data succeded
        $namaWilayah = $request['name'];
        return redirect('/location-wilayahs')->with('success', ucwords($namaWilayah).' successfully created!');
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Get id User dari request parameter
        $id = decrypt($request['id']);
        
        $wilayah = Wilayah::find($id);
        $name = $wilayah->name;
        $countLocation = Location::where('wilayah_id', $id)->count();
        $sub_division = Sub_division::where('name', $name)->first();

        if($countLocation == 0) {
            $wilayah->delete();
            $sub_division->delete();
        }else{
            return back()->with('error', ucwords($name).' cannot be deleted!');
        }

        return back()->with('success', ucwords($name).' successfully deleted!');
    }

    public function getDetailRegional($id)
    {
        $regional = Regional::with('area')->find($id);
        return response()->json($regional);
    }
}