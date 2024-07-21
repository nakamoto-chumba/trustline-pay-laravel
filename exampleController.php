<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DepositController extends Controller
{
    
    
    public function store(Request $request)
    {
        $amount = filter_var($request->input('amount'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $mpesa = filter_var($request->input('phone'), FILTER_SANITIZE_STRING);
        $user = Auth::user()->id;

        // Define API details
        $apikey = "your_api_key_paste_here";
        $channel = '6';
        $postData = [
            "api_key" => $apikey,
            "orderNo" => "001",
            "amount" => $amount,
            "phone_number" => $mpesa,
            "user_reference" => $user,
            "payment_id" => $channel,
            "callback_url" => "https://cash-mining.nakaa.com.ng/api/payhero"
        ];

        $response = Http::post('https://tencent.nakaa.com.ng/api/v1/mpesa/express', $postData);
        $responseData = $response->json();

        if ($response->successful()) {
            return back()->with('success', 'payment successful');
        } else {
            return back()->with('error', "Try Again Later");
        }
    }
     
