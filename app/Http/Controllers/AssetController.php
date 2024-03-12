<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Location;
use App\Category_asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locationId = Auth::user()->location_id;
        $assets = Asset::where('location_id', $locationId)->get();
        return view('contents.asset.index', [
            "url"       => "",
            "title"     => "Asset List",
            "path"      => "Asset",
            "path2"     => "Asset",
            "assets"    => $assets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.asset.create', [
            "url"               => "",
            "title"             => "Create Asset",
            "path"              => "Asset",
            "path2"             => "Tambah",
            "category_assets"   => Category_asset::orderBy('nama_kategori', 'ASC')->get(),
            "locations"         => Location::orderBy('nama_lokasi', 'ASC')->get()
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
            'no_asset'          => 'required|min:13|max:13|unique:assets',
            'nama_barang'       => 'required|min:5|max:30',
            'category_asset_id' => 'required',
            'merk'              => 'required|max:30',
            'model'             => 'required|max:30',
            'serial_number'     => 'required|max:30',
            'status'            => 'required',
            'location_id'       => 'required',
            'updated_by'        => 'required'
        ],
        // Create custom notification for the validation request
        [
            'no_asset.required'             => 'No. Asset harus diisi!',
            'no_asset.min'                  => 'Ketik minimal 13 digit!',
            'no_asset.max'                  => 'Ketik maksimal 13 digit!',
            'unique'                        => 'No. Asset sudah ada!',
            'nama_barang.required'          => 'Nama Barang harus diisi!',
            'nama_barang.min'               => 'Ketik minimal 5 digit!',
            'nama_barang.max'               => 'Ketik maksimal 30 digit!',
            'category_asset_id.required'    => 'Kategori harus dipilih!',
            'merk.required'                 => 'Merk harus diisi!',
            'merk.max'                      => 'Ketik maksimal 30 digit!',
            'model.required'                => 'Model harus diisi!',
            'model.max'                     => 'Ketik maksimal 30 digit!',
            'serial_number.required'        => 'Serial Number harus diisi!',
            'serial_number.max'             => 'Ketik maksimal 30 digit!',
            'status.required'               => 'Status harus diisi!',
            'location_id.required'          => 'Lokasi harus dipilih!',
            'updated_by.required'           => 'Wajib diisi!'
        ]);
        // Saving data to locations table
        Asset::create($validatedData);

        // Redirect to the Asset view if create data succeded
        $no_asset = $request['no_asset'];
        return redirect('/assets')->with('success', 'No. Asset '.ucwords($no_asset).' telah ditambahkan!');
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
    public function edit(Asset $asset)
    {
        return view('contents.asset.edit', [
            "title"             => "Edit Asset",
            "path"              => "Asset",
            "path2"             => "Edit",
            "category_assets"   => Category_asset::orderBy('nama_kategori', 'ASC')->get(),
            "locations"         => Location::orderBy('nama_lokasi', 'ASC')->get(),
            "asset"             => $asset
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asset $asset)
    {
        // Validating data request
        $rules = [
            'nama_barang'       => 'required|min:5|max:30',
            'category_asset_id' => 'required',
            'merk'              => 'required|max:30',
            'model'             => 'required|max:30',
            'serial_number'     => 'required|max:30',
            'status'            => 'required',
            'location_id'       => 'required',
            'updated_by'        => 'required'
        ];

        if($request->no_asset != $asset->no_asset){
            $rules['no_asset'] = 'required|min:13|max:13|unique:assets';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'no_asset.required'             => 'No. Asset harus diisi!',
            'no_asset.min'                  => 'Ketik minimal 13 digit!',
            'no_asset.max'                  => 'Ketik maksimal 13 digit!',
            'unique'                        => 'No. Asset sudah ada!',
            'nama_barang.required'          => 'Nama Barang harus diisi!',
            'nama_barang.min'               => 'Ketik minimal 5 digit!',
            'nama_barang.max'               => 'Ketik maksimal 30 digit!',
            'category_asset_id.required'    => 'Kategori harus dipilih!',
            'merk.required'                 => 'Merk harus diisi!',
            'merk.max'                      => 'Ketik maksimal 30 digit!',
            'model.required'                => 'Model harus diisi!',
            'model.max'                     => 'Ketik maksimal 30 digit!',
            'serial_number.required'        => 'Serial Number harus diisi!',
            'serial_number.max'             => 'Ketik maksimal 30 digit!',
            'status.required'               => 'Status harus diisi!',
            'location_id.required'          => 'Lokasi harus dipilih!',
            'updated_by.required'           => 'Wajib diisi!'
        ]);

        // Updating data to branches table
        Asset::where('id', $asset->id)->update($validatedData);
        
        return redirect('/assets')->with('success', 'Data Asset telah diubah!');
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
