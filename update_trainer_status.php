<?php
    include('session.php');
    include('database/dbinfo.php');
    include('domain/Person.php');
    include('database/persondb.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['status'])) {
        $conn = connect();
        $username = $_POST['username'];
        $status = $_POST['status'];
        $archive_date = ($status == 1) ? date('Y-m-d') : NULL;

        $query = "UPDATE persondb SET archive = $status, archiveDate = '$archive_date' WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die('Error executing query: ' . mysqli_error($conn));
        }

        mysqli_close($conn);
    }
?>
