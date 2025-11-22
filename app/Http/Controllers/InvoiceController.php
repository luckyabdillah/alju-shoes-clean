<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Config;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Transaction $transaction)
    {
        $hariLibur = Config::firstOrCreate(
            ['slug' => 'hari-libur'],
            [
                'name' => 'Hari libur',
                'value' => 'Thursday',
            ]
        );

        if ($hariLibur->value == 'Monday') {
            $holidayInNumber = 1;
            $holidayInDay = 'Senin';
        } elseif ($hariLibur->value == 'Tuesday') {
            $holidayInNumber = 2;
            $holidayInDay = 'Selasa';
        } elseif ($hariLibur->value == 'Wednesday') {
            $holidayInNumber = 3;
            $holidayInDay = 'Rabu';
        } elseif ($hariLibur->value == 'Thursday') {
            $holidayInNumber = 4;
            $holidayInDay = 'Kamis';
        } elseif ($hariLibur->value == 'Friday') {
            $holidayInNumber = 5;
            $holidayInDay = 'Jumat';
        } elseif ($hariLibur->value == 'Saturday') {
            $holidayInNumber = 6;
            $holidayInDay = 'Sabtu';
        } else {
            $holidayInNumber = 0;
            $holidayInDay = 'Minggu';
        }

        return view('transactions.invoice', compact('transaction', 'holidayInNumber', 'holidayInDay'));
    }

    public function export(Transaction $transaction)
    {
        $title = strtoupper('INVOICE ' . $transaction->customer->name) . ' - ' . $transaction->invoice_no;

        $pdf = Pdf::loadView('transactions.export', compact('transaction', 'title'));
        return $pdf->stream(strtoupper('INVOICE ' . $transaction->customer->name) . ' - ' . $transaction->invoice_no . '.pdf');
    }

    public function update(Transaction $transaction, Request $request)
    {
        $validatedData = $request->validate([
            'delivery_date' => 'required',
            'delivery_time' => 'required'
        ]);

        $updatedEndDate = date('Y-m-d', strtotime($validatedData['delivery_date'])) . ' ' . $validatedData['delivery_time'] . ':00';

        $transaction->update(['transaction_end' => $updatedEndDate]);

        return redirect("/invoice/$transaction->uuid")->with('success', 'Transaksi berhasil diperbarui');
    }
}
