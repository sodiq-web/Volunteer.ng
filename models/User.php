<?php

class User
{

    //Database Connection
    private $conn;
    private $table = 'users';

    //Properties
    public $user_id;
    public $name;
    public $email;
    public $password;
    public $description;
    public $donation_amount;
    public $date_created;
    public $is_completed;
    public $is_active;
    public $title;
    public $type;

    // Constructor with database
    public function __construct($db)
    {
        $this->conn = $db;
    }
}
