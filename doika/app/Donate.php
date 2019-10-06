<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Donate extends Model
{

    static public function getPaymentPage($request, $id)
    {
        $backUrl = isset($request->backUrl) ? preg_replace('/#.+$/', '', urldecode($request->backUrl)) : ("${_SERVER['REQUEST_SCHEME']}://${_SERVER['HTTP_HOST']}/");
        $backUrl .= (strpos($backUrl, "?") > 0) ? "&" : "?";

        $donate = $request->donate * 100;
        $orderId = uniqid(true);
        $getTokenParams = [
            "userName" => config('payment_gateway.username'),
            "password" => config('payment_gateway.password'),
            "orderNumber" => $orderId,
            "amount" => $donate,
            "returnUrl" => "${_SERVER['REQUEST_SCHEME']}://${_SERVER['HTTP_HOST']}/doika/payment-record-db/${id}/${orderId}?backUrl=" . urlencode("${backUrl}status=success"),
            "failUrl" => "${_SERVER['REQUEST_SCHEME']}://${_SERVER['HTTP_HOST']}/doika/payment-record-db/${id}/${orderId}?backUrl=" . urlencode("${backUrl}status=fail")
        ];

        $client = new Client([
            'base_uri' => config('payment_gateway.url'),
            'query' => $getTokenParams
        ]);

        $response = $client->request('POST', 'register.do');
        $paymentPageMeta = json_decode($response->getBody());
        if (isset($paymentPageMeta->errorCode)) {
            error_log("Error when registering payment: $paymentPageMeta->errorCode - $paymentPageMeta->errorMessage");
            $paymentPageMeta->formUrl = "${backUrl}status=fail&statusMessage=" . urlencode($paymentPageMeta->errorMessage);
        } else {
            $payment = new Payment();
            $payment->campaign_id = $id;
            $payment->amount = 0;
            $payment->order_id = $orderId;
            $payment->order_id_gateway = $paymentPageMeta->orderId;
            $payment->save();
        }
        return $paymentPageMeta;
    }
}
