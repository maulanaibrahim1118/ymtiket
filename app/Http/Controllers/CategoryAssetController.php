<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category_asset;

class CategoryAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category_assets    = Category_asset::all();
        return view('contents.category_asset.index', [
            "url"               => "",
            "title"             => "Category Asset List",
            "path"              => "Category Asset",
            "category_assets"   => $category_assets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.category_asset.create', [
            "url"               => "",
            "title"             => "Create Category Asset",
            "path"              => "Category Asset",
            "path2"             => "Tambah"
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
            'nama_kategori.required'    => 'Nama Kategori Asset harus diisi!',
            'nama_kategori.min'         => 'Ketik minimal 5 digit!',
            'nama_kategori.max'         => 'Ketik maksimal 50 digit!',
            'unique'                    => 'Nama Kategori Asset sudah ada!',
            'updated_by.required'       => 'Wajib diisi!'
        ]);
        // Saving data to category_asset table
        Category_asset::create($validatedData);

        // Redirect to the Category Asset view if create data succeded
        $nama_kategori = $request['nama_kategori'];
        return redirect('/category-assets')->with('success', ucwords($nama_kategori).' telah ditambahkan!');
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
    public function edit(Category_asset $category_asset)
    {
        return view('contents.category_asset.edit', [
            "title"     => "Edit Category Asset",
            "path"      => "Category Asset",
            "path2"     => "Edit",
            "ca"        => $category_asset
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category_asset $category_asset)
    {
        // Validating data request
        $validatedData = $request->validate([
            'nama_kategori' => 'required|min:5|max:50|unique:category_tickets',
            'updated_by'    => 'required'
        ],
        // Create custom notification for the validation request
        [
            'nama_kategori.required'    => 'Nama Kategori Asset harus diisi!',
            'nama_kategori.min'         => 'Ketik minimal 5 digit!',
            'nama_kategori.max'         => 'Ketik maksimal 50 digit!',
            'unique'                    => 'Nama Kategori Ticket sudah ada!',
            'updated_by.required'       => 'Wajib diisi!'
        ]);
        Category_asset::where('id', $category_asset->id)->update($validatedData);

        return redirect('/category-assets')->with('success', 'Data Category Asset telah diubah!');
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
