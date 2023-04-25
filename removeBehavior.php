<?php

include_once('database/behaviordb.php');

function remove_behavior($title){
    #Checks to make sure the title inputed is a string
    if (is_string($title) == FALSE ){
        return false;
    }

    #Check and make sure that the behavior exists
    $con = connect();
    $checkQuery = "SELECT * FROM behaviordb WHERE title='" . $title . "';";
    $check = mysqli_query($con, $checkQuery);
    if($check == null  || mysqli_num_rows($check) == 0){
        return false;
    }

    #Deletes/Removes the behavior whos titile matches the title inputted
    $removeQuery = "DELETE FROM behaviordb WHERE title='" . $title . "';";
    mysqli_query($con, $removeQuery);
    return true;
}

?>