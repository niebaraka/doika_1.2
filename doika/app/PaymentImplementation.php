<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class PaymentImplementation extends Model
{

    static public function createPayment($request, $campaignId, $orderId)
    {
        $payment = Payment::where('order_id', $orderId)->where('campaign_id', $campaignId)->firstOrFail();
        $getTransactionStatus = [
            "userName" => config('payment_gateway.username'),
            "password" => config('payment_gateway.password'),
            "orderId" => $payment->order_id_gateway
        ];
        $client = new Client([
            'base_uri' => config('payment_gateway.url'),
            'query' => $getTransactionStatus
        ]);

        $response = $client->request('GET', 'getOrderStatus.do');
        $responseBody = json_decode($response->getBody());
        if ($responseBody->OrderStatus == "2") {
            $payment->amount = $responseBody->Amount * 0.01;
            $payment->save();
        } else {
            error_log('Payment failed. OrderStatus: ' . $responseBody->OrderStatus . ' ErrorCode: ' . $responseBody->ErrorCode . ' ErrorMessage: ' . $responseBody->ErrorMessage);
        }
    }
}
