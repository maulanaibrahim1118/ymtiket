<?php

namespace App\Http\Controllers;

use App\Location;
use App\Category_ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get lokasi User
        $location_id = Auth::user()->location_id;

        // Get data Category Ticket berdasarkan Lokasi User
        if($location_id == 10 || $location_id == 359 || $location_id == 360) {
            $category_tickets = Category_ticket::where('location_id', 10)->get();
        }else {
            $category_tickets = Category_ticket::where('location_id', $location_id)->get();
        }

        return view('contents.category_ticket.index', [
            "title"             => "Ticket Category List",
            "path"              => "Ticket Category",
            "path2"             => "Ticket Category",
            "category_tickets"  => $category_tickets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.category_ticket.create', [
            "title"             => "Create Ticket Category",
            "path"              => "Ticket Category",
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
            'nama_kategori' => 'required|min:3|max:50',
            'location_id'   => 'required',
            'updated_by'    => 'required'
        ],
        
        // Create custom notification for the validation request
        [
            'nama_kategori.required'    => 'Category Name required!',
            'nama_kategori.min'         => 'Type at least 3 characters!',
            'nama_kategori.max'         => 'Type maximum 50 characters!',
            'location_id.required'      => 'Location must be selected!',
            'updated_by.required'       => 'Wajib diisi!'
        ]);

        $countCategory = Category_ticket::where([['nama_kategori', $request['nama_kategori']],['location_id', $request['location_id']]])->count();

        if($countCategory >= 1){
            return redirect('/category-tickets')->with('error', 'Category exists!');
        }

        // Simpan data Category Ticket sesuai request yang telah di validasi
        $data = array_map('strtolower', $validatedData);
        Category_ticket::create($data);

        // Get Nama Kategori Ticket untuk ditampilkan di notifikasi sukses
        $nama_kategori  = ucwords($request['nama_kategori']);

        // Redirect ke halaman Category Ticket List beserta notifikasi sukses
        return redirect('/category-tickets')->with('success', $nama_kategori.' successfully created!');
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
        // Get id Category Ticket dari request parameter
        $id = decrypt($request['id']);

        // Get data Category Ticket berdasarkan id Category Ticket
        $category_ticket = Category_ticket::where('id', $id)->first();

        return view('contents.category_ticket.edit', [
            "title"     => "Edit Ticket Category",
            "path"      => "Ticket Category",
            "path2"     => "Edit",
            "ct"        => $category_ticket
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
        // Get id Category Ticket dari request parameter
        $id = decrypt($request['id']);
        
        // Get data Category Ticket berdasarkan id Category Ticket
        $category_ticket = Category_ticket::where('id', $id)->first();
        
        // Validating data request
        $rules = [
            'nama_kategori' => 'required|min:3|max:50',
            'location_id'   => 'required',
            'updated_by'    => 'required'
        ];

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'nama_kategori.required'    => 'Category Name required!',
            'nama_kategori.min'         => 'Type at least 3 characters!',
            'nama_kategori.max'         => 'Type maximum 50 characters!',
            'location_id.required'      => 'Location must be selected!',
            'updated_by.required'       => 'Wajib diisi!'
        ]);

        $countCategory = Category_ticket::where([['nama_kategori', $request['nama_kategori']],['location_id', $request['location_id']]])->count();

        if($countCategory >= 1){
            return redirect('/category-tickets')->with('error', 'Category exists!');
        }

        // Updating data Category Ticket sesuai request yang telah di validasi
        $data = array_map('strtolower', $validatedData);
        Category_ticket::where('id', $id)->update($data);

        // Redirect ke halaman Category Ticket List beserta notifikasi sukses
        return redirect('/category-tickets')->with('success', 'Category Ticket successfully updated!');
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