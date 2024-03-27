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
            'transactions' => Transaction::with(['customer', 'outlet', 'detail_transactions'])->where('transaction_type', 'dropoff')->where('transaction_status', '!=', 'done')->orderBy('transaction_start', 'desc')->get()
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

    public function statusUpdate(Request $request, Transaction $transaction) {
        if ($transaction->transaction_status == 'pending') {
            
            $customer = $transaction->customer;
            $details = $transaction->detail_transactions;
            // dd($customer);
            // dd($details);
            $msg = "*FAKTUR ELEKTRONIK TRANSAKSI*\n";
            $msg .= "Alju Shoes Clean\n";
            $msg .= "Jl Bagas 12, Johor Bahru, Jakarta Pusat\n";
            $msg .= "https://wa.me/6281296824754\n\n";
            $msg .= "Nomor Invoice:\n";
            $msg .= "*$transaction->invoice_no*\n\n";
            $msg .= "Pelanggan Yth:\n";
            $msg .= "$customer->name\n\n";
            $msg .= "Terima: " . date_format(date_create($transaction->transaction_start), 'd M Y, H:i') . "\n";
            $msg .= "Selesai: " . date_format(date_create($transaction->transaction_end), 'd M Y, H:i') . "\n\n";
            $msg .= "==========================\n\n";
            $msg .= "Detail Pesanan:\n";
            $i = 1;
            foreach ($details as $detail) {
                $msg .= $i . ". ";
                $msg .= $detail->item_name;
                $msg .= ($detail->size) ? ", $detail->size" : "";
                $msg .= " ~ IDR " . number_format($detail->amount, 0, '.', ',') . "\n";
                $i++;
            }
            $msg .= "\n";
            $msg .= "Detail Biaya:\n";
            $msg .= "Biaya Tagihan: IDR " . number_format($transaction->cost, 0, '.', ',');
            $msg .= "\nBiaya lainnya: IDR ";
            $msg .= ($transaction->other_cost) ? number_format($detail->other_cost, 0, '.', ',') : '0';
            $msg .= "\n";
            $msg .= "\nGrand Total: IDR " . number_format($transaction->cost, 0, '.', ',') . "\n\n";
            $msg .= "Pembayaran: " . strtoupper($transaction->payment_method);
            $msg .= "\n\nStatus: ";
            $msg .= ($transaction->payment_status == 'paid') ? 'Lunas' : 'Belum Lunas';
            // $msg .= "\n";
            $msg .= "\n\n==========================\n\n";
            $msg .= "Syarat dan Ketentuan:\n";
            $msg .= "*PERHATIAN*\n";
            $msg .= "1. Pengambilan barang oleh konsumen harap disertai nota.\n";
            $msg .= "2. Pengambilan barang baik di counter maupun diantarkan (delivery) wajib diperiksa oleh konsumen. Kehilangan/kerusakan setelah barang diterima konsumen diluar tanggung jawab Alju Shoes Clean.\n";
            $msg .= "3. Barang yang tidak diambli selama 1 bulan terhitung sejak tanggal masuk barang, maka kehilangan dan kerusakan diluar tanggung jawab Alju Shoes Clean.\n";
            $msg .= "4. Setiap konsumen dianggap setuju dengan peraturan diatas.\n\n";
            $msg .= "==========================\n\n";
            $msg .= "https://aljushoesclean.com/invoice/$transaction->uuid\n\n";
            $msg .= "Terimakasih telah mencuci di Alju Shoes Clean :)";

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
            'target' => $customer->whatsapp_number,
            'message' => $msg,
            'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: d3W-pRaBFS@J1gKvyDKT' //change TOKEN to your actual token
            ),
            ));

            $response = curl_exec($curl);

            // dd($response);

            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);

            if (isset($error_msg)) {
                echo $error_msg;
            }
            // echo $response;

            Transaction::where('id', $transaction->id)->update(['transaction_status' => 'on_progress']);

        } else if ($transaction->transaction_status == 'on_progress') {

            $validatedData = $request->validate([
                'picture_evidence' => 'file|max:2048|mimes:jpg,png,jpeg,svg'
            ]);

            $validatedData['transaction_status'] = 'done';

            if ($request->file('picture_evidence')) {
                $validatedData['picture_evidence'] = $request->file('picture_evidence')->store('transaction');
            }

            Transaction::where('id', $transaction->id)->update($validatedData);

        } else if ($transaction->transaction_status == 'done') {
            return redirect('/dashboard/transaction/pending');
        } else {
            Transaction::where('id', $transaction->id)->update(['transaction_status' => 'pending']);
            // $newTransaction = Transaction::where('id', $transaction->id)->first();
            // dd($newTransaction);
        }

        return redirect('/dashboard/transaction/dropzone')->with('success', "$transaction->invoice_no status has been sucessfully updated");
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
