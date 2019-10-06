<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Donate;
use App\PaymentImplementation;

class DonateController extends Controller
{

    public function donate(Request $request, $id)
    {
        $paymentPageMeta = Donate::getPaymentPage($request, $id);
        return json_encode($paymentPageMeta);
    }

    public function recordPayment(Request $request, $campaignId, $orderId)
    {
        PaymentImplementation::createPayment($request, $campaignId, $orderId);
        return redirect($request->backUrl);
    }
}
