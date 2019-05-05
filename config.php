<?php
$currency = '&#x20AB '; //Currency Character or code

$db_username = 'dbui9256_quyen';
$db_password = 'quyendocthan';
$db_name = 'dbui9256_store';
$db_host = '112.78.2.75';

$shipping_cost      = 1000; //shipping cost
$taxes              = array( //List your Taxes percent here.
                            'VAT' => 12, 
                            'Service Tax' => 5
                            );						
//connect to MySql						
$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);						
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
?>