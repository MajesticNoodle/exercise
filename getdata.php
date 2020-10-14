<?php
include("database.php");
$db = $conn;
// fetch query
function fetch_data()
{
    global $db;
    $query = "SELECT * FROM quotes ORDER BY id ASC";
    $exec = mysqli_query($db, $query);
    if (mysqli_num_rows($exec) > 0) {
        $row = mysqli_fetch_all($exec, MYSQLI_ASSOC);
        return $row;
    } else {
        return $row = [];
    }
}
$fetchData = fetch_data();
show_data($fetchData);
?>
<?php
function show_data($fetchData)
{
    echo '<table class="table">
    <thead class="thead-dark">
        <tr>
            <th>Version</th>
            <th>Name of Client</th>
            <th>Contact no of client</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Submitter</th>
            <th>Reason for submit</th>
            <th>Tools</th>
        </tr>';
    if (count($fetchData) > 0) {
        global $db;
        $sn = 1;
        foreach ($fetchData as $data) {
            $getID = "SELECT * FROM `clients` WHERE `id` = '" . $data['clientid'] . "'";
            $resultC = $db->query($getID);

            if ($resultC->num_rows > 0) {
                while ($row = $resultC->fetch_assoc()) {
                    $name = $row["name"];
                    $number = $row["contact"];
                }
            }
            echo "<tr>
          <td>" . $sn . "</td>
          <td>" . $name . "</td>
          <td>" . $number . "</td>
          <td>" . $data['description'] . "</td>
          <td>" . $data['quantity'] . "</td>
          <td>" . $data['price'] . "</td>
          <td>" . $data['submitter'] . "</td>
          <td>" . $data['reason'] . "</td>
          <td><a class='btn btn-info' href='index.php?view=" . $data['id'] . "'>View</a></td>
        </tr>";

            $sn++;
        }
    } else {

        echo "<tr>
        <td colspan='7'>No Data Found</td>
       </tr>";
    }
    echo "</table>";
}
?>