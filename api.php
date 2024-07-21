<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Recharge {
.................................

public function callBack (Request $request){
        // Log the entire request for debugging purposes
        Log::alert($request);
    
        // Check if the JSON parsing was successful
        if (!empty($request['MpesaReceiptNumber'])) {
            // Log specific parts of the response
    
            // Extract necessary data from the response
            $deposit_amount = ($request['Amount']); // Ensure amount is treated as float
            $user_reference = $request['user_reference'];
            $code = $request['MpesaReceiptNumber'];
    
            // Retrieve user details
            $user = DB::table('users')->where('id', $user_reference)->first();
    
            if ($user) {
                // Update user balance
                $current_balance = $user->balance ?? 0;
                
                $total_balance = $current_balance + $deposit_amount;
               
    
                // Check if transaction exists
                $current_trx = DB::table('deposits')->where('transaction', $code)->exists();
    
                if (!$current_trx) {
                    // Update user's balance
                    DB::table('users')->where('id', $user->id)->update(['balance' => $total_balance,]);
    
                    // Insert deposit record
                    DB::table('deposits')->insert([
                        'method' => 'Mpesa',
                        'transaction' => $code,
                        'username' => $user->id,
                        'amount' => $deposit_amount,
                        'status' => 'healthy'
                    ]);
                }
            }
        }
    
        // Return a response (adjust as needed)
        return response()->json(['message' => 'Transaction processed successfully'], 200);
    }

