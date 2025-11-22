<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Treatment;
use App\Models\TreatmentDetail;
use App\Models\Customer;
use App\Models\Outlet;
use App\Models\Config;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

use Mail;
use App\Mail\SubmitTransactionMail;

class TransactionPDController extends Controller
{
    public function createStepOne()
    {
        $transactions = Session::get('pickup-delivery');

        return view('transactions.pickup-delivery.order', [
            'treatments' => Treatment::with('treatment_details')->get(),
            'transactions' => $transactions
        ]);
    }

    public function storeStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'transaction_details.*.type' => 'required',
            'transaction_details.*.merk' => 'required|max:100',
            'transaction_details.*.size' => 'nullable|max:15',
            'transaction_details.*.treatment_details_id' => 'required',
            'transaction_details.*.description' => 'required|max:255',
        ]);

        $list = [];
        $totalItem = 0;
        $treatmentCost = 0;

        $i = 0;
        foreach ($validatedData['transaction_details'] as $detail) {
            if ($detail['treatment_details_id'] != null) {
                $totalItem += 1;
                $treatment_details = TreatmentDetail::with('treatment')->where('id', $detail['treatment_details_id'])->first();
                
                if ($detail['type'] != $treatment_details->treatment->type) {
                    return redirect()->back()->with('failed', 'Treatment tidak sesuai dengan tipe item');
                }

                $day = $treatment_details->processing_time;
                $treatmentCost += $treatment_details->cost;
                $validatedData['transaction_details'][$i]['treatment_id'] = $treatment_details->treatment->id;
                $validatedData['transaction_details'][$i]['cost'] = $treatment_details->cost;
                $validatedData['transaction_details'][$i]['treatment_name'] = $treatment_details->treatment->name . ' - ' . $treatment_details->name;
                array_push($list, $day);
            }
            $i++;
        }

        $processingTime = max($list) - 1;

        $validatedData['transaction_type'] = 'pickup-delivery';
        $validatedData['total_item'] = $totalItem;
        $validatedData['cost'] = $treatmentCost;
        $validatedData['processing_time'] = $processingTime;

        Session::forget('pickup-delivery');
        Session::put('pickup-delivery', $validatedData);

        return redirect('/pickup-delivery/details');
    }

    public function createStepTwo()
    {
        $transactions = Session::get('pickup-delivery');
        $identity = Session::get('pickup-delivery-identity');

        if (!isset($transactions)) {
            return redirect('/pickup-delivery')->with('failed', 'Silahkan isi data order terlebih dahulu');
        }

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

        return view('transactions.pickup-delivery.details', [
            'identity' => $identity,
            'total_items' => $transactions['total_item'],
            'treatment_cost' => $transactions['cost'],
            'holidayInNumber' => $holidayInNumber,
            'holidayInDay' => $holidayInDay,
        ]);
    }

    public function storeStepTwo(Request $request)
    {
        function getUserCoordinate($address)
        {
            $endpoint = 'https://api.distancematrix.ai/maps/api/geocode/json';
            $params = array(
                'address' => $address,
                'key' => 'fnOCONjs6hcgFg2WhLt1Xt8R9GaLFQBMBznCchzOavzarfa8JLHkaZMYFQls0alM'
            );
            $url = $endpoint . '?' . http_build_query($params);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET'
            ));

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);

            if (isset($error_msg)) {
                $response = $error_msg;
            }

            $result = json_decode($response);

            if ($result) {
                if ($result->status == 'OK') {
                    if ($result->result[0]->geometry->location->lat == 0 && $result->result[0]->geometry->location->lng == 0) {
                        return "INVALID_ADDRESS";
                    } else {
                        return $result->result[0]->geometry->location;
                    }
                } else {
                    return "BAD_REQUEST";
                }
            } else {
                return "BAD_REQUEST";
            }
        }

        function calculateDistance($destinations, $maximum)
        {
            $endpoint = 'https://api.distancematrix.ai/maps/api/distancematrix/json';
            $origins = '-6.181936,106.858742';
            
            $params = array(
                'origins' => $origins,
                'destinations' => $destinations,
                'key' => 'fnOCONjs6hcgFg2WhLt1Xt8R9GaLFQBMBznCchzOavzarfa8JLHkaZMYFQls0alM'
            );
            $url = $endpoint . '?' . http_build_query($params);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET'
            ));

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);

            if (isset($error_msg)) {
                $response = $error_msg;
            }

            $result = json_decode($response);

            if ($result) {
                if ($result->status == 'OK') {
                    if ($result->rows[0]->elements[0]->status == 'ZERO_RESULTS') {
                        return 'ZERO_RESULTS';
                    } else {
                        $distance = $result->rows[0]->elements[0]->distance->value;
                        if ($distance < $maximum) {
                            $km = ceil($distance / 1000);
                            return $km;
                        } else {
                            return 'TOO_FAR';
                        }
                    }
                } elseif ($result->status == 'INVALID_REQUEST') {
                    return 'INVALID_REQUEST';
                } else {
                    return 'BAD_REQUEST';
                    return redirect()->back()->with('failed', 'Terjadi kesalahan, mohon hubungi Administrator');
                }
            } else {
                return 'BAD_REQUEST';
                return redirect()->back()->with('failed', 'Terjadi kesalahan, mohon hubungi Administrator');
            }
        }

        $message = [
            'required' => ':Attribute wajib diisi',
            'numeric' => 'Mohon mengisi Nomor WhatsApp dengan angka',
            'max' => 'Huruf tidak boleh lebih dari :max',
            'email' => 'Harap isi dengan email yang valid'
        ];

        $validatedData = $request->validate([
            'whatsapp_number' => 'required|numeric|min_digits:10',
            'email' => 'required|email:rfc,dns|max:150',
            'name' => 'required|max:100',
            'address' => 'max:512',
            'benchmark' => 'required|max:50',
            'pickup_date' => 'required',
            'pickup_time' => 'required'
        ], $message);

        $transactions = Session::get('pickup-delivery');

        $processingTime = $transactions['processing_time'];

        $startdate = strtotime($validatedData['pickup_date']);
        $enddate = strtotime("+$processingTime days", $startdate);

        $hariLibur = Config::firstWhere('slug', 'hari-libur');

        if ($hariLibur->value == 'Monday') {
            $holidayInDay = 'Senin';
        } elseif ($hariLibur->value == 'Tuesday') {
            $holidayInDay = 'Selasa';
        } elseif ($hariLibur->value == 'Wednesday') {
            $holidayInDay = 'Rabu';
        } elseif ($hariLibur->value == 'Thursday') {
            $holidayInDay = 'Kamis';
        } elseif ($hariLibur->value == 'Friday') {
            $holidayInDay = 'Jumat';
        } elseif ($hariLibur->value == 'Saturday') {
            $holidayInDay = 'Sabtu';
        } else {
            $holidayInDay = 'Minggu';
        }

        $temp = $startdate;
        $gap = [];
        $skipHoliday = false;

        while ($temp < $enddate) {
            array_push($gap, date("l", $temp));
            $temp = strtotime("+1 day", $temp);
        }
        array_push($gap, date("l", $enddate));

        if (in_array($hariLibur->value, $gap)) {
            $skipHoliday = true;
            $enddate = strtotime("+1 day", $enddate);
        }

        $isAbove3PM = false;
        if ((int)explode(':', $validatedData['pickup_time'])[0] >= 15) {
            $isAbove3PM = true;
            $enddate = strtotime("+1 day", $enddate);
        }

        $transactions['holidayInDay'] = $holidayInDay;
        $transactions['skipHoliday'] = $skipHoliday;
        $transactions['isAbove3PM'] = $isAbove3PM;

        $transactions['transaction_start'] = date('Y-m-d', $startdate) . ' ' . $validatedData['pickup_time'] . ':00';
        $transactions['transaction_end'] = date('Y-m-d', $enddate) . ' ' . $validatedData['pickup_time'] . ':00';

        $customer = Customer::updateOrCreate(
            ['whatsapp_number' => '62' . $validatedData['whatsapp_number']],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'address' => $validatedData['address'],
                'benchmark' => $validatedData['benchmark']
            ]
        );

        if ($customer->lat === null || $customer->lang === null || $customer->address != $validatedData['address']) {
            $coordinate = getUserCoordinate($validatedData['address']);

            if ($coordinate === "INVALID_ADDRESS") {
                // $customer->delete();
                return redirect()->back()->withInput()->with('failed', 'Mohon masukkan alamat yang valid');
            } elseif ($coordinate === "BAD_REQUEST") {
                // $customer->delete();
                return redirect()->back()->withInput()->with('failed', 'Terjadi kesalahan, mohon hubungi Administrator');
            }

            $lat = $coordinate->lat;
            $long = $coordinate->lng;

            $customer->update([
                'address' => $validatedData['address'],
                'benchmark' => $validatedData['benchmark'],
                'lat' => $lat,
                'long' => $long
            ]);
        }

        $jarakMaksimumPengantaran = Config::firstOrCreate(
            ['slug' => 'jarak-maksimum-pengantaran'],
            [
                'name' => 'Jarak maksimum pengantaran',
                'value' => '200000',
            ]
        );

        $destinations = $customer->lat . ',' . $customer->long;
        $distance = calculateDistance($destinations, intval($jarakMaksimumPengantaran->value));

        if ($distance === 'ZERO_RESULTS' || $distance === 'INVALID_REQUEST') {
            // $customer->delete();
            return redirect()->back()->withInput()->with('failed', 'Mohon masukkan alamat yang valid');
        } elseif ($distance === 'BAD_REQUEST') {
            // $customer->delete();
            return redirect()->back()->withInput()->with('failed', 'Terjadi kesalahan, mohon hubungi Administrator');
        } elseif ($distance === 'TOO_FAR') {
            // $customer->delete();
            return redirect()->back()->withInput()->with('failed', 'Alamat pengantaran terlalu jauh');
        }
        
        $biayaPengantaran = Config::firstOrCreate(
            ['slug' => 'biaya-pengantaran'],
            [
                'name' => 'Biaya pengantaran',
                'value' => '2500',
            ]
        );

        $gratisPengantaran = Config::firstOrCreate(
            ['slug' => 'gratis-pengantaran'],
            [
                'name' => 'Gratis pengantaran',
                'value' => '3',
            ]
        );

        $cost = ($distance * 2) - (intval($gratisPengantaran->value) * 2);
        $shippingCost = $cost * intval($biayaPengantaran->value);

        $transactions['shipping_cost'] = $shippingCost;

        if ($transactions['shipping_cost'] < 0) {
            $transactions['shipping_cost'] = 0;
        }

        $transactions['total_cost'] = (int)$transactions['cost'] + (int)$transactions['shipping_cost'];
        
        Session::forget('pickup-delivery-identity');
        Session::put('pickup-delivery-identity', $validatedData);

        Session::forget('pickup-delivery');
        Session::put('pickup-delivery', $transactions);

        return redirect('/pickup-delivery/summary');
    }

    public function createStepThree() {
        $transactions = Session::get('pickup-delivery');
        $identity = Session::get('pickup-delivery-identity');

        if (!isset($transactions)) {
            return redirect('/pickup-delivery')->with('failed', 'Silahkan isi data order terlebih dahulu');
        }

        if (!isset($identity)) {
            return redirect('/pickup-delivery/details')->with('failed', 'Silahkan isi data diri terlebih dahulu');
        }

        return view('transactions.pickup-delivery.summary', compact(['transactions', 'identity']));
    }

    public function storeStepThree(Request $request) {
        $transactions = Session::get('pickup-delivery');
        $identity = Session::get('pickup-delivery-identity');

        if (!isset($transactions)) {
            return redirect('/pickup-delivery')->with('failed', 'Silahkan isi data order terlebih dahulu');
        }

        if (!isset($identity)) {
            return redirect('/pickup-delivery/details')->with('failed', 'Silahkan isi data diri terlebih dahulu');
        }

        $message = [
            'required' => 'Kolom wajib diisi',
            'max' => 'Ukuran tidak boleh lebih dari :max kB'
        ];

        $validatedData = $request->validate([
            'proof_of_payment' => 'required|file|max:2048|mimes:jpg,png,jpeg,svg'
        ], $message);

        $whatsappNumber = '62' . $identity['whatsapp_number'];

        $outlet = Outlet::firstOrCreate(
            ['outlet_name' => 'Workshop'],
            [
                'uuid' => Str::uuid()->toString(),
                'outlet_code' => 'WKSP',
                'parter_price' => 0,
            ]
        );
        $customer = Customer::where('whatsapp_number', $whatsappNumber)->first();

        $date = date('Y-m-d');

        $treatmentDiscount = 0;
        $deliveryDiscount = 0;

        if ($request->promo_code) {
            $promoCode = PromoCode::where('code', $request->promo_code)->where('expiration_date', '>=', $date)->first();
            if ($promoCode) {
                if ($transactions['cost'] > $promoCode->min_spend) {
                    if ($promoCode->promo_type == 'treatment') {
                        $treatmentDiscount = $promoCode->type == 'amount' ? $promoCode->amount : $transactions['cost'] * $promoCode->amount / 100;
                    } else {
                        $deliveryDiscount = $promoCode->type == 'amount' ? $promoCode->amount : $transactions['cost'] * $promoCode->amount / 100;
                    }
                }
            }
        }

        $totalCost = (int)$transactions['cost'] - $deliveryDiscount - $treatmentDiscount + (int)$transactions['shipping_cost'];

        $year = date('Y');
        $lastNumberData = Transaction::where('transaction_year', $year)->orderBy('id', 'desc')->first();
        $lastNumber = $lastNumberData ? (int)explode('/', $lastNumberData->invoice_no)[0] : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

        $invoiceNo = str_pad($newNumber, 5, '0', STR_PAD_LEFT) . '/ASC/' . $year;

        $transactions['proof_of_payment'] = $request->file('proof_of_payment')->store('payments');
        $transaction = Transaction::create([
            'uuid' => Str::uuid()->toString(),
            'customer_id' => $customer->id,
            'outlet_id' => $outlet->id,
            'transaction_type' => 'pickup-delivery',
            'payment_method' => 'qris',
            'transaction_start' => $transactions['transaction_start'],
            'transaction_end' => $transactions['transaction_end'],
            'total_items' => $transactions['total_item'],
            'cost' => $transactions['cost'],
            'shipping_cost' => $transactions['shipping_cost'],
            'treatment_discount' => $treatmentDiscount,
            'delivery_discount' => $deliveryDiscount,
            'total_cost' => $totalCost,
            'invoice_no' => $invoiceNo,
            'transaction_year' => $year,
            'proof_of_payment' => $transactions['proof_of_payment']
        ]);

        foreach ($transactions['transaction_details'] as $item) {
            TransactionDetail::create([
                'uuid' => Str::uuid()->toString(),
                'transaction_id' => $transaction->id,
                'treatment_id' => $item['treatment_id'],
                'treatment_details_id' => $item['treatment_details_id'],
                'merk' => $item['merk'],
                'type' => $item['type'],
                'size' => $item['size'],
                'cost' => $item['cost'],
                'parter_price' => $outlet->parter_price,
                'description' => $item['description'],
                'treatment_name' => $item['treatment_name'],
            ]);
        }

        $totalOrder = $customer->total_order + 1;
        $totalItems = $customer->total_items + $transaction->total_items;
        $customer->update([
            'last_order' => date('Y-m-d H:i:s', strtotime('now')),
            'total_order' => $totalOrder,
            'total_items' => $totalItems
        ]);

        $mailDelivery = false;
        $mailAttemps = 0;
        
        $emailUtama = Config::firstOrCreate(
            ['slug' => 'email-utama'],
            [
                'name' => 'Email utama',
                'value' => 'luckyabdillah00@gmail.com',
            ]
        );

        while (!$mailDelivery) {
            if ($mailAttemps >= 3) {
                break;
            }
            try {
                Mail::to($customer->email)
                    ->cc($emailUtama->value)
                    ->send(new SubmitTransactionMail($customer->name, $transaction));
                
                $mailDelivery = true;
            } catch (\Throwable $th) {
                $mailAttemps += 1;
            }
        }

        if (!$mailDelivery) {
            TransactionDetail::where('transaction_id', $transaction->id)->delete();
            Transaction::destroy($transaction->id);
            return redirect('/pickup-delivery/summary')->with('failed', 'Gagal mengirim email, mohon submit kembali');
        }

        Session::forget('pickup-delivery');
        Session::forget('pickup-delivery-identity');

        return redirect("/invoice/$transaction->uuid")->with('success', 'Berhasil order');
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
