<?php
//TODO: Check if logged in
session_start();


require_once "./DBController.php";
require_once "./Util.php";
require_once "./authCookieSessionValidate.php";


if (!$isLoggedIn || !(isset($_SESSION["company"]) ) ) {
    $util->redirect("index.php");
}



$db_handle = new DBOperations\DBRunQueries();
$util = new Utilities\BasicUtilities();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contact_name = $_POST["name"];
    $contact_email = $_POST["email"];
    $contact_phone1 = $_POST["phone1"];
    $contact_phone2 = $_POST["phone2"];
    $contact_last_contacted = $_POST["last_contact"];
    $contact_next_date_to_contact = $_POST["next_date_to_contact"];
    $notes = $_POST["notes"];

    $q = "SELECT customer_id from customers where customer_name =?";
    $company =  $_SESSION["company"];
    $res = $db_handle->runQuery($q, "s", array($company));
    $cust_id = $res[0]["customer_id"];

    $consul_id = $_SESSION["member_id"];

    $query = "INSERT INTO consultant_contacts
    (contact_name,contact_email,contact_telephone1,contact_telephone2,last_day_contacted,next_day_to_contact,
    notes_on_last_call,customer_id, consultant_id) 
    VALUES(?,?,?,?,?,?,?,?,?)";
    $db_handle->insert(
        $query,
        "sssssssii",
        array(
            $contact_name,
            $contact_email,
            $contact_phone1,
            $contact_phone2,
            $contact_last_contacted,
            $contact_next_date_to_contact,
            $notes,
            $cust_id,
            $consul_id
        )
    );
    if (isset($_POST["submit"])) {
        $util->redirect("add-project.php");
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Add Contacts</title>
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
                        <h1>Add Contact</h1>
                        <p class="lead">
                            Enter the details of your contact at <?php echo "<b>{$_SESSION["company"]}</b>"; ?>
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form action="" method="post">ٖ


                                    <div class="form-group">
                                        <label>Contact Name</label>
                                        <input class="form-control form-control-lg" type="text" name="name" placeholder="Enter the contact's name" required>
                                    </div>


                                    <div class=" form-group">
                                        <label>Contact Email</label>
                                        <input class="form-control form-control-lg" type="email" name="email" placeholder="Enter their Email" required>
                                    </div>

                                    <div class=" form-group">
                                        <label for="phone1">Enter first phone number:</label><br><br>
                                        <input class="form-control form-control-lg" type="tel" id="phone1" name="phone1" placeholder="xxxx-xxxxxxx" pattern="[0-9]{4}-[0-9]{7}" required>
                                    </div>

                                    <div class=" form-group">
                                        <label for="phone2">Enter second phone number:</label><br><br>
                                        <input class="form-control form-control-lg" type="tel" id="phone2" name="phone2" placeholder="xxxx-xxxxxxx" pattern="[0-9]{4}-[0-9]{7}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="last-contact">Last Date Contacted</label>
                                        <input class="form-control form-control-lg" id="last-contact" type="date" name="last_contact" required>
                                    </div>


                                    <div class="form-group">
                                        <label for="next-contact">Next Date To Contact</label>
                                        <input class="form-control form-control-lg" id="next-contact" type="date" name="next_date_to_contact" required>
                                    </div>


                                    <div class="form-group" ٖ>
                                        <label for="notes-last">Notes on Last Call</label>
                                        <textarea class="form-control" id="notes-last" name="notes" rows="7"></textarea>
                                    </div>
                                    <div class="text-center mt-3">
                                        <input type="submit" name="add" value="Add" class="btn btn-lg btn-success"></input>
                                        <input type="submit" name="submit" value="Submit" class="btn btn-lg btn-success"></input>
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