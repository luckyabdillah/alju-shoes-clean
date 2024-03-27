<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\DetailTransaction;
use App\Models\Treatment;
use App\Models\DetailTreatment;
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
    public function createStepOne()
    {
        $transactions = Session::get('transactions');
        // Session::forget('transactions');

        return view('transactions.dropoff.order', [
            'treatments' => Treatment::with('detail_treatments')->get(),
            'transactions' => $transactions
        ]);
    }

    public function indexPD() {
        return view('pickup-delivery', [
            'treatments' => Treatment::with('detail_treatments')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function storeStepOne(Request $request)
    {
        // Session::put('transactions', $request->all());

        // dd($request);
        // dd(request('o'));

        $validatedData = $request->validate([
            // 'whatsapp_number' => 'required|numeric',
            // 'name' => 'required|max:150',
            // 'address' => 'required|max:255',
            'detail_transactions.*.type' => 'required',
            'detail_transactions.*.merk' => 'required|max:100',
            'detail_transactions.*.size' => 'max:15',
            'detail_transactions.*.treatment_detail_id' => 'required',
            'detail_transactions.*.description' => 'required|max:255',
        ]);

        // dd($validatedData['detail_transactions']);

        $outlet = Outlet::firstWhere('uuid', $request->outlet_uuid);

        if (!isset($outlet)) {
            return redirect('/order')->with('failed', 'Outlet tidak terdeteksi, masukkan URL halaman order dengan benar');
        }

        $validatedData['outlet_id'] = $outlet->id;
        $validatedData['outlet_uuid'] = $outlet->uuid;

        $list = [];
        $totalItem = 0;
        $treatmentCost = 0;

        $i = 0;
        foreach ($validatedData['detail_transactions'] as $detailTransaction) {
            if ($detailTransaction['treatment_detail_id'] != null) {
                $totalItem += 1;
                $treatment_detail = DetailTreatment::with('treatment')->where('id', $detailTransaction['treatment_detail_id'])->first();
                // dd($treatment_detail);
                $day = $treatment_detail->processing_time;
                $treatmentCost += $treatment_detail->cost;
                // dd($treatment_detail->treatment->id);
                $validatedData['detail_transactions'][$i]['treatment_id'] = $treatment_detail->treatment->id;
                $validatedData['detail_transactions'][$i]['cost'] = $treatment_detail->cost;
                $validatedData['detail_transactions'][$i]['treatment_name'] = $treatment_detail->name;
                // dd($detailTransaction);
                array_push($list, $day);
            }
            $i++;
        }
        // dd($validatedData['detail_transactions']);
        $processingTime = max($list) - 1;

        $startdate = strtotime("now");
        $enddate = strtotime("+$processingTime days");

        $temp = $startdate;
        $gap = [];
        $skipThursday = false;

        while ($temp < $enddate) {
            array_push($gap, date("l", $temp));
            $temp = strtotime("+1 day", $temp);
        }
        array_push($gap, date("l", $enddate));

        if (in_array("Thursday", $gap)) {
            $skipThursday = true;
            $enddate = strtotime("+1 day", $enddate);
        }

        $isAbove3PM = false;

        if ((int)date("h") >= 15) {
            $isAbove3PM = true;
            $enddate = strtotime("+1 day", $enddate);
        }

        // dd(date('d m Y, H:i:s', $enddate));

        $validatedData['transaction_start'] = date('Y-m-d H:i:s', $startdate);
        $validatedData['transaction_end'] = date('Y-m-d H:i:s', $enddate);
        $validatedData['transaction_type'] = 'dropoff';
        $validatedData['total_item'] = $totalItem;
        $validatedData['cost'] = $treatmentCost;

        // dd($validatedData);

        Session::forget('transactions');

        Session::put('transactions', $validatedData);

        return redirect('/order/details');
    }

    public function createStepTwo() {
        $transactions = Session::get('transactions');
        $identity = Session::get('identity');

        if (!isset($transactions)) {
            return redirect('/order')->with('failed', 'Silahkan isi data order terlebih dahulu');
        }

        // dd($transactions);

        return view('transactions.dropoff.details', [
            'identity' => $identity,
            'total_items' => $transactions['total_item'],
            'treatment_cost' => $transactions['cost'],
            'outlet' => $transactions['outlet_uuid']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeStepTwo(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'whatsapp_number' => 'required|numeric',
            'name' => 'required|max:150',
            'address' => 'max:512'
        ]);
        
        Session::forget('identity');

        Session::put('identity', $validatedData);

        return redirect('/order/summary');
    }

    public function createStepThree() {
        $transactions = Session::get('transactions');
        $identity = Session::get('identity');

        if (!isset($transactions)) {
            return redirect('/order')->with('failed', 'Silahkan isi data order terlebih dahulu');
        }

        if (!isset($identity)) {
            return redirect('/order/details')->with('failed', 'Silahkan isi data diri terlebih dahulu');
        }

        return view('transactions.dropoff.summary', compact(['transactions', 'identity']));
    }

    public function storeStepThree(Request $request) {
        $transactions = Session::get('transactions');
        $identity = Session::get('identity');

        if (!isset($transactions)) {
            return redirect('/order')->with('failed', 'Silahkan isi data order terlebih dahulu');
        }

        if (!isset($identity)) {
            return redirect('/order/details')->with('failed', 'Silahkan isi data diri terlebih dahulu');
        }

        $rules = [
            'payment_method' => 'required'
        ];

        if ($request->payment_method === 'qris') {
            $rules['proof_of_payment'] = 'required|file|max:2048|mimes:jpg,png,jpeg,svg';
        }

        $validatedData = $request->validate($rules);

        $whatsappNumber = '62' . $identity['whatsapp_number'];

        $customer = Customer::updateOrCreate(
            ['whatsapp_number' => $whatsappNumber],
            [
                'name' => $identity['name'],
                'address' => $identity['address']
            ]
        );

        $lastNumberData = Transaction::orderBy('id', 'desc')->first();
        $lastNumber = $lastNumberData ? (int)explode('/', $lastNumberData->invoice_no)[0] : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        $year = date('Y');

        $invoiceNo = str_pad($newNumber, 5, '0', STR_PAD_LEFT) . '/ASC/' . $year;

        $transaction = Transaction::create([
            'uuid' => Str::uuid()->toString(),
            'customer_id' => $customer->id,
            'outlet_id' => $transactions['outlet_id'],
            'transaction_type' => $transactions['transaction_type'],
            'payment_method' => $validatedData['payment_method'],
            'transaction_start' => $transactions['transaction_start'],
            'transaction_end' => $transactions['transaction_end'],
            'total_items' => $transactions['total_item'],
            'cost' => $transactions['cost'],
            'total_amount' => $transactions['cost'],
            'invoice_no' => $invoiceNo
        ]);

        foreach ($transactions['detail_transactions'] as $item) {
            DetailTransaction::create([
                'uuid' => Str::uuid()->toString(),
                'transaction_id' => $transaction->id,
                'treatment_id' => $item['treatment_id'],
                'detail_treatment_id' => $item['treatment_detail_id'],
                'treatment_name' => $item['treatment_name'],
                'merk' => $item['merk'],
                'type' => $item['type'],
                'size' => $item['size'],
                'amount' => $item['cost'],
                'description' => $item['description']
            ]);
        }

        Session::forget('transactions');
        Session::forget('identity');

        return redirect("/invoice/$transaction->uuid")->with('success', 'Berhasil order');
    }

    public function pdStore(Request $request) {
        // dd($request);
        if ($request->coordinateOption === 'on') {
            dd('hehe opsi koordinat nyala');
        } else {
            dd('nda nyala');
        }
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
