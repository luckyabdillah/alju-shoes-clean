<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionDropzoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.transaction.dropzone.index', [
            'transactions' => Transaction::where('transaction_type', 'dropoff')->where('transaction_status', '!=', 'done')->orderBy('transaction_start', 'desc')->get()
        ]);
    }

    public function paymentUpdate(Transaction $transaction) {
        if ($transaction->payment_status == 'unpaid') {
            Transaction::where('id', $transaction->id)->update(['payment_status' => 'paid']);
        } else {
            Transaction::where('id', $transaction->id)->update(['payment_status' => 'unpaid']);
        }

        return redirect('/dashboard/transaction/dropzone')->with('success', "Payment: $transaction->invoice_no has been sucessfully updated");
    }

    public function statusUpdate(Transaction $transaction) {
        if ($transaction->transaction_status == 'pending') {
            Transaction::where('id', $transaction->id)->update(['transaction_status' => 'on_progress']);
        } else if ($transaction->transaction_status == 'on_progress') {
            Transaction::where('id', $transaction->id)->update(['transaction_status' => 'done']);
        } else if ($transaction->transaction_status == 'done') {
            return redirect('/dashboard/transaction/pending');
        } else {
            Transaction::where('id', $transaction->id)->update(['transaction_status' => 'pending']);
        }

        return redirect('/dashboard/transaction/dropzone')->with('success', "Status: $transaction->invoice_no has been sucessfully updated");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
