<?php

session_start();


require_once "./DBController.php";
require_once "./Util.php";
// Initialize Variables

require_once "authCookieSessionValidate.php";


if (!$isLoggedIn) {
    $util->redirect("index.php");
}



$company_name = $company_address  = $company_type = $company_country = "";
$company_name_err = $company_address_err = "";
// Making object forb DbOPS
$db_handle = new DBOperations\DBRunQueries();
$util = new Utilities\BasicUtilities();
// Processing data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["name"]))) {
        $fname_err = "Please Enter A Company Name";
    } else {
        $company_name = trim($_POST["name"]);
    }
    if (empty(trim($_POST["address"]))) {
        $company_address = "Please Enter An Address";
    } else {
        $company_address = trim($_POST["address"]);
    }

    $company_country = $_POST["country"];
    $company_type = $_POST["types"];

    if (empty($company_name_err) && empty($company_address_err)) {
        $query = "INSERT INTO customers(customer_name, customer_type , customer_address , customer_country , consultant_id) values(?,?,?,?,?)";
        $consul_id = $_SESSION["member_id"];
        echo $_SESSION["member_id"];
        $db_handle->insert($query, 'ssssi', array($company_name, $company_type, $company_address, $company_country, $consul_id));
        $_SESSION["company"] = $company_name;
        $util->redirect("add-contacts.php");
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Add Consultants</title>
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
                        <h1 class="h2">Add New Companies</h1>
                        <p class="lead">
                            This is where you tell us about newly acquired customers.
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form action="" method="post">ٖ
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input class="form-control form-control-lg <?php echo (!empty($company_name_err)) ? 'is-invalid'
                                                                                        : ''; ?>" type="text" name="name" placeholder="Enter the company name" value="<?php echo $company_name; ?>" required>

                                        <span class="invalid-feedback"><?php echo $company_name_err; ?></span>
                                    </div>
                                    <div class=" form-group">
                                        <label>Company Address</label>
                                        <input class="form-control form-control-lg <?php echo (!empty($company_address_err)) ? 'is-invalid'
                                                                                        : ''; ?>" type="text" name="address" placeholder="Enter Address" required value="<?php echo $company_address; ?>">
                                        <span class=" invalid-feedback"><?php echo $company_address_err; ?></span>
                                    </div>


                                    <?php
                                    include "./countries.php"; // Gets countries
                                    echo "<br>";
                                    $sql = "select * from customer_types_tbl";
                                    $tag_name = "types";
                                    $class_name = "form-select";
                                    $val_col = "customer_type_name";
                                    $db_handle->populateDropDownFromDB($sql, $tag_name, $class_name, "customer_type_id", $val_col);

                                    ?>

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