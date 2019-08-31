
<?php

require_once('config.php');

$con = mysqli_connect("localhost", "root", "", "ucssdb");
$con = mysqli_connect($databseHost, $databaseUser, $databsePassword, $databaseName);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>

