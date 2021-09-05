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
$_SESSION["type"] = "company";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <?php include("./scripts.php"); ?>

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
                        <h2 class="pull-left">All Companies</h2>
                    </div>
                    <?php
                    if (!$_SESSION["is_admin"]) {
                        $result = $db_handle->runQuery("SELECT * from customers where consultant_id = ?", "i", array($consul_id));
                    } else {
                        $result = $db_handle->runBaseQuery("SELECT * from customers");
                    }


                    //echo Util::create_table_markup($result, $headers);
                    if (!empty($result)) {
                        Utilities\BasicUtilities::render_table_markup(array(
                            "#",
                            "Customer Name",
                            "Type",
                            "Address",
                            "Country"
                        ));

                        echo "<tbody>";
                        for ($i = 0; $i < count($result); $i++) {
                            $row = $result[$i];
                            echo "<tr>";
                            echo "<td>" . $row['customer_id'] . "</td>";
                            echo "<td>" . $row['customer_name'] . "</td>";
                            $cust_type = $db_handle->runQuery("SELECT customer_type_name  from customer_types_tbl WHERE customer_type_id = ?", "i", array($row["customer_type"]));

                            echo "<td>" . $cust_type[0]["customer_type_name"] . "</td>";
                            echo "<td>" . $row['customer_address'] . "</td>";
                            echo "<td>" . $row['customer_country'] . "</td>";
                            echo "<td>";
                            echo '<a href="read.php?type=company&id=' . $row['customer_id'] . '" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                            echo '<a href="update.php?type=company&id=' . $row['customer_id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                            echo '<a href="delete.php?type=company&id=' . $row['customer_id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';

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