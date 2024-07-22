<?php

namespace App\Http\Controllers;

use App\Wilayah;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportLocationController extends Controller
{
    public function index(Request $request)
    {
        // Get data User
        $userId     = Auth::user()->id;
        $userRole   = Auth::user()->role;
        $locationId = Auth::user()->location_id;
        $pathFilter = ["", ""];

        $wilayahs = Wilayah::all();
        
        $locations = Location::withCount(['tickets', 'user'])
            ->with(['tickets' => function($query) use ($locationId) { 
                $query->where('ticket_for', $locationId)->whereNotIn('status', ['deleted'])->with('ticket_detail');
            }, 'user']) 
            ->orderBy('nama_lokasi', 'ASC') ->get();

        $locations = $locations->map(function($location) { 
            // Report 1 
            $location->permintaan = $location->tickets->filter(function($ticket) {
                    return isset($ticket->ticket_detail) && $ticket->ticket_detail->jenis_ticket == "permintaan";  
                })->count();
            $location->kendala = $location->tickets->filter(function($ticket) {
                    return isset($ticket->ticket_detail) && $ticket->ticket_detail->jenis_ticket == "kendala";
                })->count();
            $location->total = $location->permintaan+$location->kendala;

            return $location;
        });

        $filterArray = ["", "", ""];
        
        return view('contents.report.location.index', [
            "url"           => "",
            "title"         => "Store & Division Report",
            "path"          => "Report",
            "path2"         => "Store & Division",
            "filterArray"   => $filterArray,
            "pathFilter"    => $pathFilter,
            "locations"     => $locations,
            "wilayahs"      => $wilayahs,
        ]);
    }

    public function detail(Request $request)
    {
        // Get data User
        $userId     = Auth::user()->id;
        $userRole   = Auth::user()->role;
        $locationId = Auth::user()->location_id;
        $pathFilter = ["", ""];

        $wilayahs = Wilayah::all();
        
        $locations = Location::withCount(['tickets', 'user'])
            ->with(['tickets' => function($query) use ($locationId) { 
                $query->where('ticket_for', $locationId)->whereNotIn('status', ['deleted'])->with('ticket_detail');
            }, 'user']) 
            ->orderBy('nama_lokasi', 'ASC') ->get();

        $locations = $locations->map(function($location) { 
            // Report 1 
            $location->permintaan = $location->tickets->filter(function($ticket) {
                    return $ticket->ticket_detail->jenis_ticket == "permintaan"; 
                })->count();
            $location->kendala = $location->tickets->filter(function($ticket) {
                    return $ticket->ticket_detail->jenis_ticket == "kendala";
                })->count();
            $location->total = $location->permintaan+$location->kendala;

            return $location;
        });

        $filterArray = ["", "", ""];
        
        return view('contents.report.location.index', [
            "url"           => "",
            "title"         => "Report Store & Division",
            "path"          => "Report",
            "path2"         => "Store & Division",
            "filterArray"   => $filterArray,
            "pathFilter"    => $pathFilter,
            "locations"     => $locations,
            "wilayahs"      => $wilayahs,
        ]);
    }

    public function export(Request $request)
    {
        // $category = $request['category2'];
        // $startDate = $request['startDate'];
        // $endDate = $request['endDate'];

        // $locationId = Auth::user()->location_id;
        // return Excel::download(new CategoriesExport($locationId, $category, $startDate, $endDate), 'category-report-'.$category.'-'.$startDate.'-'.$endDate.'.xlsx');
    }
}