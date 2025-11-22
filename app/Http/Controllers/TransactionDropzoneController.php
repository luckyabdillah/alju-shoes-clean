<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Treatment;
use App\Models\TreatmentDetail;
use App\Models\Customer;
use App\Models\Outlet;
use App\Models\PromoCode;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

use Mail;
use App\Mail\SubmitTransactionMail;

class TransactionDropzoneController extends Controller
{
    
    protected function getUserCoordinate($address)
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

    protected function calculateDistance($destinations, $maximum)
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

    public function createStepOne()
    {
        $transactions = Session::get('transactions');
        $outlets = Outlet::where('id', '!=', 1)->where('status', 1)->get();
        $preselectOutlet = $transactions ? $transactions['outlet_id'] : request('o');

        return view('transactions.dropzone.order', [
            'treatments' => Treatment::with('treatment_details')->get(),
            'transactions' => $transactions,
            'outlets' => $outlets,
            'preselectOutlet' => $preselectOutlet,
        ]);
    }

    public function storeStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'transaction_type' => 'required|in:dropzone,pickup-delivery',
            'outlet_id' => 'nullable',
            'transaction_details.*.type' => 'required',
            'transaction_details.*.merk' => 'required|max:100',
            'transaction_details.*.size_placeholder' => 'nullable',
            'transaction_details.*.size' => 'required|max:15',
            'transaction_details.*.treatment_details_id' => 'required',
            'transaction_details.*.description' => 'required|max:255',
        ]);

        if ($request->transaction_type == 'dropzone') {
            $outlet = Outlet::firstWhere('id', $request->outlet_id);

            if (!isset($outlet)) {
                return redirect('/order')->with('failed', 'Outlet tidak terdeteksi, masukkan URL halaman order dengan benar');
            }
            
            if (!$outlet->status) {
                return redirect('/order')->with('failed', 'Outlet tidak aktif atau tidak valid');
            }
        }

        $validatedData['partner_price'] = isset($outlet) ? $outlet->partner_price : 0;

        $list = [];
        $totalItem = 0;
        $treatmentCost = 0;

        $i = 0;
        foreach ($validatedData['transaction_details'] as $item => $detail) {
            if ($detail['treatment_details_id'] != null) {
                $totalItem += 1;
                $treatment_details = TreatmentDetail::with('treatment')->where('id', $detail['treatment_details_id'])->first();
                
                if ($detail['type'] != $treatment_details->treatment->type) {
                    return redirect()->back()->with('failed', 'Treatment tidak sesuai dengan tipe item');
                }
                
                $day = $treatment_details->processing_time;
                $treatmentCost += $treatment_details->cost;
                $validatedData['transaction_details'][$item]['treatment_id'] = $treatment_details->treatment->id;
                $validatedData['transaction_details'][$item]['cost'] = $treatment_details->cost;
                $validatedData['transaction_details'][$item]['treatment_name'] = $treatment_details->treatment->name . ' – ' . $treatment_details->name;
                array_push($list, $day);
            }
            $i++;
        }

        // $hariLibur = Config::firstOrCreate(
        //     ['slug' => 'hari-libur'],
        //     [
        //         'name' => 'Hari libur',
        //         'value' => 'Thursday',
        //     ]
        // );

        // $processingTime = max($list) - 1;

        // $startdate = strtotime("now");
        // $enddate = strtotime("+$processingTime days");

        // $temp = $startdate;
        // $gap = [];
        // $skipHoliday = false;

        // while ($temp < $enddate) {
        //     array_push($gap, date("l", $temp));
        //     $temp = strtotime("+1 day", $temp);
        // }
        // array_push($gap, date("l", $enddate));

        // if (in_array($hariLibur->value, $gap)) {
        //     $skipHoliday = true;
        //     $enddate = strtotime("+1 day", $enddate);
        // }

        // $isAbove3PM = false;

        // if ((int)date("H") >= 15) {
        //     $isAbove3PM = true;
        //     $enddate = strtotime("+1 day", $enddate);
        // }

        // $validatedData['transaction_start'] = date('Y-m-d H:i:s', $startdate);
        // $validatedData['transaction_end'] = date('Y-m-d H:i:s', $enddate);
        // $validatedData['total_item'] = $totalItem;
        // $validatedData['cost'] = $treatmentCost;
        // $validatedData['isAbove3PM'] = $isAbove3PM;
        // $validatedData['skipHoliday'] = $skipHoliday;

        $processingTime = max($list) - 1;

        $validatedData['total_item'] = $totalItem;
        $validatedData['cost'] = $treatmentCost;
        $validatedData['processing_time'] = $processingTime;

        // Session::forget('pickup-delivery');
        // Session::put('pickup-delivery', $validatedData);

        Session::forget('transactions');
        Session::put('transactions', $validatedData);

        return redirect('/order/details');
    }

    public function createStepTwo()
    {
        $transactions = Session::get('transactions');
        $identity = Session::get('identity');

        if (!isset($transactions)) {
            return redirect('/order')->with('failed', 'Silahkan isi data order terlebih dahulu');
        }

        $hariLibur = Config::firstOrCreate(
            ['slug' => 'hari-libur'],
            [
                'name' => 'Hari libur',
                'value' => 'Thursday',
            ]
        );

        $selangWaktuPenjemputan = Config::firstOrCreate(
            ['slug' => 'selang-waktu-penjemputan'],
            [
                'name' => 'Selang waktu penjemputan',
                'value' => '4',
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

        return view('transactions.dropzone.details', [
            'transactions' => $transactions,
            'identity' => $identity,
            'total_items' => $transactions['total_item'],
            'treatment_cost' => $transactions['cost'],
            'holidayInNumber' => $holidayInNumber,
            'holidayInDay' => $holidayInDay,
            'selangWaktuPenjemputan' => $selangWaktuPenjemputan,
        ]);
    }

    public function storeStepTwo(Request $request)
    {
        $transactions = Session::get('transactions');

        $message = [
            'required' => 'Kolom wajib diisi',
            'numeric' => 'Mohon mengisi Nomor WhatsApp dengan angka',
            'max' => 'Huruf tidak boleh lebih dari :max',
            'email' => 'Harap isi dengan email yang valid'
        ];

        $rules = [
            'whatsapp_number' => 'required|numeric|min_digits:10',
            'email' => 'required|email:rfc,dns|max:150',
            'name' => 'required|max:100',
            'address' => 'nullable|max:512',
        ];

        if ($transactions['transaction_type'] == 'pickup-delivery') {
            $rules['address'] = 'required|max:512';
            $rules['benchmark'] = 'required|max:50';
            $rules['pickup_date'] = 'required';
            $rules['pickup_time'] = 'required';
        }

        $validatedData = $request->validate($rules, $message);
        
        $processingTime = $transactions['processing_time'];

        $startdate = $transactions['transaction_type'] == 'pickup-delivery' ? strtotime($validatedData['pickup_date']) : strtotime('now');
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
        $placingHour = $transactions['transaction_type'] == 'pickup-delivery' ? (int)explode(':', $validatedData['pickup_time'])[0] : (int)date("H");
        $placingMinute = $transactions['transaction_type'] == 'pickup-delivery' ? (int)explode(':', $validatedData['pickup_time'])[1] : (int)date("i");
        if ($placingHour >= 15) {
            $isAbove3PM = true;
            $enddate = strtotime("+1 day", $enddate);
        }

        $transactions['holidayInDay'] = $holidayInDay;
        $transactions['isAbove3PM'] = $isAbove3PM;
        $transactions['skipHoliday'] = $skipHoliday;

        $transactions['transaction_start'] = date('Y-m-d', $startdate) . " $placingHour:$placingMinute:00";
        $transactions['transaction_end'] = date('Y-m-d', $enddate) . " $placingHour:$placingMinute:00";
        $shippingCost = 0;

        $selangWaktuPenjemputan = Config::firstWhere('slug', 'selang-waktu-penjemputan');
        $selangWaktuPenjemputanInSeconds = $transactions['transaction_type'] == 'pickup-delivery' ? $selangWaktuPenjemputan->value * 3600 : 0;

        if (strtotime($transactions['transaction_start']) < (strtotime('now') + $selangWaktuPenjemputanInSeconds - 600)) {
            return redirect()->back()->withInput()->with('failed', 'Waktu mulai transaksi tidak bisa lebih kecil dari waktu sekarang');
        }

        $customer = Customer::updateOrCreate(
            ['whatsapp_number' => '62' . $validatedData['whatsapp_number']],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'address' => $validatedData['address'],
            ]
        );

        if ($transactions['transaction_type'] == 'pickup-delivery') {
            if ($customer->lat === null || $customer->lang === null || $customer->address != $validatedData['address']) {
                $coordinate = $this->getUserCoordinate($validatedData['address']);
    
                if ($coordinate === "INVALID_ADDRESS") {
                    return redirect()->back()->withInput()->with('failed', 'Mohon masukkan alamat yang valid');
                } elseif ($coordinate === "BAD_REQUEST") {
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
            $distance = $this->calculateDistance($destinations, intval($jarakMaksimumPengantaran->value));
    
            if ($distance === 'ZERO_RESULTS' || $distance === 'INVALID_REQUEST') {
                return redirect()->back()->withInput()->with('failed', 'Mohon masukkan alamat yang valid');
            } elseif ($distance === 'BAD_REQUEST') {
                return redirect()->back()->withInput()->with('failed', 'Terjadi kesalahan, mohon hubungi Administrator');
            } elseif ($distance === 'TOO_FAR') {
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
        }

        $transactions['shipping_cost'] = $shippingCost;

        if ($transactions['shipping_cost'] < 0) {
            $transactions['shipping_cost'] = 0;
        }

        $transactions['total_cost'] = (int)$transactions['cost'] + (int)$transactions['shipping_cost'];
        
        Session::forget('identity');
        Session::put('identity', $validatedData);

        Session::forget('transactions');
        Session::put('transactions', $transactions);

        return redirect('/order/summary');
    }

    public function createStepThree()
    {
        $transactions = Session::get('transactions');
        $identity = Session::get('identity');

        if (!isset($transactions)) {
            return redirect('/order')->with('failed', 'Silahkan isi data order terlebih dahulu');
        }

        if (!isset($identity)) {
            return redirect('/order/details')->with('failed', 'Silahkan isi data diri terlebih dahulu');
        }

        return view('transactions.dropzone.summary', compact(['transactions', 'identity']));
    }

    public function storeStepThree(Request $request)
    {
        $transactions = Session::get('transactions');
        $identity = Session::get('identity');

        if (!isset($transactions)) {
            return redirect('/order')->with('failed', 'Silahkan isi data order terlebih dahulu');
        }

        if (!isset($identity)) {
            return redirect('/order/details')->with('failed', 'Silahkan isi data diri terlebih dahulu');
        }

        $rules = [
            'promo_code' => 'nullable',
            'promo_code_treatment' => 'nullable',
            'promo_code_delivery' => 'nullable'
        ];

        $rules['payment_method'] = $transactions['transaction_type'] == 'dropzone' ? 'required' : 'nullable';

        $message = [
            'required' => 'Kolom wajib diisi',
            'max' => 'Ukuran tidak boleh lebih dari :max KB'
        ];

        if ($request->payment_method === 'qris' || $transactions['transaction_type'] == 'pickup-delivery') {
            $rules['proof_of_payment'] = 'required|file|max:2048|mimes:jpg,png,jpeg,svg';
        }

        $validatedData = $request->validate($rules, $message);

        $whatsappNumber = '62' . $identity['whatsapp_number'];
        $customer = Customer::where('whatsapp_number', $whatsappNumber)->first();

        if ($transactions['transaction_type'] == 'pickup-delivery') {
            $outlet = Outlet::firstOrCreate(
                ['outlet_name' => 'Workshop'],
                [
                    'uuid' => Str::uuid()->toString(),
                    'outlet_code' => 'WKSP',
                    'parter_price' => 0,
                ]
            );
            $outletId = $outlet->id;
        } else {
            $outletId = $transactions['outlet_id'];
        }

        $date = date('Y-m-d');

        $treatmentDiscount = 0;
        $deliveryDiscount = 0;

        // Determine promo codes from new fields or legacy field
        $candidateCodes = [];
        if ($request->filled('promo_code_treatment')) {
            $candidateCodes[] = $request->promo_code_treatment;
        }
        if ($request->filled('promo_code_delivery')) {
            $candidateCodes[] = $request->promo_code_delivery;
        }
        // fallback to legacy promo_code (comma-separated) if new fields not provided
        if (empty($candidateCodes) && $request->filled('promo_code')) {
            $parts = array_filter(array_map('trim', explode(',', $request->promo_code)));
            foreach ($parts as $p) {
                if ($p !== '') $candidateCodes[] = $p;
            }
        }

        $baseSpend = (int)$transactions['cost'] + (int)$transactions['shipping_cost'];

        foreach ($candidateCodes as $code) {
            if (!$code) continue;
            $promo = PromoCode::where('code', $code)->where('expiration_date', '>=', $date)->first();
            if (!$promo) continue;

            // check min spend against total (treatment + shipping) to match frontend behavior
            if ($baseSpend < $promo->min_spend) {
                continue; // skip promos that don't meet min spend
            }

            if ($promo->promo_type == 'treatment') {
                // apply to treatment subtotal
                if ($promo->type == 'amount') {
                    $treatmentDiscount += (int)$promo->amount;
                } else {
                    $treatmentDiscount += (int)floor($transactions['cost'] * $promo->amount / 100);
                }
            } else {
                // delivery promo
                if ($transactions['transaction_type'] == 'dropzone') {
                    return redirect()->back()->with('failed', 'Kode yang kamu masukkan hanya berlaku untuk layanan Pickup & Delivery');
                }

                if ($promo->type == 'amount') {
                    $deliveryDiscount += (int)$promo->amount;
                } else {
                    $deliveryDiscount += (int)floor($transactions['shipping_cost'] * $promo->amount / 100);
                }
            }
        }

        // ensure discounts don't exceed their respective amounts
        if ($treatmentDiscount > (int)$transactions['cost']) $treatmentDiscount = (int)$transactions['cost'];
        if ($deliveryDiscount > (int)$transactions['shipping_cost']) $deliveryDiscount = (int)$transactions['shipping_cost'];

        $totalCost = (int)$transactions['cost'] - $deliveryDiscount - $treatmentDiscount + (int)$transactions['shipping_cost'];

        $year = date('Y');
        $lastNumberData = Transaction::where('transaction_year', $year)->orderBy('id', 'desc')->first();
        $lastNumber = $lastNumberData ? (int)explode('/', $lastNumberData->invoice_no)[0] : 0;
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

        $invoiceNo = str_pad($newNumber, 4, '0', STR_PAD_LEFT) . '/ASC/' . date('m') . '/' . date('y');

        $transactions['proof_of_payment'] = $request->file('proof_of_payment') ? $request->file('proof_of_payment')->store('payments') : null;
        $transaction = Transaction::create([
            'uuid' => Str::uuid()->toString(),
            'customer_id' => $customer->id,
            'outlet_id' => $outletId,
            'transaction_type' => $transactions['transaction_type'],
            'payment_method' => $validatedData['payment_method'] ?? 'qris',
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
                'parter_price' => $transactions['partner_price'],
                'treatment_name' => $item['treatment_name'],
                'description' => $item['description']
            ]);
        }

        $totalOrder = $customer->total_order + 1;
        $totalItems = $customer->total_items + $transactions['total_item'];
        $customer->update([
            'last_order' => date('Y-m-d H:i:s', strtotime('now')),
            'total_order' => $totalOrder,
            'total_items' => $totalItems,
        ]);
        
        $emailUtama = Config::firstOrCreate(
            ['slug' => 'email-utama'],
            [
                'name' => 'Email utama',
                'value' => 'luckyabdillah00@gmail.com',
            ]
        );

        $mailDelivery = false;
        $mailAttemps = 0;

        while (!$mailDelivery) {
            if ($mailAttemps >= 3) {
                break;
            }
            try {
                Mail::to($customer->email)
                    ->bcc($emailUtama->value)
                    ->send(new SubmitTransactionMail($customer->name, $transaction));
                
                $mailDelivery = true;
            } catch (\Throwable $th) {
                $mailAttemps += 1;
            }
        }

        if (!$mailDelivery) {
            TransactionDetail::where('transaction_id', $transaction->id)->delete();
            Transaction::destroy($transaction->id);
            return redirect('/order/summary')->with('failed', 'Terjadi keasalahan pengiriman email, mohon submit kembali');
        }

        Session::forget('transactions');
        Session::forget('identity');

        return redirect("/invoice/$transaction->uuid")->with('success', 'Berhasil order');
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
        return view('transactions.show', compact('transaction'));
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
