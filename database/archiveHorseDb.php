<?php

/*
    PHP file that controls that archiveHorseDB. Contains functions: archive_horse(), getall_arcHorseDB()...

    Author: Nicolas Guzzone
*/

include_once('dbinfo.php');
include_once('../domain/Horse.php');
include_once('../domain/ArchivedHorse.php');
date_default_timezone_set('America/New_York');




/*
 * Function name: archive_horse()
 * Description: Takes a horse that was removed from the horseDB, and adds it to the archivedHorseDB.
 *              Assigns a horse a time and converts it from type horse to type ArchiveHorse. Then adding it to the ArchiveHorseDB
 * Parameters: 
 *      $horse, horse that should be added to the horseDB (taken from the remove function in horsedb.php)
 * Return Values:
 *      true, the horse was archived (removed from horseDB and added to archiveHorseDB)
 *      false, the horse was NOT archived succesfully
 * Called by:
 *      remove_horse() in  horsedb.php
 */
function archive_horse($horse){

    $con=connect();

    if(!$horse instanceof Horse){
        die("Error: archive_horse type mismatch");
    }
    $arcTime = date("Y-m-d H:i:s");
    
    $query = "SELECT * FROM archivehorsedb WHERE horseName='" . $horse->get_horseName(). "' AND dateArchived='" . $arcTime . "';";
    $result = mysqli_query($con,$query);


    //If the query is empty, meaning the horse doesn't exist in the database,
    if ($result == null || mysqli_num_rows($result) == 0) {
        
        mysqli_query($con,'INSERT INTO archivehorsedb VALUES("' .
                $horse->get_horseName() . '","' .
                $horse->get_color() . '","' .
                $horse->get_breed() . '","' .
                $horse->get_pastureNum() . '","' .
                $horse->get_colorRank() . '","'.
                $arcTime . '");');							        
        mysqli_close($con);
        return true;
    }
    //Else then the horse already exists (same name and time archived) so an error has occured 
    mysqli_close($con);
    return false;
    


}

//Add getALL functions to print all info from horse. And to print by date possibly.

//Gets all horses from the ArchiveHorseDB, saves it to an array and returns said array.
//Horses are saved by the order of their name in this function.
//Work on this next
function getall_arcHorseDB(){

    $con=connect();
    $query = "SELECT * FROM archiveHorseDB ORDER BY horseName";
    $result = mysqli_query($con,$query);

    if($result == null || mysqli_num_rows($result) == 0){

        //close connection and return false
        mysqli_close($con);
        return false;
    }
    else{
        $allHorses = array();
        while($result_row = mysqli_fetch_assoc($result)){
            $arcHorse = horse_to_archive($result_row);
            $arcHorses[] = $arcHorse;
        }
    }

    //

}



//Gets all horses from the ArchiveHorseDB, saves it to an array and returns said array.
//Horses are saved by the order of the time they were archived
function getall_arcHorseDB_byTime(){

    $con=connect();
    $query = "SELECT * FROM archiveHorseDB ORDER BY arcTime";
    $result = mysqli_query($con,$query);

    if($result == null || mysqli_num_rows($result) == 0){

        //close connection and return false
        mysqli_close($con);
        return 0;
    }
    else{
        $allHorses = array();
        while($result_row = mysqli_fetch_assoc($result)){
            $arcHorse = horse_to_archive($result_row);
            $arcHorses[] = $arcHorse;
        }
    }

    //

}

function getall_archived_horse_names(){
    $con = connect();
    $query = "SELECT horseName from horseDB ORDER BY horseName";
    $result = my_sqli_query($con,$query);

    //If archive is empty.
    if($result == null || mysqli_num_rows($result) == 0){
        mysqli_close($con);
        return false;
    }

    $names = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $names[] = $result_row['horseName'];
    }

// close ehre
    return $names;
}

function num_archived(){
    if(getall_archived_horse_names() == 0){
        return 0;
    }

    $numNames = count(getall_archived_horse_names());
}


function getall_archived_horse_times(){
    $con = connect();
    $query = "SELECT dateArchived from archivehorsedb ORDER BY dateArchived";
    $result = my_sqli_query($con,$query);

    //If archive is empty.
    if($result == null || mysqli_num_rows($result) == 0){
        
        return false;
    }

    $names = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $names[] = $result_row['arcTime'];
    }

   
    return $names;
}



/*
 * Function name: make_a_horse($result_row)
 * Description: Convert an individual horse row into a Horse object and return it.
 * Parameters: 
 *      $result_row, the MySQL row containing behavior information.
 * Return Values:
 *      $theHorse, the Horse object created from the MySQL row.
 */
function horse_to_archive($result_row) {
    $archivedHorse = new ArchivedHorse(
                $result_row['horseName'],
                $result_row['color'],
                $result_row['breed'],
                $result_row['pastureNum'],
                $result_row['colorRank']);
                $result_row['arcTime'];
    return $archivedHorse;
}






?>