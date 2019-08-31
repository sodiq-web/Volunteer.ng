<?php
require('includes/config.php');
require('includes/functions.php');


if (!isset($_GET['user']) || !isset($_GET['email']) || !isset($_GET['amount'])) {
  header('Location: index.php');
  exit(0);
}

$userId = $_GET['user'];
$email = $_GET['email'];
$amount = $_GET['amount'];

$reference = generateRef($userId);

$redirectUrl = $baseUrl . '/process_payment.php';

$curl = curl_init();

$customer_email = $email;
$currency = "NGN";
$txref = $reference; //unique references for transaction.
$PBFPubKey = $flutterWavePublicKey; //FlutterWave Public Key
$redirect_url = $redirectUrl;
$first_name = 'Jude';
$last_name = 'Jonathan';
$button = 'Donate';
$title = 'VolunteerNG';


curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'amount' => $amount,
    'customer_email' => $customer_email,
    'customer_firstname' => $first_name,
    'customer_lastname' => $last_name,
    'pay_button_text' => $button,
    'custom_title' => $title,
    'currency' => $currency,
    'txref' => $txref,
    'PBFPubKey' => $PBFPubKey,
    'redirect_url' => $redirect_url

  ]),
  CURLOPT_HTTPHEADER => [
    "content-type: application/json",
    "cache-control: no-cache"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

if ($err) {
  // there was an error contacting the rave API
  die('Curl returned error: ' . $err);
}

$transaction = json_decode($response);

if (!$transaction->data && !$transaction->data->link) {
  // there was an error from the API
  print_r('API returned error: ' . $transaction->message);
}

// redirect to page so Donor can pay
header('Location: ' . $transaction->data->link);
