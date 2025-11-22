<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Outlet;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionExport;

class ReportController extends Controller
{
    public function index()
    {
        $month = request('month') ?? date('m');
        $year = request('year') ?? date('Y');
        $id = request('id') ?? null;

        $transactions = TransactionDetail::with('transaction.customer', 'transaction.outlet')->where('created_at', 'like', "$year-$month%");

        $outlets = Outlet::all();
        if ($id) {
            $outlet = Outlet::where('uuid', $id)->first();
            if (!$outlet) {
                return redirect('/dashboard/report')->with('failed', 'Outlet tidak tersedia');
            }
            $transactions = $transactions->whereHas('transaction', function ($query) use ($outlet) {
                $query->where('outlet_id', $outlet->id);
            });
        }

        $transactions = $transactions->latest()->get();

        return view('dashboard.report.index', compact('month', 'year', 'transactions', 'outlets', 'id'));
    }

    public function exportExcel()
    {
        $month = request('month') ?? date('m');
        $year = request('year') ?? date('Y');
        $id = request('id') ?? null;

        $transactions = TransactionDetail::with('transaction.customer', 'transaction.outlet')->where('created_at', 'like', "$year-$month%");

        $outlets = Outlet::all();
        if ($id) {
            $outlet = Outlet::where('uuid', $id)->first();
            if (!$outlet) {
                return redirect('/dashboard/report')->with('failed', 'Outlet tidak tersedia');
            }
            $transactions = $transactions->whereHas('transaction', function ($query) use ($outlet) {
                $query->where('outlet_id', $outlet->id);
            });
        }

        $transactions = $transactions->latest()->get();

        $fullDate = date('F-Y', strtotime("$year-$month-01"));
        $outletName = $id ? $outlet->outlet_name : 'All Outlet';

        return Excel::download(new TransactionExport($transactions, $fullDate, $outletName), $outletName . '-' . $fullDate . '.xlsx');
    }
}
