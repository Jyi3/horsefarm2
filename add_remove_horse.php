<?php
include_once("database/dbinfo.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $horseID = $_POST["horseID"];
    $username = $_POST["username"];

    $conn = connect();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the trainer-horse connection exists
    $sql = "SELECT * FROM persontohorsedb WHERE horseID = ? AND username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $horseID, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_rows = $result->num_rows;

    if ($num_rows == 0) {
        // If the connection doesn't exist, insert it
        $sql = "INSERT INTO persontohorsedb (horseID, username) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $horseID, $username);
        $action_success = $stmt->execute();
    } else {
        // If the connection exists, remove it
        $sql = "DELETE FROM persontohorsedb WHERE horseID = ? AND username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $horseID, $username);
        $action_success = $stmt->execute();
    }

    if (!$action_success) {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
