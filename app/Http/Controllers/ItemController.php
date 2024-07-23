<?php

namespace App\Http\Controllers;

use App\Item;
use App\Category_asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class ItemController extends Controller
{
    public function index()
    {
        // Get data Item
        $items = Item::orderBy('name', 'ASC')->get();

        return view('contents.asset.item.index', [
            "title" => "Item List",
            "path"  => "Item",
            "path2" => "Item",
            "items" => $items
        ]);
    }

    public function create()
    {
        $category_assets = Category_asset::orderBy('nama_kategori', 'ASC')->get();

        return view('contents.asset.item.create', [
            "title"             => "Create Item",
            "path"              => "Item",
            "path2"             => "Create",
            "category_assets"   => $category_assets
        ]);
    }

    public function store(Request $request)
    {
        $userName = Auth::user()->nama;

        // Validating data request
        $validatedData = $request->validate([
            'name'              => 'required|max:50|unique:items',
            'uom'               => 'required|max:20',
            'category_asset_id' => 'required',
        ],
        // Create custom notification for the validation request
        [
            'name.required'                 => 'Item Name required!',
            'name.max'                      => 'Type maximum 50 characters!',
            'unique'                        => 'Item Name already exists!',
            'uom.required'                  => 'UOM required!',
            'uom.max'                       => 'Type maximum 20 characters!',
            'category_asset_id.required'    => 'Asset Category required!',
        ]);

        // Saving data to Area table
        $item                       = new Item;
        $item->name                 = strtolower($request['name']);
        $item->uom                  = $request['uom'];
        $item->category_asset_id    = $request['category_asset_id'];
        $item->updated_by           = $userName;
        $item->save();

        // Redirect to the area view if create data succeded
        $itemName = $request['name'];
        return redirect('/asset-items')->with('success', ucwords($itemName).' successfully created!');
    }

    public function edit(Request $request)
    {
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);

        // Get data Asset berdasarkan id Asset
        $item = Item::where('id', $id)->first();

        // Get data Category Asset untuk ditampilkan di select option view edit
        $category_assets = Category_asset::orderBy('nama_kategori', 'ASC')->get();

        return view('contents.asset.item.edit', [
            "title"             => "Edit Item",
            "path"              => "Item",
            "path2"             => "Edit",
            "category_assets"   => $category_assets,
            "item"              => $item
        ]);
    }

    public function update(Request $request)
    {
        // Get id Asset dari request parameter
        $id = decrypt($request['id']);
        
        // Get data Asset berdasarkan id Asset
        $asset = Item::where('id', $id)->first();

        // Validating data request
        $rules = [
            'uom'               => 'required|max:20',
            'category_asset_id' => 'required',
            'updated_by'        => 'required',
        ];

        if($request->no_asset != $asset->no_asset){
            $rules['name'] = 'required|max:50|unique:items';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'name.required'                 => 'Item Name required!',
            'name.max'                      => 'Type maximum 50 characters!',
            'unique'                        => 'Item Name already exists!',
            'uom.required'                  => 'UOM required!',
            'uom.max'                       => 'Type maximum 20 characters!',
            'category_asset_id.required'    => 'Asset Category required!',
            'updated_by.required'           => 'Required!',
        ]);

        // Updating data Asset sesuai request yang telah di validasi
        Item::where('id', $id)->update($validatedData);
        
        // Redirect ke halaman asset list beserta notifikasi sukses
        return redirect('/asset-items')->with('success', 'Item successfully updated!');
    }
}