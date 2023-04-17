<?php
include('session.php');
include('database\dbinfo.php');

// Check if the user has the necessary permissions (permissions level 3)
if ($_SESSION['permissions'] < 3) {
    header("Location: index.php");
    exit;
}

// Get the horse ID and note ID from the POST data
$horseID = $_POST['horseID'];
$noteID = $_POST['noteID'];

// Connect to the database
$conn = connect();

// Check for errors connecting to the database
if (mysqli_connect_errno()) {
    echo 'Error: Could not connect to database. Please try again later.';
    exit();
}

// Prepare and execute a query to update the note's archive status
$query = "UPDATE notesDB SET archive = 0 WHERE noteID = '$noteID'";
$result = mysqli_query($conn, $query);

// Check for errors updating the note
if (!$result) {
    echo 'Error: Could not de-archive note. Please try again later.';
    exit();
}

// Redirect the user back to the horse profile page
header("Location: horseprofile.php?horseID=$horseID");
exit();
?>
