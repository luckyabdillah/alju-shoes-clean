<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promoCodes = PromoCode::all();

        return view('dashboard.master-data.promo-code.index', compact('promoCodes'));
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
        $validatedData = $request->validate([
            'code' => 'required|unique:promo_codes,code',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:amount,percentage',
            'promo_type' => 'required|in:treatment,delivery',
            'min_spend' => 'required|numeric|min:0',
            'expiration_date' => 'required',
        ]);

        $validatedData['code'] = strtoupper(preg_replace('/\s+/', '', $validatedData['code']));

        PromoCode::create($validatedData);

        return redirect('/dashboard/master-data/promo-code')->with('success', 'Kode promo baru berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PromoCode $promoCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PromoCode $promoCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromoCode $promoCode)
    {
        $rules = [
            'code' => 'required',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:amount,percentage',
            'promo_type' => 'required|in:treatment,delivery',
            'min_spend' => 'required|numeric|min:0',
            'expiration_date' => 'required',
        ];

        if ($request->code != $promoCode->code) {
            $rules['code'] = 'required|unique:promo_codes,code';
        }

        $validatedData = $request->validate($rules);

        $validatedData['code'] = strtoupper(preg_replace('/\s+/', '', $validatedData['code']));

        $promoCode->update($validatedData);

        return redirect('/dashboard/master-data/promo-code')->with('success', 'Kode promo berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromoCode $promoCode)
    {
        PromoCode::destroy($promoCode->id);

        return redirect('/dashboard/master-data/promo-code')->with('success', 'Kode promo berhasil dihapus');
    }
}
