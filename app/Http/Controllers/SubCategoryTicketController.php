<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Location;
use App\Ticket_detail;
use App\Category_ticket;
use App\Sub_category_ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SubCategoryTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get ID Lokasi User
        $locationId = Auth::user()->location_id;
        
        // Get data Sub Category Ticket sesuai lokasi user yang ada pada tabel category ticket
        if($locationId == 10 || $locationId == 359 || $locationId == 360) {
            $subCategoryTicket = Sub_category_ticket::join('category_tickets', 'sub_category_tickets.category_ticket_id', '=', 'category_tickets.id')
                ->where('category_tickets.location_id', 10)
                ->select('sub_category_tickets.*')
                ->get();
        }else{
            $subCategoryTicket = Sub_category_ticket::join('category_tickets', 'sub_category_tickets.category_ticket_id', '=', 'category_tickets.id')
                ->where('category_tickets.location_id', $locationId)
                ->select('sub_category_tickets.*')
                ->get();
        }
            
        return view('contents.sub_category_ticket.index', [
            "title"                 => "Ticket Sub Category List",
            "path"                  => "Ticket Sub Category",
            "path2"                 => "Ticket Sub Category",
            "sub_category_tickets"  => $subCategoryTicket
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get ID Lokasi User
        $locationId = Auth::user()->location_id;
        
        // Get data untuk Select Option
        if($locationId == 10 || $locationId == 359 || $locationId == 360) {
            $categoryTickets    = Category_ticket::where('location_id', 10)->get();
        }else{
            $categoryTickets    = Category_ticket::where('location_id', $locationId)->get();
        }
        $assetChange        = ["ya", "tidak"];

        return view('contents.sub_category_ticket.create', [
            "url"               => "",
            "title"             => "Create Ticket Sub Category",
            "path"              => "Ticket Sub Category",
            "path2"             => "Create",
            "category_tickets"  => $categoryTickets,
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
            'nama_sub_kategori'     => 'required|min:3|max:50',
            'category_ticket_id'    => 'required',
            'asset_change'          => 'required',
            'updated_by'            => 'required',
            // Menambahkan aturan validasi unik dengan kondisi
            'nama_sub_kategori'     => [
                'required',
                Rule::unique('sub_category_tickets')->where(function ($query) use ($request) {
                    return $query->where([
                        ['category_ticket_id', $request->category_ticket_id],
                        ['nama_sub_kategori', $request->nama_sub_kategori]
                    ]);
                })->ignore($request->id),
            ],
        ],

        // Create custom notification for the validation request
        [
            'nama_sub_kategori.required'    => 'Sub Category Name required!',
            'nama_sub_kategori.min'         => 'Type at least 3 characters!',
            'nama_sub_kategori.max'         => 'Type maximum 50 characters!',
            'nama_sub_kategori.unique'      => 'Already exists for the same category!',
            'category_ticket_id.required'   => 'Ticket Category must be selected!',
            'asset_change.required'         => 'Asset Change must be selected!',
            'updated_by.required'           => 'Harap diisi!'
        ]);

        // Mengganti inputan nama sub kategori ke huruf kecil semua
        $nama_sub_kategori = strtolower($request['nama_sub_kategori']);

        // Saving data to sub_category_ticket table
        $sct                        = new Sub_category_ticket;
        $sct->nama_sub_kategori     = ucwords($nama_sub_kategori);
        $sct->category_ticket_id    = $request['category_ticket_id'];
        $sct->asset_change          = $request['asset_change'];
        $sct->updated_by            = $request['updated_by'];
        $sct->save();

        // Redirect ke halaman Sub Category Ticket List beserta notifikasi sukses
        return redirect('/category-sub-tickets')->with('success', ucwords($nama_sub_kategori).' successfully created!');
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
        // Get ID Lokasi User
        $locationId = Auth::user()->location_id;

        // Get id Sub Category Ticket dari request parameter
        $id = decrypt($request['id']);

        // Get data Sub Category Ticket berdasarkan id Sub Category Ticket
        $subCategoryTicket = Sub_category_ticket::where('id', $id)->first();

        // Get data untuk select option
        if($locationId == 10 || $locationId == 359 || $locationId == 360) {
            $categoryTickets    = Category_ticket::where('location_id', 10)->get();
        }else{
            $categoryTickets    = Category_ticket::where('location_id', $locationId)->get();
        }
        $assetChange        = ["ya", "tidak"];

        return view('contents.sub_category_ticket.edit', [
            "title"             => "Edit Ticket Sub Category",
            "path"              => "Ticket Sub Category",
            "path2"             => "Edit",
            "sct"               => $subCategoryTicket,
            "category_tickets"  => $categoryTickets,
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
    public function update(Request $request)
    {
        // Get id Sub Category Ticket dari request parameter
        $id = decrypt($request['id']);

        // Get data Sub Category Ticket berdasarkan id Sub Category Ticket
        $subCategoryTicket = Sub_category_ticket::where('id', $id)->first();

        // Validating data request
        $validatedData = $request->validate([
            'nama_sub_kategori'     => 'required|min:3|max:50',
            'category_ticket_id'    => 'required',
            'asset_change'          => 'required',
            'updated_by'            => 'required',
            // Menambahkan aturan validasi unik dengan kondisi
            'nama_sub_kategori'     => [
                'required',
                Rule::unique('sub_category_tickets')->where(function ($query) use ($request, $id) {
                    return $query->where([
                        ['category_ticket_id', $request->category_ticket_id],
                        ['nama_sub_kategori', $request->nama_sub_kategori]
                    ])->where('id', '!=', $id); // Mengabaikan entri saat ini saat memeriksa unik
                }),
            ],
        ],

        // Create custom notification for the validation request
        [
            'nama_sub_kategori.required'    => 'Sub Category Name required!',
            'nama_sub_kategori.min'         => 'Type at least 3 characters!',
            'nama_sub_kategori.max'         => 'Type maximum 50 characters!',
            'nama_sub_kategori.unique'      => 'Already exists for the same category!',
            'category_ticket_id.required'   => 'Ticket Category must be selected!',
            'asset_change.required'         => 'Asset Change must be selected!',
            'updated_by.required'           => 'Harap diisi!'
        ]);
        
        // Mengganti inputan nama sub kategori ke huruf kecil semua
        $namaSubKategori = strtolower($request['nama_sub_kategori']);

        // Updating data Sub Category Ticket sesuai request
        Sub_category_ticket::where('id', $id)->update([
            'nama_sub_kategori'     => ucwords($namaSubKategori),
            'category_ticket_id'    => $request['category_ticket_id'],
            'asset_change'          => $request['asset_change'],
            'updated_by'            => $request['updated_by']
        ]);

        // Redirect ke halaman Sub Category Ticket List beserta notifikasi sukses
        return redirect('/category-sub-tickets')->with('success', 'Ticket Sub Category successfully updated!');
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

    // Menampilkan Kendala yang ditangani oleh agent (Sub Category Ticket = Kendala)
    public function kendalaDashboard(Request $request)
    {
        // Get ID Lokasi User
        $locationId = Auth::user()->location_id;

        $status     = $request->input('status');
        $agent      = $request->input('filter1');
        $periode    = $request->input('filter2');
        
        $title      = "Sub Category";

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

        // Mencari Kendala berdasarkan lokasi user pada detail ticket
        $data = Ticket_detail::join('tickets', 'ticket_details.ticket_id', '=', 'tickets.id')
            ->where([['tickets.ticket_for', $locationId],['ticket_details.agent_id', 'like', '%'.$filter1],['ticket_details.created_at', 'like', $filter2.'%']])
            ->select('ticket_details.sub_category_ticket_id')
            ->groupBy('ticket_details.sub_category_ticket_id')
            ->get();

        return view('contents.sub_category_ticket.filter.index', [
            "title"         => $title,
            "path"          => "Ticket Sub Category",
            "path2"         => $title,
            "pathFilter"    => "[".$namaAgent."] - [".$pathFilter."]",
            "data"          => $data
        ]);
    }
}