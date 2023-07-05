<?php

namespace App\PaymentChannels;

use App\Models\LMS\Order;
use App\Models\LMS\PaymentChannel;
use Illuminate\Http\Request;

interface IChannel
{
    /**
     * IChannel constructor.
     * @param PaymentChannel $paymentChannel
     */
    public function __construct(PaymentChannel $paymentChannel);

    /**
     * @param Order $order
     * @return Order
     */
    public function paymentRequest(Order $order);

    /**
     * @param Request $request
     * @return mixed
     */
    public function verify(Request $request);
}
