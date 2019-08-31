<?php
session_start();

require('includes/db_connection.php');


if (isset($_POST['login'])) {
    $email_unsafe = $_POST['email'];
    $pass_unsafe = $_POST['password'];

    $email = mysqli_real_escape_string($con, $email_unsafe);
    $password = mysqli_real_escape_string($con, $pass_unsafe);

    //Query DB for details
    $check_query = mysqli_query($con, "SELECT * FROM user WHERE email = '$email' AND password = '$password'") or die(mysqli_error($con));
    $count = mysqli_num_rows($check_query);

    if ($count == 0) {
        //Display Error here
        addAlert('error', 'Invalid Details Provided');
        echo "<script type='text/javascript'>document.location='login.php'</script>";
    } else {
        //process login here

        $user = mysqli_fetch_array($check_query);
        $isUserActive = $user['is_active'];
        $userId = $user['user_id'];
        $userFullName = $user['name'];

        $_SESSION['user_id'] = $userId;
        $_SESSION['name'] = $userFullName;

        addAlert('success', 'You Successfully Logged in');

        //Redirect to User Dashboard
        echo "<script type='text/javascript'>document.location='dashboard.php'</script>";
        exit(0);
    }
}

?>

<!-- 
    
Front End Magic Here

Guide:

Input field name for user email: `email`
Input field name for user password: `password`
Submit botton name for form: `login`

 -->