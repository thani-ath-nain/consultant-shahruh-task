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
$_SESSION["type"] = "contact";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Contacts</title>
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
                        <h2 class="pull-left">All Contacts</h2>
                    </div>
                    <?php
                    if (!$_SESSION["is_admin"]) {
                        $result = $db_handle->runQuery("SELECT * from consultant_contacts where consultant_id = ?", "i", array($consul_id));
                    } else {
                        $result = $db_handle->runBaseQuery("SELECT * from consultant_contacts");
                    }

                    if (!empty($result)) {

                        Utilities\BasicUtilities::render_table_markup(array(
                            "#",
                            "Contact Name",
                            "Email",
                            "Phone1",
                            "Phone2",
                            "Last Date Contacted",
                            "Next Date to Contact",
                            "Notes"
                        ));

                        echo "<tbody>";
                        for ($i = 0; $i < count($result); $i++) {
                            # code...
                            $row = $result[$i];
                            echo "<tr>";
                            echo "<td>" . $row['contact_id'] . "</td>";
                            echo "<td>" . $row['contact_name'] . "</td>";
                            echo "<td>" . $row['contact_email'] . "</td>";
                            echo "<td>" . $row['contact_telephone1'] . "</td>";
                            echo "<td>" . $row['contact_telephone2'] . "</td>";
                            echo "<td>" . $row['last_day_contacted'] . "</td>";
                            echo "<td>" . $row['next_day_to_contact'] . "</td>";
                            echo "<td>" . $row['notes_on_last_call'] . "</td>";
                            echo "<td>";
                            echo '<a href="read.php?type=contact&id=' . $row['contact_id'] . '" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                            echo '<a href="update.php?type=contact&id=' . $row['contact_id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                            echo '<a href="delete.php?type=contact&id=' . $row['contact_id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';

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