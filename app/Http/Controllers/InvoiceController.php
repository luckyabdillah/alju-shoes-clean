<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Transaction $invoice)
    {
        $transaction = $invoice->load('customer', 'detail_transactions');

        return view('transactions.invoice', compact('transaction'));
    }
}
