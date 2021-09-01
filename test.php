<?php

require_once "./DBController.php";

$db_handle = new DBOperations\DBRunQueries();

$q = "SELECT customer_id from customers where customer_name =?";
$company =  $_COOKIE["company"];
$res = $db_handle->runQuery($q, "s", array($company));
$cust_id = $res[0]["customer_id"];
echo $cust_id;