<?php

include('includes/config.php');
//Here, we process payment, whether succcessfull or failed and update our databse.


if (isset($_GET['txref'])) {
    $ref = $_GET['txref'];
    $amount = ""; //Correct Amount from Server
    $currency = ""; //Correct Currency from Server

    $query = array(
        "SECKEY" => $flutterWaveSecretKey,
        "txref" => $ref
    );

    $data_string = json_encode($query);

    $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $response = curl_exec($ch);

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    curl_close($ch);

    $resp = json_decode($response, true);

    $paymentStatus = $resp['data']['status'];
    $chargeResponsecode = $resp['data']['chargecode'];
    $chargeAmount = $resp['data']['amount'];
    $chargeCurrency = $resp['data']['currency'];

    //Donor details
    $fullName = $resp['data']['custname'];
    $email = $resp['data']['custemail'];

    if (($chargeResponsecode == "00" || $chargeResponsecode == "0")  && ($chargeCurrency == $currency)) {
        // transaction was successful...
        // please check other things like whether you already gave value for this ref
        // if the email matches the customer who owns the product etc
        //Give Value and return to Success page

        //Get User ID from transaction ref
        $uArray = explode('-', $txref);
        $userId = $uArray[0];

        $user_query = mysqli_query($con, "SELECT * FROM users WHERE user_id = '$userId'") or die(myslqli_error($con));
        $row = mysqli_fetch_array($user_query);

        //Get total donations for user
        $user_donation_query = mysqli_query($con, "SELECT SUM(amount) as totalDonations FROM donations WHERE user_id = '$userId'") or die(myslqli_error($con));
        $donation_row = mysql_fetch_array($user_donation_query);

        $totalDonations = $donation_row['totalDonations'];
        $sum = $totalDonations + $amount;
        $neededAmount = $row['donation_amount'];


        if ($sum >= $neededAmount) {
            //Funding already gotten, update to completed!
            mysqli_query($con, "UPDATE users SET is_completed = 1 WHERE user_id = '$userId'") or die(myslqli_error($con));
        }

        //query db and add new donation
        $queryDB = mysqli_query($con, "INSERT INTO donations SET status = 'Successful', transaction_ref = '$txref', donation_amount = '$amount', user_id = '$userId', email_of_donor = '$email', name_of_donor = '$fullName'");
        if ($queryDB) {
            addAlert('success', 'Donation Received successfully!');

            //TODO: Redirect to payment success page
            echo "<script type='text/javascript'>document.location='index.php'</script>";
            exit(0);
        } else {
            addAlert('error', 'Transaction Successful, but something went wrong. Contect volunteerng@gmail.com');

            echo "<script type='text/javascript'>document.location='index.php'</script>";
            exit(0);
        }
    } else {
        //Trasanction failed
        addAlert('error', 'Transaction Failed Please try again');

        echo "<script type='text/javascript'>document.location='index.php'</script>";
        exit(0);
    }
} else {

    header('Location: index.php');
}
