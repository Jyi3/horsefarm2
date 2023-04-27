<?php
    include('session.php');
    include('database/dbinfo.php');
    include('domain/Horse.php');
    include('database/horsedb.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['horseID']) && isset($_POST['status'])) {
        $conn = connect();
        $horseID = $_POST['horseID'];
        $status = $_POST['status'];
        $archive_date = ($status == 1) ? date('Y-m-d') : NULL;

        $query = "UPDATE horsedb SET archive = $status, archiveDate = '$archive_date' WHERE horseID = $horseID";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die('Error executing query: ' . mysqli_error($conn));
        }

        mysqli_close($conn);
    }
?>
