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
        send_email_to($_SESSION['email'], "Bevestiging cadeaukaart(en)", "giftcard_confirmation.php", [
            'name' => $_SESSION['name'],
            'email' => $_SESSION['email'],
            'couponCodes' => $_SESSION['couponCodes'],
            'couponPrizes' => $_SESSION['couponPrizes']
        ]);
    }
    elseif ($payment->isOpen())
    {
        echo "Uw betaling is niet geslaagd en uw cadeaubon bestelling is niet opgeslagen";
        base_query("UPDATE `Coupon` SET PaymentStatus = 0 WHERE PaymentCode = :paymentCode", [':paymentCode' => $payCode]);
    }
}