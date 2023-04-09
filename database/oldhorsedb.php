<?php

/* 
 * horsedb.php: PHP file containing all functions to access and manipulate the horsedb database.
 */

//Include the MySQL connection and Horse class.
include_once('dbinfo.php');
include_once('./domain/Horse.php');

/*
 * Function name: add_horse($horse)
 * Description: add a horse to the database, IF the horse doesn't exist already.
 * Parameters: 
 *      $horse, a Horse object with the information to add to the database.
 * Return Values:
 *      true, the horse was added to the database.
 *      false, the horse was NOT added to the database.
 */
// function add_horse($horse) {
    
//     //Legacy code check to ensure the parameter is a Horse object.
//     if (!$horse instanceof Horse) {
//         die("Error: add_horse type mismatch");
//     }
    
//     //Create a database connection and retrieve an existing horse.
//     $con=connect();
//     $query = "SELECT * FROM horseDB WHERE horseName='" . $horse->get_horseName() . "';";
//     $result = mysqli_query($con,$query);

//     //If the query is empty, meaning the horse doesn't exist in the database,
//     if ($result == null || mysqli_num_rows($result) == 0) {
        
//         //add the horse to the database.
//         mysqli_query($con,'INSERT INTO horseDB VALUES("' .
//                 $horse->get_horseName() . '","' .
//                 $horse->get_color() . '","' .
//                 $horse->get_breed() . '","' .
//                 $horse->get_pastureNum() . '","' .
//                 $horse->get_colorRank() . '");');							        
        
//         //Close the connection and return true.
//         mysqli_close($con);
//         return true;
//     }

//     //Otherwise, close the connection and return false.
//     mysqli_close($con);
//     return false;
// }
function add_horse($horse) {
    
    //Legacy code check to ensure the parameter is a Horse object.
    if (!$horse instanceof Horse) {
        die("Error: add_horse type mismatch");
    }
    
    //Create a database connection and retrieve the current horse count.
    $con=connect();
    
    //Add the new horse to the database.
    $query = "INSERT INTO horseDB (horseName, color, breed, pastureNum, colorRank) VALUES ('" .
            $horse->get_horseName() . "','" .
            $horse->get_color() . "','" .
            $horse->get_breed() . "','" .
            $horse->get_pastureNum() . "','" .
            $horse->get_colorRank() . "')";

            
    mysqli_query($con, $query);
    
    //Close the connection and return true.
    mysqli_close($con);
    return true;
}

function edit_horse($horseID, $horse) {

    //Legacy code check to ensure the parameter is a Horse object.
    if (!$horse instanceof Horse) {
        die("Errors: edit_horse type mismatch");
    }

    //Create a database connection and update the existing horse.
    $con=connect();
    $query = "UPDATE horseDB SET horseName='" . $horse->get_horseName() . "', color='" . $horse->get_color() . "', breed='" . $horse->get_breed() . "', pastureNum='" . $horse->get_pastureNum() . "', colorRank='" . $horse->get_colorRank() . "' WHERE horseID='" . $horseID . "';";
    $result = mysqli_query($con,$query);
    
    //Close the connection and return true.
    mysqli_close($con);
    return true;
}

    
function retrieve_horse($horseID) {
    
    //Create a database connection and retrieve the horse from the database.    
    $con=connect();
    $query = "SELECT * FROM horseDB WHERE horseID='" . $horseID . "';";
    $result = mysqli_query($con,$query);

    //If the horse does NOT exist in the database,
    if (mysqli_num_rows($result) != 1) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create a Horse object from the query row and return the object.
    $result_row = mysqli_fetch_assoc($result);
    $theHorse = make_a_horse($result_row);
    return $theHorse;
}


function archive_horse($horseID) {
    // Create a database connection and update the archive and archiveDate fields for the horse
    $con = connect();
    
    // Update the archive and archiveDate fields for the specified horse
    $query = 'UPDATE horseDB SET archive=1, archiveDate=CURDATE() WHERE horseID = "' . $horseID . '"';

    $result = mysqli_query($con, $query);

    // Close the connection and return true if the update was successful, false otherwise
    mysqli_close($con);
    return $result !== false;
}

function activate_horse($horseID) {
    // Create a database connection and update the archive and archiveDate fields for the horse
    $con = connect();

    // Update the archive and archiveDate fields for the specified horse
    $query = 'UPDATE horseDB SET archive=0, archiveDate=null WHERE horseID = "' . $horseID . '"';
    $result = mysqli_query($con, $query);

    // Close the connection and return true if the update was successful, false otherwise
    mysqli_close($con);
    return $result !== false;
}


function getall_horseDB() {
    //Create a database connection and retrieve all non-archived horses from the database.
    $con = connect();
    $query = "SELECT * FROM horsedb WHERE archive IS NULL OR archive=0 ORDER BY horseName ASC"; 
    $result = mysqli_query($con, $query);

    //If there are no non-archived horses in the database,
    if ($result == null || mysqli_num_rows($result) == 0) {
        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create an array, convert each query row into a Horse object, and add the objects to the array.
    $theHorses = array();

    while ($result_row = mysqli_fetch_assoc($result)) {
        $theHorse = new Horse(
            $result_row['horseName'],
            $result_row['color'],
            $result_row['breed'],
            $result_row['pastureNum'],
            $result_row['colorRank'],
            $result_row['horseID']
        );
        $theHorses[] = $theHorse;
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $theHorses;
}


function getallInactive_horses() {
    //Create a database connection and retrieve all non-archived horses from the database.
    $con = connect();
    $query = "SELECT * FROM horsedb WHERE archive=1 ORDER BY horseName ASC"; 
    $result = mysqli_query($con, $query);

    //If there are no non-archived horses in the database,
    if ($result == null || mysqli_num_rows($result) == 0) {
        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create an array, convert each query row into a Horse object, and add the objects to the array.
    $theHorses = array();

    while ($result_row = mysqli_fetch_assoc($result)) {
        $theHorse = new Horse(
            $result_row['horseName'],
            $result_row['color'],
            $result_row['breed'],
            $result_row['pastureNum'],
            $result_row['colorRank'],
            $result_row['horseID']
        );
        $theHorses[] = $theHorse;
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $theHorses;
}

function getall_horse_names() {

    //Create a database connection and retrieve all of the horse names.
    $con=connect();
    $query = "SELECT * FROM horseDB WHERE archive IS NULL OR archive=0 ORDER BY horseName ASC"; 
    $result = mysqli_query($con,$query);

    //If the horse table is empty,
    if ($result == null || mysqli_num_rows($result) == 0) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }


    $result = mysqli_query($con,$query); //This line might be redundant.

    //Otherwise, create an array and add each horse name to the array.
    $names = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $names[] = $result_row['horseName'];
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $names;
}
 

function get_numHorses() {

    //Create a database connection and retrieve the count of non-archived horses from the database.
    $con=connect();
    $query = "SELECT COUNT(*) as num_horses FROM horseDB WHERE archive IS NULL OR archive=0";
    $result = mysqli_query($con,$query);

    //If the query returns no rows, close the connection and return 0.
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return 0;
    }

    //Otherwise, extract the count from the first (and only) row of the result set and return it.
    $row = mysqli_fetch_assoc($result);
    $count = $row['num_horses'];

    //Close the connection and return the count.
    mysqli_close($con);
    return $count;
}

function get_num_archiveHorses() {

    //Create a database connection and retrieve the count of non-archived horses from the database.
    $con=connect();
    $query = "SELECT COUNT(*) as num_horses FROM horseDB WHERE archive=true";
    $result = mysqli_query($con,$query);

    //If the query returns no rows, close the connection and return 0.
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return 0;
    }

    //Otherwise, extract the count from the first (and only) row of the result set and return it.
    $row = mysqli_fetch_assoc($result);
    $count = $row['num_horses'];

    //Close the connection and return the count.
    mysqli_close($con);
    return $count;
}


function make_a_horse($result_row) {
    $theHorse = new Horse(
        $result_row['horseID'],
        $result_row['horseName'],
        $result_row['color'],
        $result_row['breed'],
        $result_row['pastureNum'],
        $result_row['colorRank']);
    return $theHorse;
}


