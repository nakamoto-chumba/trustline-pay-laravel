## Laravel Implementations

### Controller Method (Recommended)

#### 1. Create the `store` Method

The `stkPush` method in the `DepositController` handles the logic for initiating a payment request. Below is an example implementation:

```php

    public function stkPush(Request $request)
    {
        $amount = filter_var($request->input('amount'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $mpesa = filter_var($request->input('phone'), FILTER_SANITIZE_STRING);
        $user = Auth::user()->id;

        // Define API details
        $apikey = env('PAYMENT_API_KEY');
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

