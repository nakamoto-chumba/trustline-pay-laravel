<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Deposit;

class DepositController extends Controller
{
    public function store(Request $request)
    {
        $amount = filter_var($request->input('amount'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $mpesa = filter_var($request->input('phone'), FILTER_SANITIZE_STRING);
        $user = Auth::user()->id;

        // Define API details
        $apikey = env('PAYMENT_API_KEY');
        $keyusername = env('PAYMENT_KEY_USERNAME');
        $channel = env('PAYMENT_CHANNEL');
        $postData = [
            "api_key" => $apikey,
            "orderNo" => "001",
            "amount" => $amount,
            "phone_number" => $mpesa,
            "user_reference" => $user,
            "payment_id" => $channel,
            "callback_url" => env('PAYMENT_CALLBACK_URL')
        ];

        $response = Http::post(env('PAYMENT_ENDPOINT'), $postData);
        $responseData = $response->json();

        if ($response->successful()) {
            return back()->with('success', 'payment successful');
        } else {
            return back()->with('error', "Try Again Later");
        }
    }
}
