<?php

use Utilities\BasicUtilities;

require_once "./DBController.php";
require_once "./Util.php";
// Initialize Variables

$fname = $lname = $email = $username = $password = $confirm_password = $consultant_initial = "";
$fname_err = $lname_err = $email_err = $username_err = $password_err = $confirm_password_err = "";
// Making object forb DbOPS
$db_handle = new DBOperations\DBRunQueries();
$util = new Utilities\BasicUtilities();
// Processing data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["fname"]))) {
        $fname_err = "Please Enter Your First Name";
    } else {
        $fname = trim($_POST["fname"]);
    }
    if (empty(trim($_POST["lname"]))) {
        $lname_err = "Please Enter Your Last Name";
    } else {
        $lname = trim($_POST["lname"]);
        $username = $fname . $lname;
    }
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please Enter an Email Address";
    } else {
        $email = trim($_POST["email"]);
    }
    if (!empty($_POST['initialٖ'])) {
        $consultant_initial  =  $_POST["initialٖ"];;
    }



    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Make Password Hash
    $password =  password_hash($password, PASSWORD_DEFAULT);

    if (empty($fname_err) && empty($lname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $query = "INSERT INTO consultants(consultant_fname,consultant_lname,consultant_email,consultant_username,consultant_pass,initial_id ) values(?,?,?,?,?,?)";
        $db_handle->insert($query, 'sssssi', array($fname, $lname, $email, $username, $password, $consultant_initial));
        $util->redirect("dashboard.php");
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
                        <h1 class="h2">Add Consultant</h1>
                        <p class="lead">
                            This is where you givev access to new users.
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form action="" method="post">ٖ
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input class="form-control form-control-lg <?php echo (!empty($fname_err)) ? 'is-invalid'
                                                                                        : ''; ?>" type="text" name="fname" placeholder="Enter their first name" value="<?php echo $fname; ?>" required>

                                        <span class="invalid-feedback"><?php echo $fname_err; ?></span>
                                    </div>
                                    <div class=" form-group">
                                        <label>Last Name</label>
                                        <input class="form-control form-control-lg <?php echo (!empty($lname_err)) ? 'is-invalid'
                                                                                        : ''; ?>" type="text" name="lname" placeholder="Enter their last name" required value="<?php echo $lname; ?>">
                                        <span class=" invalid-feedback"><?php echo $lname_err; ?></span>
                                    </div>
                                    <div class=" form-group">
                                        <label>Email</label>
                                        <input class="form-control form-control-lg <?php echo (!empty($email_err)) ? 'is-invalid'
                                                                                        : ''; ?>" type="text" name="email" placeholder="Enter their Email" required value="<?php echo $email; ?>">

                                        <span class=" invalid-feedback"><?php echo $email_err; ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Password</label>
                                        <input class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" type="password" name="password" placeholder="Give them a password" required value="<?php echo $password; ?>">
                                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input class="form-control form-control-lg <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" type="password" name="confirm_password" placeholder="Once For Good Measure" required value="<?php echo $confirm_password; ?>">
                                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                                    </div>
                                    <?php
                                    $sql = "select * from consultant_initials";
                                    $tag_name = "initialٖ";
                                    $class_name = "form-select";
                                    $id_col = "initial_id ";
                                    $val_col = "initial";
                                    $db_handle->populateDropDownFromDB($sql, $tag_name, $class_name, $id_col, $val_col); ?>

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

<!--
    TODO: Add class name directly
    ..>