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
        echo "Uw bestelling is geslaagd en opgeslagen!";
        $isVipUser = false;
        if (isset($_SESSION['UserId'])) {
            $isVipUser = base_query("SELECT Role FROM USER WHERE Id = :id", [':id' => $_SESSION['UserId']])->fetchColumn() == ROLE_VIP_USER;
        }
        send_email_to($_SESSION['e-mail'], "Bevestiging bestelling", "order_confirmation", [
            'dishes' => $_SESSION['dishname'],
            'amountDishes' => $_SESSION['amountDishes'],
            'categories' => $_SESSION['categoryname'],
            'amountCategories' => $_SESSION['amountCategories'],
            'dishPrices' => $_SESSION['dishPrices'],
            'categoryPrices' => $_SESSION['categoryPrices'],
            'dishSubTotal' => $_SESSION['dishSubTotal'],
            'categorySubTotal' => $_SESSION['categorySubTotal'],
            'dishCumulative' => $_SESSION['dishCumulative'],
            'categoryCumulative' => $_SESSION['categoryCumulative'],
            'totalPrice' => $_SESSION['totalPrice'],
            'name' => $_SESSION['name'],
            'email' => $_SESSION['e-mail'],
            'telNumber' => $_SESSION['telephoneNumber'],
            'date' => $_SESSION['date'],
            'time' => $_SESSION['time'],
            'discount' => $_SESSION['discount'],
            'isVipUser' => $isVipUser
        ]);
    }
    elseif ($payment->isOpen())
    {
        echo "Uw betaling is niet geslaagd en uw bestelling is niet opgeslagen";
        base_query("UPDATE `Order` SET PaymentStatus = 0 WHERE PaymentCode = :paymentCode", [':paymentCode' => $payCode]);
    }
}
