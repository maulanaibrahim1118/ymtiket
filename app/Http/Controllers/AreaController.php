<?php

namespace App\Http\Controllers;

use App\Area;
use App\Regional;
use App\Sub_division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get data Area
        $areas = Area::orderBy('name', 'ASC')->get();

        return view('contents.location.area.index', [
            "title" => "Area List",
            "path"  => "Area",
            "path2" => "Area",
            "areas" => $areas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.location.area.create', [
            "title"     => "Create Area",
            "path"      => "Area",
            "path2"     => "Tambah"
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
            'name' => 'required|max:50|unique:areas',
        ],
        // Create custom notification for the validation request
        [
            'name.required' => 'Nama Area harus diisi!',
            'name.max'      => 'Ketik maksimal 50 digit!',
            'unique'        => 'Nama Area sudah ada!',
        ]);

        // Saving data to Area table
        $area               = new Area;
        $area->name         = $request['name'];
        $area->updated_by   = $request['updated_by'];
        $area->save();

        $codeAccess = substr($request['name'], -1);

        // Saving data to Sub Divisi table
        $sub_division               = new Sub_division;
        $sub_division->name         = $request['name'];
        $sub_division->location_id  = 17;
        $sub_division->code_access  = $codeAccess;
        $sub_division->updated_by   = $request['updated_by'];
        $sub_division->save();

        // Redirect to the area view if create data succeded
        $namaArea = $request['name'];
        return redirect('/location-areas')->with('success', ucwords($namaArea).' telah ditambahkan!');
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
        // // Get id User dari request parameter
        // $id = decrypt($request['id']);

        // // Get data User berdasarkan id User
        // $area = Area::where('id', $id)->first();

        // return view('contents.location.area.edit', [
        //     "title" => "Edit Area",
        //     "path"  => "Area",
        //     "path2" => "Edit",
        //     "area"  => $area
        // ]);
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
        // // Get id Asset dari request parameter
        // $id = decrypt($request['id']);

                
        // // Get data User berdasarkan id User
        // $area = Area::where('id', $id)->first();

        // // Validating data request
        // $rules = [
        //     'updated_by'    => 'required'
        // ];

        // if($request->name != $area->name){
        //     $rules['name'] = 'required|max:50|unique:areas';
        // }

        // // Create custom notification for the validation request
        // $validatedData = $request->validate($rules,
        // [
        //     'name.required'         => 'Nama Area harus diisi!',
        //     'name.max'              => 'Ketik maksimal 50 digit!',
        //     'unique'                => 'Nama Area sudah ada!',
        //     'updated_by.required'   => 'Wajib diisi!'
        // ]);

        // // Updating data to user table
        // Area::where('id', $area->id)->update([
        //     'name'          => $request['name'],
        //     'updated_by'    => $request['updated_by']
        // ]);

        // return redirect('/location-areas')->with('success', 'Data Area telah diubah!');
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
        
        $area = Area::find($id);
        $name = $area->name;
        $countRegional = Regional::where('area_id', $id)->count();
        $sub_division = Sub_division::where('name', $name)->first();

        if($countRegional == 0) {
            $area->delete();
            $sub_division->delete();
        }else{
            return back()->with('error', ucwords($name).' tidak dapat dihapus!');
        }

        return back()->with('success', ucwords($name).' berhasil dihapus!');
    }
}