<?php
session_start();

require_once "./DBController.php";
require_once "./Util.php";
require_once "./authCookieSessionValidate.php";



$auth = new DBOperations\DBAuth();
$db_handle = new DBOperations\DBHandler();
$util1 = new Utilities\BasicUtilities();
$util2 = new Utilities\CryptoUtilities();





if ($isLoggedIn) {
  $util->redirect("dashboard.php");
}

if (!empty($_POST["login"])) {
  $isAuthenticated = false;

  $username = $_POST["member_name"];
  $password = $_POST["member_password"];

  $user = $auth->getMemberByUsername($username);
  if (!empty($user)) {
    if (password_verify($password, $user[0]["member_password"])) {
      $isAuthenticated = true;
      $_SESSION["member_id"] = $user[0]["member_id"];
      $_SESSION["is_admin"] = 1;
    }
  } else {
    $user = $auth->getConsultantByUsername($username);
    if (password_verify($password, $user[0]["consultant_pass"])) {
      $isAuthenticated = true;
      $_SESSION["member_id"] = $user[0]["consultant_id"];
      $_SESSION["is_admin"] = 0;
    }
  }


  if ($isAuthenticated) {


    // Set Auth Cookies if 'Remember Me' checked
    if (!empty($_POST["remember"])) {
      setcookie("member_login", $username, $cookie_expiration_time);

      $random_password = $util2->getToken(16);
      setcookie("random_password", $random_password, $cookie_expiration_time);

      $random_selector = $util2->getToken(32);
      setcookie("random_selector", $random_selector, $cookie_expiration_time);

      $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
      $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);

      $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);

      // mark existing token as expired
      $userToken = $auth->getTokenByUsername($username, 0);
      if (!empty($userToken[0]["id"])) {
        $auth->markAsExpired($userToken[0]["id"]);
      }
      // Insert new token
      $auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
    } else {
      $util->clearAuthCookie();
    }
    if ($_SESSION["is_admin"]) {
      $util->redirect("dashboard.php");
    } else {
      $util->redirect("add-company.php");
    }
  } else {
    $message = "Invalid Login";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="generator" content="Hugo 0.84.0" />
  <title>Sign In</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/" />

  <!-- Bootstrap core CSS -->
  <link href="./assets/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>

  <!-- Custom styles for this template -->
  <link href="./signin.css" rel="stylesheet" />
</head>

<body class="text-center">
  <main class="form-signin">
    <form action="" method="POST" id="frmLogin">
      <div class="error-message"><?php if (isset($message)) {
                                    echo $message;
                                  } ?></div>
      <img class="mb-4" src="./assets/brand/invigors.jpg" alt="invigors-logo" width="72" height="57">
      <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

      <div class="form-floating">
        <input name="member_name" type="text" value="<?php if (isset($_COOKIE["member_login"])) {
                                                        echo $_COOKIE["member_login"];
                                                      } ?>" class="form-control" id="floatingInput">
        <label for="floatingInput">Username</label>
      </div>
      <div class="form-floating">

        <input name="member_password" type="password" value="<?php if (isset($_COOKIE["member_password"])) {
                                                                echo $_COOKIE["member_password"];
                                                              } ?>" class="form-control" id="floatingPassword">

        <label for="floatingPassword">Password</label>
      </div>

      <div class="checkbox mb-3">
        <input type="checkbox" name="remember" id="remember" <?php if (isset($_COOKIE["member_login"])) { ?> checked <?php } ?> /> <label for="remember-me">Remember me</label>
      </div>
      <input type="submit" name="login" value="Login" class="w-100 btn btn-lg btn-primary btn-success">
    </form>
  </main>
</body>

</html>


<!--
  TODO: Remake dashboard with template
  TODO: Adminn can view all consultants
  TODO: Consultant can add project
  TODO: Consultant can view his companies and his contacts there
  TODO: Consultant can view all his projects
  TODO: Admin can view all companies , contacts
  TODO: Admin can view all projects
  TODO: Better organization of files


-->