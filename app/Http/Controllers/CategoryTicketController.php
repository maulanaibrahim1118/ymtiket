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
    public function index()
    {
        return view('contents.category_ticket.index', [
            "url"               => "",
            "title"             => "Category Ticket List",
            "path"              => "Category Ticket",
            "category_tickets"  => Category_ticket::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        Category_ticket::create($validatedData);

        // Redirect to the Category Asset view if create data succeded
        $nama_kategori = $request['nama_kategori'];
        return redirect('/category-tickets')->with('success', ucwords($nama_kategori).' telah ditambahkan!');
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
    public function edit(Category_ticket $category_ticket)
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
        Category_ticket::where('id', $category_ticket->id)->update($validatedData);

        return redirect('/category-tickets')->with('success', 'Data Category Ticket telah diubah!');
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
