<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sub_category_ticket;
use App\Category_ticket;

class SubCategoryTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contents.sub_category_ticket.index', [
            "url"                   => "",
            "title"                 => "Sub Category Ticket List",
            "path"                  => "Sub Category Ticket",
            "sub_category_tickets"  => Sub_category_ticket::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.sub_category_ticket.create', [
            "url"               => "",
            "title"             => "Create Sub Category Ticket",
            "path"              => "Sub Category Ticket",
            "path2"             => "Tambah",
            "category_tickets"  => Category_ticket::all()
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
            'nama_sub_kategori'     => 'required|min:5|max:50|unique:sub_category_tickets',
            'category_ticket_id'    => 'required',
            'updated_by'            => 'required'
        ],
        // Create custom notification for the validation request
        [
            'nama_sub_kategori.required'    => 'Nama Kategori Ticket harus diisi!',
            'nama_sub_kategori.min'         => 'Ketik minimal 5 digit!',
            'nama_sub_kategori.max'         => 'Ketik maksimal 50 digit!',
            'unique'                        => 'Nama Kategori Ticket sudah ada!',
            'category_ticket_id.required'   => 'Kategori Ticket harus dipilih!',
            'updated_by.required'           => 'Wajib diisi!'
        ]);

        // Saving data to sub_category_ticket table
        $sct                        = new Sub_category_ticket;
        $sct->nama_sub_kategori     = ucwords($request['nama_sub_kategori']);
        $sct->category_ticket_id    = $request['category_ticket_id'];
        $sct->updated_by            = $request['updated_by'];
        $sct->save();

        // Redirect to the Category Asset view if create data succeded
        $nama_sub_kategori = $request['nama_sub_kategori'];
        return redirect('/category-sub-tickets')->with('success', ucwords($nama_sub_kategori).' telah ditambahkan!');
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
    public function edit(Sub_category_ticket $category_sub_ticket)
    {
        return view('contents.sub_category_ticket.edit', [
            "title"             => "Edit Sub Category Ticket",
            "path"              => "Sub Category Ticket",
            "path2"             => "Edit",
            "sct"               => $category_sub_ticket,
            "category_tickets"  => Category_ticket::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sub_category_ticket $category_sub_ticket)
    {
        // Validating data request
        $validatedData = $request->validate([
            'nama_sub_kategori'     => 'required|min:5|max:50|unique:sub_category_tickets',
            'category_ticket_id'    => 'required',
            'updated_by'            => 'required'
        ],
        // Create custom notification for the validation request
        [
            'nama_sub_kategori.required'    => 'Nama Sub Kategori Ticket harus diisi!',
            'nama_sub_kategori.min'         => 'Ketik minimal 5 digit!',
            'nama_sub_kategori.max'         => 'Ketik maksimal 50 digit!',
            'unique'                        => 'Nama Kategori Ticket sudah ada!',
            'category_ticket_id.required'   => 'Kategori Ticket harus dipilih!',
            'updated_by.required'           => 'Wajib diisi!'
        ]);
        
        Sub_category_ticket::where('id', $category_sub_ticket->id)->update([
            'nama_sub_kategori'     => ucwords($request['nama_sub_kategori']),
            'category_ticket_id'    => $request['category_ticket_id'],
            'updated_by'            => $request['updated_by']
        ]);

        return redirect('/category-sub-tickets')->with('success', 'Data Sub Category Ticket telah diubah!');
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
