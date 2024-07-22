<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Category_ticket;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoriesExport;
use Illuminate\Support\Facades\Auth;

class ReportSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        // Get data User
        $userId     = Auth::user()->id;
        $userRole   = Auth::user()->role;
        $locationId = Auth::user()->location_id;
        $location   = Auth::user()->location->nama_lokasi;
        $pathFilter = ["", ""];

        $dCategories = Category_ticket::where('location_id', $locationId)->get();
        $totalAgents = Agent::where([['location_id', $locationId],['is_active', '1']])->count();
        $categories = Category_ticket::where('location_id', $locationId)->with(['sub_category_tickets.ticket_details.agent'])->get();
        $agents = Agent::where([['location_id', $locationId],['is_active', '1']])->get();

        // Persiapkan data untuk view
        $data = [];
        foreach ($categories as $category) {
            foreach ($category->sub_category_tickets as $subCategory) {
                foreach ($agents as $agent) {
                    $avgTime = $subCategory->ticket_details->where('agent_id', $agent->id)->whereIn('status', ['resolved', 'assigned'])->avg('processed_time');
                    $data[$category->nama_kategori][$subCategory->nama_sub_kategori][$agent->id] = $avgTime;
                }
                $data[$category->nama_kategori][$subCategory->nama_sub_kategori]['totalAverage'] = $subCategory->ticket_details->whereIn('status', ['resolved', 'assigned'])->avg('processed_time');
            }
        }

        $filterArray = ["", "", ""];
        
        return view('contents.report.sub_category.index', [
            "url"           => "",
            "title"         => "Sub Category Report",
            "path"          => "Report",
            "path2"         => "Sub Category",
            "filterArray"   => $filterArray,
            "pathFilter"    => $pathFilter,
            "dCategories"   => $dCategories,
            "totalAgents"   => $totalAgents,
            "agents"        => $agents,
            "data"          => $data
        ]);
    }

    public function export(Request $request)
    {
        $category = $request['category2'];
        $startDate = $request['startDate'];
        $endDate = $request['endDate'];

        $locationId = Auth::user()->location_id;
        return Excel::download(new CategoriesExport($locationId, $category, $startDate, $endDate), 'category-report-'.$category.'-'.$startDate.'-'.$endDate.'.xlsx');
    }
}