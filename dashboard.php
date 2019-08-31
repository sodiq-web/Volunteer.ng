<?php

sesion_start();

require('includes/db_connection.php');

$userId = $_SESSION['user_id'];

$donations = mysqli_query($con, "SELECT * FROM  donations WHERE user_id = '$userId'") or die(mysqli_error($con));
