<?php
session_start();
require_once "./DBController.php";
require_once "./Util.php";




function render_read_crud($type, $table_name, $id_col, $headings_arr, $table_cols, $file_to_redirect) {
    $db_handle = new DBOperations\DBRunQueries();
    $util = new Utilities\BasicUtilities();

    if ($_SESSION["type"] == $type) {
        $sql = "SELECT * FROM " . $table_name .  " WHERE " . $id_col . "= ?";

        $param_id = trim($_GET["id"]);
        $result = $db_handle->runQuery($sql, "i", array($param_id));

        if (count($result) == 1) {
            Utilities\BasicUtilities::render_read_crud_data(
                $headings_arr,
                $result,
                $table_cols
            );
        } else {
            $util->redirect("error.php");
        }
        echo  '<p><a href=' . $file_to_redirect . 'class="btn btn-primary">Back</a></p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
                    <h1 class="mt-5 mb-3">View Record</h1>
                    <?php
                    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
                        if ($_SESSION["type"] == "company") {
                            render_read_crud(
                                "company",
                                "customers",
                                "customer_id",
                                array("Customer ID", "Customer Name", "Customer Type", "Customer Address", "Customer Country", "Consultant ID"),
                                array("customer_id", "customer_name", "customer_type", "customer_address", "customer_country", "consultant_id"),
                                "all-companies.php"
                            );
                            // Contact
                        } elseif ($_SESSION["type"] == "contact") {
                            render_read_crud(
                                "contact",
                                "consultant_contacts",
                                "contact_id",
                                array("Contact ID", "Contact Name", "Contact Email", "Contact Telephone 1", "Contact Telephone 2", "Last Date Contacted", "Next Day to Contact", "Notes on Last Call", "Customer ID", "Consultant ID"),
                                array("contact_id", "contact_name", "contact_email", "contact_telephone1", "contact_telephone2", "last_day_contacted", "next_day_to_contact", "notes_on_last_call", "customer_id", "consultant_id"),
                                "all-contacts.php"
                            );
                        } elseif ($_SESSION["type"] == "project") {
                            render_read_crud(
                                "project",
                                "projects",
                                
                                "project_id",
                                array("Project ID", "Project Frequency", "Project Name", "Date Completes", "Project Type", "Completed", "Company ID", "Consultant ID"),
                                array("project_id", "proj_freq", "proj_name", "month_completes", "project_type", "is_completed", "company_id", "consultant_id"),
                                "all-projects.php"
                            );
                        } elseif ($_SESSION["type"] == "consultant") {
                            render_read_crud(
                                "consultant",
                                "consultants",
                                "consultant_id ",
                                array("Consultant ID", "Consultant fName", "Consultant lName", "Consultant Email", "Consultant Username", "Consultant Password (Hashed)", "Initials ID"),
                                array("consultant_id", "consultant_fname", "consultant_lname", "consultant_email", "consultant_username", "consultant_pass", "initial_id "),
                                "all-consultants.php"
                            );
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</body>

</html>