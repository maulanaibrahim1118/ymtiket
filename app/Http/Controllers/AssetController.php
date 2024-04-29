<?php

namespace App\Http\Controllers;

use App\User;
use App\Asset;
use App\Ticket;
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
        // Get Lokasi User
        $locationId = Auth::user()->location_id;

        if ($locationId == 10) {
            // Get data Asset sesuai lokasi user
            $assets = Asset::all();
        } else {
            // Get data Asset sesuai lokasi user
            $assets = Asset::where('location_id', $locationId)->get();
        }

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
            'no_asset'          => 'required|min:8|max:13|unique:assets',
            'nama_barang'       => 'required|min:3|max:30',
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
            'no_asset.min'                  => 'Ketik minimal 8 digit!',
            'no_asset.max'                  => 'Ketik maksimal 13 digit!',
            'unique'                        => 'No. Asset sudah ada!',
            'nama_barang.required'          => 'Nama Barang harus diisi!',
            'nama_barang.min'               => 'Ketik minimal 3 digit!',
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

        // Simpan data Asset sesuai request yang telah di validasi
        Asset::create($validatedData);

        // Get nomor Asset untuk ditampilkan di notifikasi sukses
        $no_asset = $request['no_asset'];
        
        // Redirect ke halaman asset list beserta notifikasi sukses
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
    public function edit(Request $request)
    {
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);

        // Get data Asset berdasarkan id Asset
        $asset = Asset::where('id', $id)->first();

        // Get data Category Asset untuk ditampilkan di select option view edit
        $category_assets = Category_asset::orderBy('nama_kategori', 'ASC')->get();
        $locations = Location::orderBy('nama_lokasi', 'ASC')->get();

        return view('contents.asset.edit', [
            "title"             => "Edit Asset",
            "path"              => "Asset",
            "path2"             => "Edit",
            "category_assets"   => $category_assets,
            "locations"         => $locations,
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
    public function update(Request $request)
    {
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);
        
        // Get data Asset berdasarkan id Asset
        $asset = Asset::where('id', $id)->first();

        // Validating data request
        $rules = [
            'nama_barang'       => 'required|min:3|max:30',
            'category_asset_id' => 'required',
            'merk'              => 'required|max:30',
            'model'             => 'required|max:30',
            'serial_number'     => 'required|max:30',
            'status'            => 'required',
            'location_id'       => 'required',
            'updated_by'        => 'required'
        ];

        if($request->no_asset != $asset->no_asset){
            $rules['no_asset'] = 'required|min:8|max:13|unique:assets';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'no_asset.required'             => 'No. Asset harus diisi!',
            'no_asset.min'                  => 'Ketik minimal 8 digit!',
            'no_asset.max'                  => 'Ketik maksimal 13 digit!',
            'unique'                        => 'No. Asset sudah ada!',
            'nama_barang.required'          => 'Nama Barang harus diisi!',
            'nama_barang.min'               => 'Ketik minimal 3 digit!',
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

        // Updating data Asset sesuai request yang telah di validasi
        Asset::where('id', $id)->update($validatedData);
        
        // Redirect ke halaman asset list beserta notifikasi sukses
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

    // Menampilkan Asset Berkendala yang telah/sedang ditangani
    public function assetDashboard(Request $request)
    {
        // Get data User
        $id         = Auth::user()->id;
        $role       = Auth::user()->role;
        $location   = Auth::user()->location->nama_lokasi;

        $status     = $request->input('status');
        $agent      = $request->input('filter1');
        $periode    = $request->input('filter2');
        
        $title      = "Asset Berkendala";

        // Menentukan Filter by Agent
        if($agent == NULL){
            $filter1        = "";
            $namaAgent      = "Semua Agent";
        }else{
            $filter1        = $agent;
            $agentFilter    = Agent::where('id', $filter1)->first();
            $namaAgent      = ucwords($agentFilter->nama_agent);
        }

        // Menentukan Filter by Periode
        if($periode == "today"){
            $filter2    = date('Y-m-d');
            $pathFilter = date('d F Y');
        }elseif($periode == "monthly"){
            $filter2    = date('Y-m');
            $pathFilter = date('F Y');
        }elseif($periode == "yearly"){
            $filter2    = date('Y');
            $pathFilter = date('Y');
        }else{
            $filter2    = "";
            $pathFilter = "Semua Periode";
        }

        // Mencari asset yang sedang berkendala berdasarkan ticket yang masuk dan filter (agent & periode)
        $tickets = Ticket::where([['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%'],['ticket_for', $location]])->whereNotIn('status', ['deleted'])
            ->groupBy('asset_id')
            ->select('asset_id')
            ->orderBy('asset_id', 'ASC')
            ->get();

        return view('contents.asset.filter.index', [
            "url"           => "",
            "title"         => $title,
            "path"          => "Asset",
            "path2"         => $title,
            "pathFilter"    => "[".$namaAgent."] - [".$pathFilter."]",
            "tickets"       => $tickets
        ]);
    }
}