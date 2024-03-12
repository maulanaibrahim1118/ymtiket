<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category_ticket;
use App\Location;

class CategoryTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $location_id    = decrypt($id);
        $data           = Category_ticket::where('location_id', $location_id)->get();

        return view('contents.category_ticket.index', [
            "url"               => "",
            "title"             => "Category Ticket List",
            "path"              => "Category Ticket",
            "path2"             => "Category Ticket",
            "category_tickets"  => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $locations  = Location::where('id', 10)->orWhere('id', 12)->orWhere('id', 83)->orWhere('id', 84)->get();

        return view('contents.category_ticket.create', [
            "url"               => "",
            "title"             => "Create Category Ticket",
            "path"              => "Category Ticket",
            "path2"             => "Tambah",
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
            'nama_kategori' => 'required|min:5|max:50|unique:category_tickets',
            'location_id'   => 'required',
            'updated_by'    => 'required'
        ],
        // Create custom notification for the validation request
        [
            'nama_kategori.required'    => 'Nama Kategori Ticket harus diisi!',
            'nama_kategori.min'         => 'Ketik minimal 5 digit!',
            'nama_kategori.max'         => 'Ketik maksimal 50 digit!',
            'unique'                    => 'Nama Kategori Ticket sudah ada!',
            'location_id.required'      => 'Lokasi harus dipilih!',
            'updated_by.required'       => 'Wajib diisi!'
        ]);
        // Saving data to category_asset table
        $data = array_map('strtolower', $validatedData);
        Category_ticket::create($data);

        // Redirect to the Category Asset view if create data succeded
        $nama_kategori  = ucwords($request['nama_kategori']);
        $url            = $request['url'];
        return redirect($url)->with('success', $nama_kategori.' telah ditambahkan!');
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
    public function edit($id = 0, Category_ticket $category_ticket)
    {
        $locations  = Location::where('id', 10)->orWhere('id', 12)->orWhere('id', 83)->orWhere('id', 84)->get();

        return view('contents.category_ticket.edit', [
            "title"     => "Edit Category Ticket",
            "path"      => "Category Ticket",
            "path2"     => "Edit",
            "ct"        => $category_ticket,
            "locations" => $locations
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category_ticket $category_ticket)
    {
        $rules = [
            'location_id'   => 'required',
            'updated_by'    => 'required'
        ];

        if($request->nama_kategori != $category_ticket->nama_kategori){
            $rules['nama_kategori'] = 'required|min:5|max:50|unique:category_tickets';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'nama_kategori.required'    => 'Nama Kategori Ticket harus diisi!',
            'nama_kategori.min'         => 'Ketik minimal 5 digit!',
            'nama_kategori.max'         => 'Ketik maksimal 50 digit!',
            'unique'                    => 'Nama Kategori Ticket sudah ada!',
            'location_id.required'      => 'Lokasi harus dipilih!',
            'updated_by.required'       => 'Wajib diisi!'
        ]);
        $data = array_map('strtolower', $validatedData);
        Category_ticket::where('id', $category_ticket->id)->update($data);

        $url    = $request['url'];
        return redirect($url)->with('success', 'Data Category Ticket telah diubah!');
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
