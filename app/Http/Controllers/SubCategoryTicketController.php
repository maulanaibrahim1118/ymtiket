<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sub_category_ticket;
use App\Category_ticket;

class SubCategoryTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $locationId = decrypt($id);
        $data   = Sub_category_ticket::join('category_tickets', 'sub_category_tickets.category_ticket_id', '=', 'category_tickets.id')
            ->where('category_tickets.location_id', $locationId)
            ->select(
                'sub_category_tickets.*',
                DB::raw('(SELECT AVG(processed_time) FROM ticket_details WHERE ticket_details.sub_category_ticket_id = sub_category_tickets.id) as avg')
            )
            ->get();
            
        return view('contents.sub_category_ticket.index', [
            "url"                   => "",
            "title"                 => "Sub Category Ticket List",
            "path"                  => "Sub Category Ticket",
            "path2"                 => "Sub Category Ticket",
            "sub_category_tickets"  => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $locationId     = decrypt($id);
        $data           = Category_ticket::where('location_id', $locationId)->get();
        $assetChange    = ["ya", "tidak"];

        return view('contents.sub_category_ticket.create', [
            "url"               => "",
            "title"             => "Create Sub Category Ticket",
            "path"              => "Sub Category Ticket",
            "path2"             => "Tambah",
            "category_tickets"  => $data,
            "assetChange"       => $assetChange
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
            'nama_sub_kategori'     => 'required|min:3|max:50|unique:sub_category_tickets',
            'category_ticket_id'    => 'required',
            'asset_change'          => 'required',
            'updated_by'            => 'required'
        ],
        // Create custom notification for the validation request
        [
            'nama_sub_kategori.required'    => 'Nama Kategori Ticket harus diisi!',
            'nama_sub_kategori.min'         => 'Ketik minimal 3 digit!',
            'nama_sub_kategori.max'         => 'Ketik maksimal 50 digit!',
            'unique'                        => 'Nama Kategori Ticket sudah ada!',
            'category_ticket_id.required'   => 'Kategori Ticket harus dipilih!',
            'asset_change.required'         => 'Asset Change harus dipilih!',
            'updated_by.required'           => 'Wajib diisi!'
        ]);

        $nama_sub_kategori = strtolower($request['nama_sub_kategori']);

        // Saving data to sub_category_ticket table
        $sct                        = new Sub_category_ticket;
        $sct->nama_sub_kategori     = ucwords($nama_sub_kategori);
        $sct->category_ticket_id    = $request['category_ticket_id'];
        $sct->asset_change          = $request['asset_change'];
        $sct->updated_by            = $request['updated_by'];
        $sct->save();

        // Redirect to the Category Asset view if create data succeded
        $url = $request['url'];
        return redirect($url)->with('success', ucwords($nama_sub_kategori).' telah ditambahkan!');
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
    public function edit( $id = 0, Sub_category_ticket $category_sub_ticket)
    {
        $locationId     = decrypt($id);
        $data           = Category_ticket::where('location_id', $locationId)->get();
        $assetChange    = ["ya", "tidak"];

        return view('contents.sub_category_ticket.edit', [
            "title"             => "Edit Sub Category Ticket",
            "path"              => "Sub Category Ticket",
            "path2"             => "Edit",
            "sct"               => $category_sub_ticket,
            "category_tickets"  => $data,
            "assetChange"       => $assetChange
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
        $rules = [
            'category_ticket_id'    => 'required',
            'asset_change'          => 'required',
            'updated_by'            => 'required'
        ];

        if($request->nama_sub_kategori != $category_sub_ticket->nama_sub_kategori){
            $rules['nama_sub_kategori'] = 'required|min:5|max:50|unique:sub_category_tickets';
        }

        // Create custom notification for the validation request
        $validatedData = $request->validate($rules,
        [
            'nama_sub_kategori.required'    => 'Nama Sub Kategori Ticket harus diisi!',
            'nama_sub_kategori.min'         => 'Ketik minimal 3 digit!',
            'nama_sub_kategori.max'         => 'Ketik maksimal 50 digit!',
            'unique'                        => 'Nama Kategori Ticket sudah ada!',
            'category_ticket_id.required'   => 'Kategori Ticket harus dipilih!',
            'asset_change.required'         => 'Asset Change harus dipilih!',
            'updated_by.required'           => 'Wajib diisi!'
        ]);
        
        $nama_sub_kategori = strtolower($request['nama_sub_kategori']);

        Sub_category_ticket::where('id', $category_sub_ticket->id)->update([
            'nama_sub_kategori'     => ucwords($request['nama_sub_kategori']),
            'category_ticket_id'    => $request['category_ticket_id'],
            'asset_change'          => $request['asset_change'],
            'updated_by'            => $request['updated_by']
        ]);

        $url    = $request['url'];
        return redirect($url)->with('success', 'Data Sub Category Ticket telah diubah!');
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
