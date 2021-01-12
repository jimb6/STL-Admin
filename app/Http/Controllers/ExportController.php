<?php

namespace App\Http\Controllers;

use App\Exports\GeneralReports;
use App\Exports\UsersExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    function export()
    {
        $filename = Carbon::now()->format('Ymdhms') . '-users.xlsx';
        return Excel::download(new UsersExport, $filename);
    }


    function exportBetEntries(Request $request)
    {
        $filename = Carbon::now()->format('Ymdhms') . '-bet-entries.xlsx';
        return Excel::download(new GeneralReports($request), $filename);
    }
}
