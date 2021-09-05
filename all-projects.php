<?php
session_start();

require_once "./DBController.php";
require_once "./Util.php";
require_once "./authCookieSessionValidate.php";



$db_handle = new DBOperations\DBRunQueries();

if (!$isLoggedIn) {
    $util->redirect("index.php");
}
$consul_id = $_SESSION["member_id"];
$_SESSION["type"] = "project";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Projects</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }

        table tr td:last-child {
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">All Projects</h2>
                    </div>
                    <?php

                    if (!$_SESSION["is_admin"]) {
                        $result = $db_handle->runQuery("SELECT * from projects where consultant_id = ?", "i", array($consul_id));
                    } else {
                        $result = $db_handle->runBaseQuery("SELECT * from projects");
                    }

                    if (!empty($result)) {

                        Utilities\BasicUtilities::render_table_markup(array(
                            "#",
                            "Project Name",
                            "Project Frequency",
                            "Month Completes",
                            "Type",
                            "Completed",
                            "Company"
                        ));
                        echo "<tbody>";
                        for ($i = 0; $i < count($result); $i++) {
                            # code...
                            $row = $result[$i];
                            echo "<tr>";
                            echo "<td>" . $row['project_id'] . "</td>";
                            echo "<td>" . $row['proj_name'] . "</td>";
                            $proj_freq = $db_handle->runQuery("SELECT freq_type  from project_freq WHERE freq_id  = ?", "i", array($row["proj_freq"]));

                            echo "<td>" . $proj_freq[0]["freq_type"] . "</td>";
                            echo "<td>" . date('m', strtotime($row['month_completes']));
                            $proj_type = $db_handle->runQuery("SELECT proj_type  from proj_types WHERE proj_type_id  = ?", "i", array($row["project_type"]));
                            echo "<td>" . $proj_type[0]["proj_type"] . "</td>";
                            if ($row["is_completed"]) {
                                echo "<td>" . "Yes" . "</td>";
                            } else {
                                echo "<td>" . "No" . "</td>";
                            }
                            $company_name = $db_handle->runQuery("SELECT customer_name FROM customers WHERE customer_id =? ", "i", array($row["company_id"]));

                            echo "<td>" . $company_name[0]["customer_name"] . "</td>";
                            echo "<td>";
                            echo '<a href="read.php?type=project&id=' . $row['project_id'] . '" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                            echo '<a href="update.php?type=project&id=' . $row['project_id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                            echo '<a href="delete.php?type=project&id=' . $row['project_id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';

                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>