<?php
require_once "./DBController.php";
require_once "./Util.php";
$db_handle = new DBOperations\DBRunQueries();
$util = new Utilities\BasicUtilities();
print_r($_GET);
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    if ($_GET["type"] == "contact") {
        echo "type is contact";
        sleep(2);
        $param_id = trim($_POST["id"]);
        $is_query_successful = $db_handle->delete("DELETE from consultant_contacts where contact_id = ?", "i", array($param_id));

        if ($is_query_successful) {
            $util->redirect("all-contacts.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>" />
                            <p>Are you sure you want to delete this record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php" class="btn btn-secondary ml-2">No</a>
                                <!-- 
                                    Add PHP code to link to different pages
    -->
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>