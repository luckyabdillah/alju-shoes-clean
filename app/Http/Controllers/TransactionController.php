<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Treatment;
use App\Models\TreatmentDetail;
use App\Models\Customer;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexDropzone()
    {
        $pending_transactions = Transaction::with(['customer', 'outlet', 'transaction_details'])->where('transaction_type', 'dropzone')->where('transaction_status', 'pending')->orderBy('transaction_start');
        $on_progress_transactions = Transaction::with(['customer', 'outlet', 'transaction_details'])->where('transaction_type', 'dropzone')->where('transaction_status', 'on_progress')->orderBy('transaction_start');

        if (auth()->user()->role == 'operation') {
            $pending_transactions->where('outlet_id', auth()->user()->outlet_id);
            $on_progress_transactions->where('outlet_id', auth()->user()->outlet_id);
        } else {
            if (request('id')) {
                $outlet = Outlet::where('uuid', request('id'))->first();
                if (!$outlet) {
                    return redirect('/dashboard/transaction/dropzone')->with('failed', 'Outlet tidak tersedia');
                }
                $pending_transactions->where('outlet_id', $outlet->id);
                $on_progress_transactions->where('outlet_id', $outlet->id);
            }
        }

        return view('dashboard.transaction.dropzone.index', [
            'pending_transactions' => $pending_transactions->get(),
            'on_progress_transactions' => $on_progress_transactions->get(),
            'outlets' => Outlet::where('id', '!=', 1)->get(),
        ]);
    }

    public function indexPickupDelivery()
    {
        $pickup = Transaction::with(['customer', 'outlet', 'transaction_details'])->where('transaction_type', 'pickup-delivery')->orderBy('transaction_start')->where('transaction_status', 'pending')->get();
        $delivery = Transaction::with(['customer', 'outlet', 'transaction_details'])->where('transaction_type', 'pickup-delivery')->orderBy('transaction_start')->where('transaction_status', 'on_progress')->get();
        
        return view('dashboard.transaction.pickup-delivery.index', [
            'pickup_transactions' => $pickup,
            'delivery_transactions' => $delivery
        ]);
    }

    public function paymentUpdate(Transaction $transaction)
    {
        $type = $transaction->transaction_type;

        if ($transaction->payment_status == 'unpaid') {
            Transaction::where('id', $transaction->id)->update(['payment_status' => 'paid']);
        } else {
            Transaction::where('id', $transaction->id)->update(['payment_status' => 'unpaid']);
        }

        return redirect("/dashboard/transaction/$type")->with('success', "Payment: $transaction->invoice_no has been sucessfully updated");
    }

    public function statusUpdate(Request $request, Transaction $transaction)
    {
        $type = $transaction->transaction_type;

        if ($transaction->transaction_status == 'pending') {
            
            // $customer = $transaction->customer;
            // $details = $transaction->transaction_details;
            
            // $msg = "*FAKTUR ELEKTRONIK TRANSAKSI*\n";
            // $msg .= "Alju Shoes Clean\n";
            // $msg .= "Jl Bagas 12, Johor Bahru, Jakarta Pusat\n";
            // $msg .= "https://wa.me/6281296824754\n\n";
            // $msg .= "Nomor Invoice:\n";
            // $msg .= "*$transaction->invoice_no*\n\n";
            // $msg .= "Pelanggan Yth:\n";
            // $msg .= "$customer->name\n\n";
            // $msg .= "Terima: " . date('d M Y, H:i', strtotime($transaction->transaction_start)) . "\n";
            // $msg .= "Selesai: " . date('d M Y, H:i', strtotime($transaction->transaction_end)) . "\n\n";
            // $msg .= "==========================\n\n";
            // $msg .= "Detail Pesanan:\n";

            // $i = 1;
            // foreach ($details as $detail) {
            //     $msg .= $i . ". ";
            //     $msg .= $detail->item_name;
            //     $msg .= ($detail->size) ? ", $detail->size" : "";
            //     $msg .= " ~ IDR " . number_format($detail->amount, 0, '.', ',') . "\n";
            //     $i++;
            // }

            // $msg .= "\n";
            // $msg .= "Detail Biaya:\n";
            // $msg .= "Biaya Tagihan: IDR " . number_format($transaction->cost, 0, '.', ',');
            // $msg .= "\nBiaya lainnya: IDR ";
            // $msg .= ($transaction->other_cost) ? number_format($detail->other_cost, 0, '.', ',') : '0';
            // $msg .= "\n";
            // $msg .= "\nGrand Total: IDR " . number_format($transaction->cost, 0, '.', ',') . "\n\n";
            // $msg .= "Pembayaran: " . strtoupper($transaction->payment_method);
            // $msg .= "\n\nStatus: ";
            // $msg .= ($transaction->payment_status == 'paid') ? 'Lunas' : 'Belum Lunas';
            // $msg .= "\n\n==========================\n\n";
            // $msg .= "Syarat dan Ketentuan:\n";
            // $msg .= "*PERHATIAN*\n";
            // $msg .= "1. Pengambilan barang oleh konsumen harap disertai nota.\n";
            // $msg .= "2. Pengambilan barang baik di counter maupun diantarkan (delivery) wajib diperiksa oleh konsumen. Kehilangan/kerusakan setelah barang diterima konsumen diluar tanggung jawab Alju Shoes Clean.\n";
            // $msg .= "3. Barang yang tidak diambli selama 1 bulan terhitung sejak tanggal masuk barang, maka kehilangan dan kerusakan diluar tanggung jawab Alju Shoes Clean.\n";
            // $msg .= "4. Setiap konsumen dianggap setuju dengan peraturan diatas.\n\n";
            // $msg .= "==========================\n\n";
            // $msg .= "https://aljushoesclean.com/invoice/$transaction->uuid\n\n";
            // $msg .= "Terimakasih telah mencuci di Alju Shoes Clean :)";

            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => 'https://api.fonnte.com/send',
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //     CURLOPT_POSTFIELDS => array(
            //     'target' => $customer->whatsapp_number,
            //     'message' => $msg,
            //     'countryCode' => '62', //optional
            //     ),
            //     CURLOPT_HTTPHEADER => array(
            //         'Authorization: d3W-pRaBFS@J1gKvyDKT' //change TOKEN to your actual token
            //     ),
            // ));

            // $response = curl_exec($curl);

            // if (curl_errno($curl)) {
            //     $error_msg = curl_error($curl);
            // }
            // curl_close($curl);

            // if (isset($error_msg)) {
            //     echo $error_msg;
            // }

            Transaction::where('id', $transaction->id)->update(['transaction_status' => 'on_progress']);

        } else if ($transaction->transaction_status == 'on_progress') {

            $validatedData = $request->validate([
                'proof_of_handover' => 'file|max:2048|mimes:jpg,png,jpeg,svg'
            ]);

            $validatedData['transaction_status'] = 'done';

            if ($request->file('proof_of_handover')) {
                $validatedData['proof_of_handover'] = $request->file('proof_of_handover')->store('transaction');
            }

            Transaction::where('id', $transaction->id)->update($validatedData);

        } else {
            return redirect("/dashboard/transaction/$type");
        }

        return redirect("/dashboard/transaction/$type")->with('success', "$transaction->invoice_no status has been sucessfully updated");
    }

    public function index()
    {
        $month = request('month') ?? date('m');
        $year = request('year') ?? date('Y');
        $id = request('id') ?? null;
        
        $transactions = Transaction::where('created_at', 'like', "$year-$month%");
        $outlets = Outlet::all();

        if ($id) {
            $outlet = Outlet::where('uuid', $id)->first();
            if (!$outlet) {
                return redirect('/dashboard/transaction')->with('failed', 'Outlet tidak tersedia');
            }
            $transactions = $transactions->where('outlet_id', $outlet->id);
        }
        
        $transactions = $transactions->latest()->get();

        return view('dashboard.transaction.index', compact('transactions', 'outlets', 'month', 'year', 'id'));
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
        $month = request('month') ?? date('m');
        $year = request('year') ?? date('Y');
        $id = request('id') ?? null;

        $details = TransactionDetail::where('transaction_id', $transaction->id)->get();

        return view('dashboard.transaction.edit', compact('transaction', 'details', 'month', 'year', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request['payment_status'] = isset($request['payment_status']) ? 'paid' : 'unpaid';
        $validatedData = $request->validate([
            'payment_status' => 'required|in:paid,unpaid',
            'shipping_cost' => 'required|numeric|min:0',
            'treatment_discount' => 'required|numeric|min:0',
            'delivery_discount' => 'required|numeric|min:0',
            'transaction_end' => 'required',
            'details.*.id' => 'required|numeric',
            'details.*.cost' => 'required|numeric|min:0',
            'details.*.partner_price' => 'required|numeric|min:0',
            'details.*.treatment_name' => 'required|max:150',
        ]);

        $updatedSubtotalCost = 0;
        foreach ($validatedData['details'] as $detail) {
            $transactionDetail = TransactionDetail::firstWhere('id', $detail['id']);
            $transactionDetail->update([
                'cost' => $detail['cost'],
                'partner_price' => $detail['partner_price'],
                'treatment_name' => $detail['treatment_name'],
            ]);

            $updatedSubtotalCost += $detail['cost'];
        }

        $updatedTotalCost = $updatedSubtotalCost + $validatedData['shipping_cost'] - $validatedData['treatment_discount'] - $validatedData['delivery_discount'];

        $transaction->update([
            'cost' => $updatedSubtotalCost,
            'shipping_cost' => $validatedData['shipping_cost'],
            'treatment_discount' => $validatedData['treatment_discount'],
            'delivery_discount' => $validatedData['delivery_discount'],
            'total_cost' => $updatedTotalCost,
            'payment_status' => $validatedData['payment_status'],
            'transaction_end' => date('Y-m-d H:i:s', strtotime($validatedData['transaction_end'])),
        ]);

        return redirect("/dashboard/transaction/$transaction->uuid/edit")->with('success', 'Transaksi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        TransactionDetail::where('transaction_id', $transaction->id)->delete();
        Transaction::destroy($transaction->id);

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus');
    }
}
