<?php

namespace App\Http\Controllers;

use App\Item;
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
        $locationName = Auth::user()->location->nama_lokasi;
        $roleId = Auth::user()->role_id;

        // Jika user Service Desk
        if ($roleId == 1) {
            if($locationId == 10) {
                $assets = Asset::all();
            }else{
                $assets = Asset::where('location_id', $locationId)
                    ->orWhere(function($query) use ($locationId, $locationName) {
                        $query->where('location_id', '!=', $locationId)
                                ->where('category_asset', $locationName);
                    })
                    ->get();
            }
        // Jika user Client
        } else {
            $assets = Asset::where('location_id', $locationId)->whereNotIn('status', ['tidak digunakan'])->get();
        }

        return view('contents.asset.asset.index', [
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
        $category_assets = Category_asset::orderBy('nama_kategori', 'ASC')->get();
        $locations = Location::where('is_active', '1')->orderBy('nama_lokasi', 'ASC')->get();

        return view('contents.asset.asset.create', [
            "url"               => "",
            "title"             => "Create Asset",
            "path"              => "Asset",
            "path2"             => "Create",
            "category_assets"   => $category_assets,
            "locations"         => $locations
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
            'no_asset'          => 'required|max:20|unique:assets',
            'category_asset'    => 'required',
            'item_id'           => 'required',
            'merk'              => 'required|max:30',
            'model'             => 'required|max:30',
            'serial_number'     => 'required|max:30',
            'status'            => 'required',
            'location_id'       => 'required',
            'asset_users'       => 'max:40',
            'updated_by'        => 'required'
        ],
        
        // Create custom notification for the validation request
        [
            'no_asset.required'         => 'Asset Number required!',
            'no_asset.max'              => 'Type maximum 20 characters!',
            'unique'                    => 'Asset Number already exists!',
            'category_asset.required'   => 'Asset Category required!',
            'item_id.required'          => 'Item Name required!',
            'merk.required'             => 'Brand required!',
            'merk.max'                  => 'Type maximum 30 characters!',
            'model.required'            => 'Model/Type required!',
            'model.max'                 => 'Type maximum 30 characters!',
            'serial_number.required'    => 'Serial Number required!',
            'serial_number.max'         => 'Type maximum 30 characters!',
            'status.required'           => 'Status required!',
            'location_id.required'      => 'Location required!',
            'asset_users.max'           => 'Type maximum 40 characters!',
            'updated_by.required'       => 'Wajib diisi!'
        ]);

        // Simpan data Asset sesuai request yang telah di validasi
        Asset::create($validatedData);

        // Redirect ke halaman asset list beserta notifikasi sukses
        return redirect('/assets')->with('success', 'Asset successfully created!');
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
        $locations = Location::where('is_active', '1')->orderBy('nama_lokasi', 'ASC')->get();
        $items = Item::where('category_asset_id', $asset->item->category_asset_id)->orderBy('name', 'ASC')->get();

        return view('contents.asset.asset.edit', [
            "title"             => "Edit Asset",
            "path"              => "Asset",
            "path2"             => "Edit",
            "category_assets"   => $category_assets,
            "locations"         => $locations,
            "asset"             => $asset,
            "items"             => $items,
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
            'category_asset'    => 'required',
            'item_id'           => 'required',
            'merk'              => 'required|max:30',
            'model'             => 'required|max:30',
            'serial_number'     => 'required|max:30',
            'status'            => 'required',
            'location_id'       => 'required',
            'asset_users'       => 'max:40',
            'updated_by'        => 'required'
        ];

        if($request->no_asset != $asset->no_asset){
            $rules['no_asset'] = 'required|max:20|unique:assets';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'no_asset.required'         => 'Asset Number required!',
            'no_asset.max'              => 'Type maximum 20 characters!',
            'unique'                    => 'Asset Number already exists!',
            'category_asset.required'   => 'Asset Category required!',
            'item_id.required'          => 'Item Name required!',
            'merk.required'             => 'Brand required!',
            'merk.max'                  => 'Type maximum 30 characters!',
            'model.required'            => 'Model/Type required!',
            'model.max'                 => 'Type maximum 30 characters!',
            'serial_number.required'    => 'Serial Number required!',
            'serial_number.max'         => 'Type maximum 30 characters!',
            'status.required'           => 'Status required!',
            'location_id.required'      => 'Location required!',
            'asset_users.max'           => 'Type maximum 40 characters!',
            'updated_by.required'       => 'Wajib diisi!'
        ]);

        // Updating data Asset sesuai request yang telah di validasi
        Asset::where('id', $id)->update($validatedData);
        
        // Redirect ke halaman asset list beserta notifikasi sukses
        return redirect('/assets')->with('success', 'Asset successfully updated!');
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
        $locationId   = Auth::user()->location_id;

        $status     = $request->input('status');
        $agent      = $request->input('filter1');
        $periode    = $request->input('filter2');
        
        $title      = "Ticket Asset";

        // Menentukan Filter by Agent
        if($agent == NULL){
            $filter1        = "";
            $namaAgent      = "All Agent";
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
            $pathFilter = "All Period";
        }

        // Mencari asset yang sedang berkendala berdasarkan ticket yang masuk dan filter (agent & periode)
        $tickets = Ticket::where([['agent_id', 'like', '%'.$filter1],['created_at', 'like', $filter2.'%'],['ticket_for', $locationId]])->whereNotIn('status', ['deleted'])
            ->groupBy('asset_id')
            ->select('asset_id')
            ->orderBy('asset_id', 'ASC')
            ->get();

        return view('contents.asset.asset.filter.index', [
            "url"           => "",
            "title"         => $title,
            "path"          => "Asset",
            "path2"         => $title,
            "pathFilter"    => "[".$namaAgent."] - [".$pathFilter."]",
            "tickets"       => $tickets
        ]);
    }

    public function getItem($id = 0)
    {
        $ca = Category_asset::where('nama_kategori', $id)->first();
        $caId = $ca->id;
        $data = Item::where('category_asset_id', $caId)->get();
        return response()->json($data);
    }
}