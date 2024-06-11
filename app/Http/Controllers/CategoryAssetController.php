<?php

namespace App\Http\Controllers;

use App\Category_asset;
use Illuminate\Http\Request;

class CategoryAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get semua data Category Asset
        $categoryAssets = Category_asset::all();
        
        return view('contents.asset.asset_category.index', [
            "url"               => "",
            "title"             => "Asset Category List",
            "path"              => "Asset Category",
            "path2"             => "Asset Category",
            "category_assets"   => $categoryAssets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.asset.asset_category.create', [
            "url"               => "",
            "title"             => "Create Asset Category",
            "path"              => "Asset Category",
            "path2"             => "Create"
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
            'nama_kategori' => 'required|min:5|max:50|unique:category_assets',
            'updated_by'    => 'required'
        ],

        // Create custom notification for the validation request
        [
            'nama_kategori.required'    => 'Category Name required!',
            'nama_kategori.min'         => 'Type at least 5 characters!',
            'nama_kategori.max'         => 'Type maximum 50 characters!',
            'unique'                    => 'Category Name already exists',
            'updated_by.required'       => 'Wajib diisi!'
        ]);

        // Simpan data Category Asset sesuai request yang telah di validasi
        Category_asset::create($validatedData);

        // Get nomor Nama Kategori Asset untuk ditampilkan di notifikasi sukses
        $namaKategori = $request['nama_kategori'];

        // Redirect ke halaman Category Asset List beserta notifikasi sukses
        return redirect('/asset-categories')->with('success', ucwords($namaKategori).' successfully created!');
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
        // Get id Category Asset dari request parameter
        $id = decrypt($request['id']);

        // Get data Category Asset berdasarkan id Category Asset
        $categoryAsset = Category_asset::where('id', $id)->first();

        return view('contents.asset.asset_category.edit', [
            "title"     => "Edit Asset Category",
            "path"      => "Asset Category",
            "path2"     => "Edit",
            "ca"        => $categoryAsset
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
        // Get id Category Asset dari request parameter
        $id = decrypt($request['id']);

        // Validating data request
        $validatedData = $request->validate([
            'nama_kategori' => 'required|min:5|max:50|unique:category_tickets',
            'updated_by'    => 'required'
        ],

        // Create custom notification for the validation request
        [
            'nama_kategori.required'    => 'Category Name required!',
            'nama_kategori.min'         => 'Type at least 5 characters!',
            'nama_kategori.max'         => 'Type maximum 50 characters!',
            'unique'                    => 'Category Name already exists',
            'updated_by.required'       => 'Wajib diisi!'
        ]);

        // Updating data Category Asset sesuai request yang telah di validasi
        Category_asset::where('id', $id)->update($validatedData);

        // Redirect ke halaman Category Asset List beserta notifikasi sukses
        return redirect('/asset-categories')->with('success', 'Asset Category successfully updated!');
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