<?php
require_once "./DBController.php";



function populateDropDown($sql, $select_tag_name) {
  $db_handler = new DBOperations\DBRunQueries();
  $result = $db_handler->runBaseQuery($sql);
  echo "<select name='{$select_tag_name}' >";
  for ($i = 0; $i < count($result); $i++) {
    echo "<option value='" . $result[$i]['initial_id'] . "'>" . $result[$i]['initial'] . "</option>";
  }
  echo "</select>";
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  echo "Submitted";
  if (!empty($_POST['initialٖ'])) {
    echo "<pre>";
    echo ($_POST["initialٖ"]);
    echo "</pre>";
  }
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
    <?php
    $sql = "select * from consultant_initials";
    $tag_name = "initialٖ";
    populateDropDown($sql, $tag_name);

    ?>
    <input type="submit" value="Submit">
  </form>
</body>

</html>