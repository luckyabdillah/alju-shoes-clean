<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hariLibur = Config::firstOrCreate(
            ['slug' => 'hari-libur'],
            [
                'name' => 'Hari libur',
                'value' => 'Thursday',
            ]
        );
        
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
                'value' => '3000',
            ]
        );

        $jarakMaksimumPengantaran = Config::firstOrCreate(
            ['slug' => 'jarak-maksimum-pengantaran'],
            [
                'name' => 'Jarak maksimum pengantaran',
                'value' => '200000',
            ]
        );

        $selangWaktuPenjemputan = Config::firstOrCreate(
            ['slug' => 'selang-waktu-penjemputan'],
            [
                'name' => 'Selang waktu penjemputan',
                'value' => '4',
            ]
        );
        
        $emailUtama = Config::firstOrCreate(
            ['slug' => 'email-utama'],
            [
                'name' => 'Email utama',
                'value' => 'luckyabdillah00@gmail.com',
            ]
        );
        
        $nomorWhatsapp = Config::firstOrCreate(
            ['slug' => 'nomor-whatsapp'],
            [
                'name' => 'Nomor WhatsApp',
                'value' => '6281283890098',
            ]
        );
        
        $akunInstagram = Config::firstOrCreate(
            ['slug' => 'akun-instagram'],
            [
                'name' => 'Akun instagram',
                'value' => 'aljushoesclean',
            ]
        );

        return view('dashboard.config.index', compact('hariLibur', 'biayaPengantaran', 'gratisPengantaran', 'jarakMaksimumPengantaran', 'selangWaktuPenjemputan', 'emailUtama', 'nomorWhatsapp', 'akunInstagram'));
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
    public function show(Config $config)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Config $config)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Config $config)
    {
        $validatedData = $request->validate([
            'hari_libur' => 'required',
            'biaya_pengantaran' => 'required|numeric|min:0',
            'gratis_pengantaran' => 'required|numeric|min:0',
            'jarak_maksimum_pengantaran' => 'required|numeric|min:0',
            'selang_waktu_penjemputan' => 'required|numeric|min:0',
            'email_utama' => 'required|email:rfc,dns|max:255',
            'nomor_whatsapp' => 'required|numeric',
            'akun_instagram' => 'required',
        ]);

        $validatedData['akun_instagram'] = preg_replace('/\s+/', '', $request->akun_instagram);

        foreach ($validatedData as $key => $value) {
            $slug = str_replace('_', '-', $key);
            $config = Config::firstWhere('slug', $slug);
            $config->update(['value' => $value]);
        }

        return redirect('dashboard/config')->with('success', 'Config berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Config $config)
    {
        //
    }
}
