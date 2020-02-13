
<?php


$servername = "filepond-boilerplate-php_mariadbtest_1";
$username = "root";
$password = "mypass";
$dbname = "OSPREY_UPLOAD";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, username, filename FROM Uploads";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "<table>";
    echo "<tr><th>id</th>";
    echo "<th>Username</th><th>filename</th>";
    echo "</tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"]. "</td><td>>" . $row["username"]. "</td><td>" . $row["filename"]. "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();