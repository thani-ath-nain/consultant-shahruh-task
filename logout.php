<?php
session_start();


require_once "./Util.php";


$util = new Utilities\BasicUtilities();

//Clear Session
$_SESSION["member_id"] = "";
session_destroy();

// clear cookies
$util->clearAuthCookie();

header("Location: ./");
?>