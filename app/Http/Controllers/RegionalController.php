<?php

namespace App\Http\Controllers;

use App\Area;
use App\Wilayah;
use App\Regional;
use App\Sub_division;
use Illuminate\Http\Request;

class RegionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get data Regional
        $regionals = Regional::orderBy('name', 'ASC')->get();

        return view('contents.location.regional.index', [
            "title"     => "Regional List",
            "path"      => "Regional",
            "path2"     => "Regional",
            "regionals" => $regionals
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas = Area::orderBy('name', 'ASC')->get();

        return view('contents.location.regional.create', [
            "title" => "Create Regional",
            "path"  => "Regional",
            "path2" => "Tambah",
            "areas" => $areas
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
            'name'          => 'required|max:50|unique:regionals',
            'area_id'       => 'required',
            'updated_by'    => 'required',
        ],
        // Create custom notification for the validation request
        [
            'name.required'     => 'Nama Regional harus diisi!',
            'name.max'          => 'Ketik maksimal 50 digit!',
            'unique'            => 'Nama Regional sudah ada!',
            'area_id.required'  => 'Area harus dipilih!',
        ]);

        // Saving data to Regional table
        Regional::create($validatedData);

        $codeAccess = substr($request['name'], -1);

        // Saving data to Sub Divisi table
        $sub_division               = new Sub_division;
        $sub_division->name         = $request['name'];
        $sub_division->location_id  = 17;
        $sub_division->code_access  = $codeAccess;
        $sub_division->updated_by   = $request['updated_by'];
        $sub_division->save();

        // Redirect to the area view if create data succeded
        $namaRegional = $request['name'];
        return redirect('/location-regionals')->with('success', ucwords($namaRegional).' telah ditambahkan!');
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
        
        $regional = Regional::find($id);
        $name = $regional->name;
        $countWilayah = Wilayah::where('regional_id', $id)->count();
        $sub_division = Sub_division::where('name', $name)->first();

        if($countWilayah == 0) {
            $regional->delete();
            $sub_division->delete();
        }else{
            return back()->with('error', ucwords($name).' tidak dapat dihapus!');
        }

        return back()->with('success', ucwords($name).' berhasil dihapus!');
    }
}