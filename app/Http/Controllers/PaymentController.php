<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function auth(){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://api.triple-a.io/api/v2/oauth/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => env("TRIPLEA_CLIENT_ID"),
            'client_secret' => env("TRIPLEA_SECRET"),
            'grant_type' => 'client_credentials',
        )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = json_decode(curl_exec($ch));
        curl_close ($ch);

        if (isset($server_output->access_token)) {
            return $server_output->access_token;
        } else {
            abort(401);
        }
    }

    public function payPage(){
        return view('payment.index');
    }

    public function pay(PayRequest $request){
        $token = $this->auth();
        $data = array(
            'type' => 'triplea',
            'merchant_key' => env("TRIPLEA_MERCHANT_KEY"),
            'order_currency' => 'USD',
            'order_amount' => $request->amount,
            'payer_id' => $request->email,
            'payer_name' => $request->name,
            'payer_email' => $request->email,
            'success_url' => route('success'),
            'cancel_url' => route('fail'),
            'sandbox' => env("TRIPLEA_SANDBOX"),
        );
        if($request->address){
            $data['payer_address'] = $request->address;
        }
        $ch = curl_init();
        $headers = array(
            "Accept: application/json",
            "Authorization: Bearer {$token}",
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL,"https://api.triple-a.io/api/v2/payment");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = json_decode(curl_exec($ch));
        curl_close ($ch);
        return redirect($server_output->hosted_url);
    }

    public function success(Request $request){
        return view('payment.success');
    }

    public function fail(Request $request){
        return view('payment.fail');
    }
}
