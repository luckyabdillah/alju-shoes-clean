<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Gallery;
use App\Models\Config;
use App\Models\Treatment;
use App\Models\TreatmentDetail;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index() {
        $campaigns = Campaign::where('status', 1)->get();
        
        $gallery1 = Gallery::where('id', '1')->first();
        $gallery2 = Gallery::where('id', '2')->first();
        $gallery3 = Gallery::where('id', '3')->first();

        $treatments = Treatment::all();
        
        $freeDelivery = Config::firstOrCreate(
            ['slug' => 'gratis-pengantaran'],
            [
                'name' => 'Gratis pengantaran',
                'value' => '3',
            ]
        );
        
        $whatsappNumber = Config::firstOrCreate(
            ['slug' => 'nomor-whatsapp'],
            [
                'name' => 'Nomor WhatsApp',
                'value' => '6281283890098',
            ]
        );
        
        $instagramAccount = Config::firstOrCreate(
            ['slug' => 'akun-instagram'],
            [
                'name' => 'Akun instagram',
                'value' => 'aljushoesclean',
            ]
        );
        
        Session::forget('pickup-delivery');
        Session::forget('pickup-delivery-identity');
        Session::forget('transactions');
        Session::forget('identity');

        return view('index', compact('campaigns', 'gallery1', 'gallery2', 'gallery3', 'treatments', 'freeDelivery', 'whatsappNumber', 'instagramAccount'));
    }

    public function getTreatment(Treatment $treatment) {
        return response()->json([
            'statusCode' => 200,
            'message' => 'OK',
            'data' => $treatment
        ], 200);
    }

    public function getTreatmentDetails(Treatment $treatment) {
        $treatmentDetails = TreatmentDetail::where('treatment_id', $treatment->id)->get();

        if ($treatmentDetails->count()) {
            return response()->json([
                'statusCode' => 200,
                'message' => 'OK',
                'data' => $treatmentDetails
            ], 200);
        }

        return response()->json([
            'statusCode' => 404,
            'message' => 'Not found',
        ], 404);
    }

    public function checkPromoCode(string $code)
    {
        $date = date('Y-m-d');

        $promoCode = PromoCode::where('code', $code)->where('expiration_date', '>=', $date)->first();

        if ($promoCode) {
            return response()->json([
                'statusCode' => 200,
                'message' => 'OK',
                'data' => $promoCode,
            ], 200);
        }

        return response()->json([
            'statusCode' => 404,
            'message' => 'Not Found'
        ], 404);
    }
}
