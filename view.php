<?php
include('includes/database.php');

if (isset($_GET['view'])) {
    $Get_id = $_GET['view'];
    setcookie("quote", $_GET['view']);
} elseif (isset($_GET['viewversion'])) {
    $Get_id = $_GET['viewversion'];
}

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($_POST['qty'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $submitter = filter_var($_POST['author'], FILTER_SANITIZE_STRING);
    $reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);
    $Get_id = filter_var($_POST['postID'], FILTER_SANITIZE_STRING);


    //Adding Client if not exist
    $checkName = "SELECT * FROM `clients` WHERE `name` = '" . $name . "'";
    $resultC = $conn->query($checkName);

    if ($resultC->num_rows > 0) {
        while ($row = $resultC->fetch_assoc()) {
            $relationID = $row["id"];
        }
    } else {
        $sqlPerson = "INSERT INTO clients (name, contact) VALUES ('$name', '$number')";
        if ($conn->query($sqlPerson) === TRUE) {
            $relationID = $conn->insert_id;
            echo "Quote submitted successfully";
        } else {
            echo "Error: " . $sqlPerson . "<br>" . $conn->error;
        }
    }

    //Add quote with incremented version every save and also updating with latest


    $checkName = "SELECT * FROM `quote_versions` WHERE `quote_id` = '" . $Get_id . "'";
    $resultC = $conn->query($checkName);

    if ($resultC->num_rows > 0) {
        while ($row = $resultC->fetch_assoc()) {
            $version = $row['version'] + 1;
            $quote_id = $_COOKIE["quote"];
            $sqlData = "INSERT INTO quote_versions (description, quantity, price, submitter, reason, clientid, version, quote_id, date)
        VALUES ('$description', '$quantity', '$price', '$submitter', '$reason', '$relationID', '$version', '$quote_id', now())";

            if ($conn->query($sqlData) === TRUE) {
                echo "Quote submitted successfully";
            } else {
                echo "Error: " . $sqlData . "<br>" . $conn->error;
            }
            header('Location:index.php');
        }
    } else {
        $version = 1;
        $quote_id = $_COOKIE["quote"];
        $sqlData = "INSERT INTO quote_versions (description, quantity, price, submitter, reason, clientid, version, quote_id, date)
        VALUES ('$description', '$quantity', '$price', '$submitter', '$reason', '$relationID', '$version', '$quote_id', now())";

        if ($conn->query($sqlData) === TRUE) {
            echo "Quote submitted successfully";
        } else {
            echo "Error: " . $sqlData . "<br>" . $conn->error;
        }
        header('Location:index.php');
    }
}

?>

<!DOCTYPE HTML>
<html>

<head>

    <title>Quotation</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- External Links -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Internal Links -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
    <?php

    if (isset($_GET['view'])) {
        $id = $_GET['view'];

        $sql = "SELECT * FROM quotes WHERE id = $id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $getID = "SELECT * FROM `clients` WHERE `id` = '" . $row['clientid'] . "'";
                $resultC = $conn->query($getID);

                if ($resultC->num_rows > 0) {
                    while ($rowC = $resultC->fetch_assoc()) {
                        $name = $rowC["name"];
                        $number = $rowC["contact"];
                    }
                }
    ?>
    <div class="space-medium">
        <div class="container">
            <form method="post" action="view.php">
                <div class="service-form">
                    <div class="row">
                        <input type="text" name="postID" value="<?php echo $Get_id; ?>" hidden>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb10 ">
                            <h3><b>Edit</b> Quote for : <b>Computer Supplies</b></h3>
                        </div>
                        <hr />
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                            <div class="form-group service-form-group">
                                <label class="control-label  " for="name">Name of Client</label>
                                <input id="name" type="text" placeholder="Name of Client" name="name"
                                    class="form-control" value="<?php echo $name; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12  ">
                            <div class="form-group service-form-group">
                                <label class="control-label  " for="contact">Contact no of client</label>
                                <input id="contact" type="number" placeholder="Contact no of client" name="contact"
                                    class="form-control" value="<?php echo $number; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group service-form-group">
                                <label class="control-label  " for="description">Description</label>
                                <input id="description" type="text" placeholder="Description" name="description"
                                    class="form-control" value="<?php echo $row["description"]; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group service-form-group">
                                <label class="control-label  " for="qty">Quantity</label>
                                <input id="qty" type="number" placeholder="Quantity" name="qty" class="form-control"
                                    value="<?php echo $row["quantity"]; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group service-form-group">
                                <label class="control-label  " for="website">Unit Price</label>
                                <input id="price" type="number" placeholder="Unit Price" name="price"
                                    class="form-control" value="<?php echo $row["price"]; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group service-form-group">
                                <p>Submitted by : <?php echo $row["submitter"]; ?></p>
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <select class="form-control" onchange="javascript:location.href = this.value;">
                                <option>Select an older version </option>
                                <?php
                                            $quote_id = $row['id'];
                                            $query = "SELECT * FROM quote_versions WHERE quote_id = '$quote_id' ORDER BY `version` ASC";
                                            $result = $conn->query($query);
                                            if ($result->num_rows > 0) {
                                                foreach ($result as $data) {
                                                    $quoteID = $data['id'];
                                                    $quoteVersion = $data['version'];
                                                    $quoteSubmitter = $data['submitter'];
                                                    echo '<option value="view.php?viewversion=' . $quoteID . '"> v' . $quoteVersion . ' - ' . $quoteSubmitter . '</option>';
                                                }
                                            } else {
                                                echo '<option>No older versions available </option>';
                                            }
                                            ?>
                            </select>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <button type="button" name="modal" class="btn btn-info btn-block mb10" data-toggle="modal"
                                data-target="#myModal">Update
                                Quote</button>
                            <a href="index.php" type="button" name="back" class="btn btn-danger btn-block mb10">Back</a>


                            <!-- Modal -->

                            <div class="modal" id="myModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Submitter Data</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <div class="form-group service-form-group">
                                                <label class="control-label  " for="author">Name of Author</label>
                                                <input id="name" type="text" placeholder="Name of Author" name="author"
                                                    class="form-control" value="<?php echo $row["submitter"]; ?>"
                                                    required>
                                            </div>
                                            <div class="form-group service-form-group">
                                                <label class="control-label " for="reason">Commit Message</label>
                                                <input id="name" type="text" placeholder="Commit Message" name="reason"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-info" name="submit">Submit</button>
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
            }
        } else {
            echo "0 results";
        }
        $conn->close();
    } elseif (isset($_GET['viewversion'])) {

        $id = $_GET['viewversion'];

        $sql = "SELECT * FROM quote_versions WHERE id = $id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $getID = "SELECT * FROM `clients` WHERE `id` = '" . $row['clientid'] . "'";
                $resultC = $conn->query($getID);

                if ($resultC->num_rows > 0) {
                    while ($rowC = $resultC->fetch_assoc()) {
                        $name = $rowC["name"];
                        $number = $rowC["contact"];
                    }
                }
            ?>
    <div class="space-medium">
        <div class="container">
            <form method="post" action="index.php">
                <div class="service-form">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb10 ">
                            <h3><b>Edit</b> Quote for : <b>Computer Supplies</b></h3>
                        </div>
                        <hr>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                            <div class="form-group service-form-group">
                                <label class="control-label  " for="name">Name of Client</label>
                                <input id="name" type="text" placeholder="Name of Client" name="name"
                                    class="form-control" value="<?php echo $name; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12  ">
                            <div class="form-group service-form-group">
                                <label class="control-label  " for="contact">Contact no of client</label>
                                <input id="contact" type="number" placeholder="Contact no of client" name="contact"
                                    class="form-control" value="<?php echo $number; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group service-form-group">
                                <label class="control-label" for="description">Description</label>
                                <input id="description" type="text" placeholder="Description" name="description"
                                    class="form-control" value="<?php echo $row["description"]; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group service-form-group">
                                <label class="control-label" for="qty">Quantity</label>
                                <input id="qty" type="number" placeholder="Quantity" name="qty" class="form-control"
                                    value="<?php echo $row["quantity"]; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group service-form-group">
                                <label class="control-label" for="website">Unit Price</label>
                                <input id="price" type="number" placeholder="Unit Price" name="price"
                                    class="form-control" value="<?php echo $row["price"]; ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group service-form-group">
                                <p>Submitted by : <?php echo $row["submitter"]; ?></p>
                            </div>
                        </div>

                        <select class="form-control" onchange="javascript:location.href = this.value;">
                            <option>Select an older version </option>
                            <?php
                                        $quote_id = $row['id'];
                                        $query = "SELECT * FROM quote_versions WHERE quote_id = '$quote_id' ORDER BY `version` ASC";
                                        $result = $conn->query($query);
                                        if ($result->num_rows > 0) {
                                            foreach ($result as $data) {
                                                $quoteVersion = $data['version'];
                                                echo '<option value="view.php?viewversion=' . $quoteVersion . '">v' . $quoteVersion . '</option>';
                                            }
                                        } else {
                                            echo '<option>No older versions available </option>';
                                        }
                                        ?>
                        </select>


                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <button type="button" name="modal" class="btn btn-info btn-block mb10" data-toggle="modal"
                                data-target="#myModal">Update
                                Quote</button>
                            <a href="index.php" type="button" name="back" class="btn btn-danger btn-block mb10">Back</a>


                            <!-- Modal -->

                            <div class="modal" id="myModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Submitter Data</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <div class="form-group service-form-group">
                                                <label class="control-label" for="author"></label>
                                                <input id="name" type="text" placeholder="Name of Author" name="author"
                                                    class="form-control" value="<?php echo $row["submitter"]; ?>"
                                                    required>
                                            </div>
                                            <div class="form-group service-form-group">
                                                <label class="control-label" for="reason"></label>
                                                <input id="name" type="text" placeholder="Reason for submitting"
                                                    name="reason" class="form-control" required>
                                            </div>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-info" name="submit">Submit</button>
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php }
        }
    } ?>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script>
    $(document).on('click', '#showData', function(e) {
        $.ajax({
            type: "GET",
            url: "getdata.php",
            dataType: "html",
            success: function(data) {
                $("#table-container").html(data);

            }
        });
    });
    </script>
</body>

</html>