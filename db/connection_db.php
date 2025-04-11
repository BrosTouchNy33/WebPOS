<?php
    // connection_db.php
    // $host = "localhost";
    // $dbUsername = "root";
    // $dbPassword = "";
    // $dbName = "pos_mart";
    //$port = 3306; // Use the correct port (3306 or 3307)

    // Establish database connection
    $conn = new mysqli("localhost", "root", "root", "pos_mart");


    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
?>