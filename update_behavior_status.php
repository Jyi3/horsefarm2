<?php
    include_once('session.php');
    include_once('database/dbinfo.php');
    include_once('domain/Behavior.php');
    include_once('database/behaviordb.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
        $conn = connect();
        
        $title = $_POST['title'];
        echo "Title: " . $title;
        var_dump($title);
        $query = 'DELETE FROM horsetobehaviordb WHERE title="' . $title . '"';
        $result = mysqli_query($conn,$query);

        if (!$result) {
            die('Error executing query: ' . mysqli_error($conn));
        }
        
        $query = 'DELETE FROM behaviordb WHERE title="' . $title . '"';
        
        #remove_behavior($title);
        $result = mysqli_query($conn,$query);

        if (!$result) {
            die('Error executing query: ' . mysqli_error($conn));
        }

        mysqli_close($conn);
    }
?>
