<?php

class Donation
{

    //Database Connection
    private $conn;
    private $table = 'donations';

    //Properties
    public $donation_id;
    public $user_id;
    public $name_of_donor;
    public $email_of_donor;
    public $amount;
    public $transaction_ref;
    public $status;
    public $message;


    // Constructor with database
    public function __construct($db)
    {
        $this->conn = $db;
    }
}
