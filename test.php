<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $password = $_POST["password"];
  $password =  password_hash($password, PASSWORD_DEFAULT) . "\n";
  echo $password;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form action="" method="post">
    <input type="password" name="password" id="">
    <input type="submit" value="Submit">
  </form>
</body>

</html>