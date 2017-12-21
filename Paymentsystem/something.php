<?php

require "Mollie/API/Autoloader.php";

$mollie = new Mollie_API_Client;
$mollie->setApiKey("test_d2GTnQhp2BVUNcVhffcsJJu8KdCeNq");  

$payment = $mollie->payments->create(array(
    "amount"      => 10.00,
    "description" => "My first API payment",
    "redirectUrl" => "kratonrosbeijer.nl",
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
));

$payment = $mollie->payments->get($payment->id);

if ($payment->isPaid())
{
    echo "Payment received.";
}

$issuers = $mollie->issuers->all();

$payment = $mollie->payments->create(array(
    "amount"      => 10.00,
    "description" => "My first API payment",
    "redirectUrl" => "https://webshop.example.org/order/12345/",
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
    "method"      => Mollie_API_Object_Method::IDEAL,
    "issuer"      => $selected_issuer_id, // e.g. "ideal_INGBNL2A"
));

$payment = $mollie->payments->get($payment->id);

// Refund € 15 of this payment
$refund = $mollie->payments->refund($payment, 15.00);
