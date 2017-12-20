<?php

$payCode = $_GET["PaymentCode"];

if (!isset($_GET["PaymentCode"])) {
    echo "Betaling is niet gelukt.";
    return;
}
elseif (base_query("SELECT PaymentCode FROM `Order` WHERE PaymentCode = :paymentCode", [':paymentCode' => $payCode])->fetch()) {

    $payment_id = $_SESSION["paymentId"];
    base_query("UPDATE `Order` SET PaymentId = :paymentId WHERE PaymentCode = :paymentCode", [':paymentCode' => $payCode, ':paymentId' => $payment_id]);

    require_once 'paymentSystem/Mollie/API/Autoloader.php';

    $mollie = new Mollie_API_Client;
    $mollie->setApiKey('test_RpAVRh4PFgcBMNCqB6DMfFPy9yeBt7');

    $payment    = $mollie->payments->get($payment_id);

    $order_id = $payment->metadata->order_id;

    if ($payment->isPaid())
    {
        echo "Uw Cadeaukaart Bestelling is geslaagd";
        base_query("UPDATE`Order` SET PaymentCode = NULL WHERE PaymentCode = :paymentCode", [':paymentCode' => $payCode]);
    }
    elseif ($payment->isOpen())
    {
        echo "RIP";
        base_query("UPDATE `Order` SET PaymentStatus = 0 WHERE PaymentCode = :paymentCode", [':paymentCode' => $payCode]);
    }
}
