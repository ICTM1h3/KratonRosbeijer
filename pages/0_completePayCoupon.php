<?php

$payCode = $_GET['PaymentCode'];

if (!isset($_GET['PaymentCode'])) {
    echo "U heeft geen geldige betaling gedaan";
    return;
}
elseif (base_query("SELECT PaymentCode FROM Coupon WHERE PaymentCode = :paymentCode", [':paymentCode' => $payCode])->fetch()) {

    $payment_id = $_SESSION["paymentId"];
    base_query("UPDATE Coupon SET PaymentId = :paymentId WHERE PaymentCode = :paymentCode", [':paymentCode' => $payCode, ':paymentId' => $payment_id]);

    require_once 'paymentSystem/Mollie/API/Autoloader.php';

    $mollie = new Mollie_API_Client;
    $mollie->setApiKey('test_RpAVRh4PFgcBMNCqB6DMfFPy9yeBt7');

    $payment    = $mollie->payments->get($payment_id);

    /*
    * The order ID saved in the payment can be used to load the order and update it's
    * status
    */
    $order_id = $payment->metadata->order_id;

    if ($payment->isPaid())
    {
        echo "Uw Cadeaukaart Bestelling is geslaagd";
        base_query("UPDATE Coupon SET PaymentCode = NULL WHERE PaymentCode = :paymentCode", [':paymentCode' => $payCode]);
    }
    elseif ($payment->isOpen())
    {
        echo "RIP";
    }
}