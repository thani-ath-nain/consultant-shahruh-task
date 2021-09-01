<?php
//TODO: Check if logged in

use Utilities\BasicUtilities;

require_once "./DBController.php";
require_once "./Util.php";

$db_handle = new DBOperations\DBRunQueries();
$util = new Utilities\BasicUtilities();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_name = $_POST["name"];
    $proj_freq = $_POST["freqs"];
    $proj_type = $_POST["types"];

    if (isset($_POST["proj_status"])) {
        $status = 1;
    } else {
        $status = 0;
    }

    $proj_completes_date = $_POST["month"];

    //TODO: Make it into a function

    $q = "SELECT customer_id from customers where customer_name =?";
    $company =  $_COOKIE["company"];
    $res = $db_handle->runQuery($q, "s", array($company));
    $cust_id = $res[0]["customer_id"];

    $query = "INSERT INTO projects
    (proj_freq,proj_name,month_completes,project_type,is_completed,company_id) 
    VALUES(?,?,?,?,?,?)";
    $db_handle->insert(
        $query,
        "issiii",
        array($proj_freq, $project_name, $proj_completes_date, $proj_type, $status, $cust_id)
    );

    $util->redirect("night.php");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Add Projects</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">

                    <div class="text-center mt-4">
                        <h1>Add A New Project</h1>
                        <p class="lead">
                            Enter the details of your project with <?php echo "<b>{$_COOKIE["company"]}</b>"; ?>
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form action="" method="post">ٖ


                                    <div class="form-group">
                                        <label>Project Name</label>
                                        <input class="form-control form-control-lg" type="text" name="name" placeholder="Enter the project's name" required>
                                    </div>


                                    <?php
                                    $sql = "select * from project_freq";
                                    $tag_name = "freqs";
                                    $class_name = "form-select";
                                    $val_col = "freq_type";
                                    $db_handle->populateDropDownFromDB($sql, $tag_name, $class_name, "freq_id", $val_col);

                                    ?>

                                    <br>
                                    <div class="form-group">
                                        <label for="next-contact">Completion Month</label>
                                        <input class="form-control form-control-lg" id="next-contact" type="date" name="month" required>
                                    </div>

                                    <?php
                                    $sql = "select * from proj_types";
                                    $tag_name = "types";
                                    $class_name = "form-select";
                                    $val_col = "proj_type";
                                    $db_handle->populateDropDownFromDB($sql, $tag_name, $class_name, "proj_type_id", $val_col);

                                    ?>

                                    <br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="proj_status" id="complete-project">
                                        <label class="form-check-label" for="complete-project">
                                            Completed Project
                                        </label>
                                    </div>
                                    <div class="text-center mt-3">
                                        <input type="submit" name="add" class="btn btn-lg btn-success"></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        body {
            margin-top: 20px;
            background-color: #f2f3f8;
        }

        .card {
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 15px 1px rgba(52, 40, 104, .08);
        }

        .card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #e5e9f2;
            border-radius: .2rem;
        }
    </style>
</body>

</html>