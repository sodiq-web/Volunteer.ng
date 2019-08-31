<?php
session_start();

require('includes/db_connection.php');


if (isset($_POST['signup'])) {
    $email_unsafe = $_POST['email'];
    $pass_unsafe = $_POST['password'];
    $pass_conf_unsafe = $_POST['password_confirmation'];
    $name_unsafe = $_POST['name'];
    $title_unsafe = $_POST['title'];
    $desc_unsafe = $_POST['desc'];
    $amount_unsafe = $_POST['amount'];

    $email = mysqli_real_escape_string($con, $email_unsafe);
    $password = mysqli_real_escape_string($con, $pass_unsafe);
    $password_confirmation = mysqli_real_escape_string($con, $pass_conf_unsafe);
    $name = mysqli_real_escape_string($con, $name_unsafe);
    $title = mysqli_real_escape_string($con, $title_unsafe);
    $desc = mysqli_real_escape_string($con, $desc_unsafe);
    $amount = mysqli_real_escape_string($con, $amount_unsafe);

    //Query DB for details
    $user_check_query = mysqli_query($con, "SELECT * FROM user WHERE email = '$email'") or die(mysqli_error($con));
    $count = mysqli_num_rows($user_check_query);

    if ($count == 0) {
        //Display Error here
        addAlert('error', 'Email address already exists');
        echo "<script type='text/javascript'>document.location='signup.php'</script>";
        exit(0);
    } elseif ($password != $password_confirmation) {
        addAlert('error', 'Passwords dont Match');
        echo "<script type='text/javascript'>document.location='signup.php'</script>";
        exit(0);
    } elseif (!validateEmail($email)) {
        addAlert('error', 'Invalid Email address');
        echo "<script type='text/javascript'>document.location='signup.php'</script>";
        exit(0);
    } else {
        //process registration here

        $result = mysqli_query($con, "INSERT INTO users SET name= '$name', email = '$email', password = '$password', title = '$title', description = '$desc', donation_amount = '$amount'") or die(mysqli_error($con));

        if ($result) {
            addAlert('success', 'Welcome to Volunteer! Kindly Login to begin');
            echo "<script type='text/javascript'>document.location='login.php'</script>";
        } else {
            addAlert('success', 'Something went wrong! Conteact volunteerng@gmail.com');
            echo "<script type='text/javascript'>document.location='login.php'</script>";
        }
    }
}

?>

<!-- 
    
Front End Magic Here

Guide:

Input field name for user email: `email`
Input field name for user password: `password`
Input field name for password confirmation: `password_confirm`
Input field name for full name: `name`
Input field name for title of cause: `title`
Input field name for cause description: `desc`
Input field name for donation amount needed: `amount`
Submit botton name for form: `signup`

 -->