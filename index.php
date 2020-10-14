<?php
include('includes/database.php');

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($_POST['qty'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $submitter = filter_var($_POST['author'], FILTER_SANITIZE_STRING);
    $reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);

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

    $sqlData = "INSERT INTO quotes (description, quantity, price, submitter, reason, clientid, date)
VALUES ('$description', '$quantity', '$price', '$submitter', '$reason', '$relationID', now())";

    if ($conn->query($sqlData) === TRUE) {
        echo "Quote submitted successfully";
    } else {
        echo "Error: " . $sqlData . "<br>" . $conn->error;
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
    <div class="space-medium">
        <div class="container">
            <button type="button" name="modal" class="btn btn-info mb-3" data-toggle="modal"
                data-target="#myModal">Create
                Quote</button>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Name of Client</th>
                        <th>Contact no of client</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Author</th>
                        <th>Reason for submit</th>
                        <th>Tools</th>
                    </tr>

                    <tr>
                        <?php
                        $query = "SELECT * FROM quotes ORDER BY id ASC";
                        $exec = $conn->query($query);
                        $sn = 1;
                        if ($exec->num_rows > 0) {
                            foreach ($exec as $data) {
                                $clientID = $data['clientid'];
                                $getID = "SELECT * FROM `clients` WHERE `id` = '$clientID'";
                                $resultC = $conn->query($getID);

                                if ($resultC->num_rows > 0) {
                                    while ($row = $resultC->fetch_assoc()) {
                                        $name = $row["name"];
                                        $number = $row["contact"];
                                    }
                                }
                        ?>
                        <td>
                            <?php echo $sn; ?>
                        </td>
                        <td>
                            <?php echo $name; ?>
                        </td>
                        <td>
                            <?php echo $number; ?>
                        </td>
                        <td>
                            <?php echo $data['description']; ?>
                        </td>
                        <td>
                            <?php echo $data['quantity']; ?>
                        </td>
                        <td>
                            <?php echo $data['price']; ?>
                        </td>
                        <td>
                            <?php echo $data['submitter']; ?>
                        </td>
                        <td>
                            <?php echo $data['reason']; ?>
                        </td>
                        <td><a class='btn btn-info' href='view.php?view=<?php echo $data["id"]; ?>'>View</a></td>
                    <tr>
                        <?php
                                $sn++;
                            }
                        } else {
                            echo "<tr>
                                <td colspan='7'>No Data Found</td>
                            </tr>";
                        }
                ?>
            </table>
        </div>
    </div>


    <div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Create new Quote</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body p-0">
                    <form method="post" action="index.php">
                        <div class="service-form">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb10 ">
                                    <h3><b>Create</b> Quote</h3>
                                </div>
                                <hr>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                                    <div class="form-group service-form-group">
                                        <label class="control-label  " for="name"></label>
                                        <input id="name" type="text" placeholder="Name of Client" name="name"
                                            class="form-control"
                                            value="<?php echo isset($_GET['view']) ? $name : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12  ">
                                    <div class="form-group service-form-group">
                                        <label class="control-label  " for="contact"></label>
                                        <input id="contact" type="number" placeholder="Contact no of client"
                                            name="contact" class="form-control"
                                            value="<?php echo isset($_GET['view']) ? $number : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                    <div class="form-group service-form-group">
                                        <label class="control-label  " for="description"></label>
                                        <input id="description" type="text" placeholder="Description" name="description"
                                            class="form-control"
                                            value="<?php echo isset($_GET['view']) ? $row["description"] : ''; ?>"
                                            required>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                    <div class="form-group service-form-group">
                                        <label class="control-label  " for="qty"></label>
                                        <input id="qty" type="number" placeholder="Quantity" name="qty"
                                            class="form-control"
                                            value="<?php echo isset($_GET['view']) ? $row["quantity"] : ''; ?>"
                                            required>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                    <div class="form-group service-form-group">
                                        <label class="control-label  " for="website"></label>
                                        <input id="price" type="number" placeholder="Unit Price" name="price"
                                            class="form-control"
                                            value="<?php echo isset($_GET['view']) ? $row["price"] : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                    <button type="button" name="modal" class="btn btn-info btn-block mb10"
                                        data-toggle="modal" data-target="#myModalSmall">Submit
                                        Quote</button>
                                    <a href="index.php" type="button" name="back"
                                        class="btn btn-danger btn-block mb10">Back</a>


                                    <!-- Modal -->

                                    <div class="modal" id="myModalSmall">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Submitter Data</h4>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    <div class="form-group service-form-group">
                                                        <label class="control-label  " for="author">Author</label>
                                                        <input id="name" type="text" placeholder="Name of Author"
                                                            name="author" class="form-control" required>
                                                    </div>
                                                    <div class="form-group service-form-group">
                                                        <label class="control-label  " for="reason">Reason for
                                                            submitting</label>
                                                        <input id="name" type="text" placeholder="Reason for submitting"
                                                            name="reason" class="form-control" required>
                                                    </div>
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-info"
                                                        name="submit">Submit</button>
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
        </div>
    </div>