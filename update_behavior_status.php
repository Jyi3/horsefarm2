<?php
    include('session.php');
    include('database/dbinfo.php');
    include('domain/Behavior.php');
    include('database/behaviordb.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
        $conn = connect();
        $title = $_POST['title'];

        $query = 'DELETE FROM behaviordb WHERE title="' . $title . '"';
        $result = mysqli_query($con,$query);

        if (!$result) {
            die('Error executing query: ' . mysqli_error($conn));
        }

        mysqli_close($conn);
    }
?>
