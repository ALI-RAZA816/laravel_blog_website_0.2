<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyReport;

class MonthlyReportController extends Controller
{
    public function report(){

        $total = MonthlyReport::select(
            'total_post',
            'month',
            'year'
        )
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();
        return response()->json([
            'total'=>$total
        ]);

    }
}
