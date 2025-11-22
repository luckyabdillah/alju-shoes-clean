<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $undoneDropzoneTransactions = Transaction::where('transaction_status', '!=', 'done')->where('transaction_type', 'dropzone')->count();
        $undonePDTransactions = Transaction::where('transaction_status', '!=', 'done')->where('transaction_type', 'pickup-delivery')->count();

        return view('dashboard.index', compact('undoneDropzoneTransactions', 'undonePDTransactions'));
    }
}
