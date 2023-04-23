<?php
include_once("database/dbinfo.php");

// Check if the user has the necessary permissions
if (!isset($_SESSION['permissions']) || ($_SESSION['permissions'] != 3 && $_SESSION['permissions'] != 5)) {
    die("You do not have permission to access this page.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $horseID = $_POST["horseID"];
    $username = $_POST["username"];

    $conn = connect();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the trainer-horse connection exists
    $sql = "SELECT * FROM persontohorsedb WHERE horseID = '$horseID' AND username = '$username'";
    $result = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($result);

    if ($num_rows == 0) {
        // If the connection doesn't exist, insert it
        $sql = "INSERT INTO persontohorsedb (horseID, username) VALUES ('$horseID', '$username')";
    } else {
        // If the connection exists, remove it
        $sql = "DELETE FROM persontohorsedb WHERE horseID = '$horseID' AND username = '$username'";
    }

    $action_success = mysqli_query($conn, $sql);
    if (!$action_success) {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
