<?php

/* 
 * horsedb.php: PHP file containing all functions to access and manipulate the horsedb database.
 */

//Include the MySQL connection and Horse class.
include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Horse.php');
date_default_timezone_set('America/New_York');


/*
 * Function name: add_horse($horse)
 * Description: add a horse to the database, IF the horse doesn't exist already.
 * Parameters: 
 *      $horse, a Horse object with the information to add to the database.
 * Return Values:
 *      true, the horse was added to the database.
 *      false, the horse was NOT added to the database.
 */
function add_horse($horse) {
    
    //Legacy code check to ensure the parameter is a Horse object.
    if (!$horse instanceof Horse) {
        die("Error: add_horse type mismatch");
    }
    
    //Create a database connection and retrieve an existing horse.
    $con=connect();
    $query = "SELECT * FROM horseDB WHERE horseName='" . $horse->get_horseName() . "';";
    $result = mysqli_query($con,$query);

    //If the query is empty, meaning the horse doesn't exist in the database,
    if ($result == null || mysqli_num_rows($result) == 0) {
        $arctime = "0000-00-00";
        $notArchive = 0;
        $trainer = "none";
        //add the horse to the database.
        mysqli_query($con,'INSERT INTO horseDB VALUES("' .
                $horse->get_horseName() . '","' .
                $horse->get_color() . '","' .
                $horse->get_breed() . '","' .
                $horse->get_pastureNum() . '","' .
                $horse->get_colorRank() . '","' .
               
                $horse->get_horseArchived() . '","' .
					 $horse->get_horseArchiveDate() . '","' .
                //archive 
                //arctime
                //trainer
                $horse->get_assignedTrainer() . '");');							        
                //dont need to insert datetime, since the default is equal to 00-00-0000 00:00:00
                //dont need to insert trainer here, since the default is just NULL
        //Close the connection and return true.
        mysqli_close($con);
        return true;
    }

    //Otherwise, close the connection and return false.
    mysqli_close($con);
    return false;
}


/*
 * Function name: edit_horse($name, $horse)
 * Description: edit a horse in the database.
 *              By the time "edit_horse($name, $horse)" is called, it is certain that the database can be edited.
 * Parameters: 
 *      $name, the current name of the horse in the database.
 *      $horse, a Horse object that holds the updated information to edit the database with.
 * Return Values:
 *      true, the existing horse was edited.
 */
function edit_horse($name, $horse) {

    //Legacy code check to ensure the parameter is a Horse object.
    if (!$horse instanceof Horse) {
        die("Errors: edit_horse type mismatch");
    }

    //Create a database connection and update the existing horse.
    $con=connect();
    $query = "UPDATE horseDB SET horseName='" . $horse->get_horseName() . "', color='" . $horse->get_color() . "', breed='" . $horse->get_breed() . "', pastureNum='" . $horse->get_pastureNum() . "', colorRank='" . $horse->get_colorRank() . "' WHERE horseName='" . $name . "';";
    $result = mysqli_query($con,$query);
    
    //Close the connection and return true.
    mysqli_close($con);
    return true;
}

/*
 * Function name: retrieve_horse($horseName)
 * Description: retrieve a horse from the database based on its name.
 * Parameters: 
 *      $horseName, the current name of the behavior in the database.
 * Return Values:
 *      $theHorse, a Horse object created using the horse information from the database.
 *      false, a horse with the name "$horseName" doesn't exist.
 */
function retrieve_horse($horseName) {
    
    //Create a database connection and retrieve the horse from the database.    
    $con=connect();
    
    $query = "SELECT * FROM horseDB WHERE horseName='" . $horseName . "'";
    if ($arcy == 1){
    	
    }
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
 
function archive_horse($horse){

    $con=connect();

    if(!$horse instanceof Horse){
        die("Error: archive_horse type mismatch");
    }
    $arcTime = date("Y-m-d H:i:s");
    $horseName = $horse->get_horseName();

    //checks the DB to see if theres a horse with the same name and dateArchived, to avoid any duplications
    $query = "SELECT * FROM horsedb WHERE horseName='" . $horseName . "' AND dateArchived='" . $arcTime . "';";
    $result = mysqli_query($con,$query);


    //If the query is empty, meaning the horse doesn't exist in the database,
    if ($result == null || mysqli_num_rows($result) == 0) {
        $archived = 1;
        //updates the inputted horse, changing its archived to 1 and updating the dateArchived        
        mysqli_query($con, "UPDATE horsedb SET archived = 1, dateArchived='" .$arcTime . "' WHERE horseName='" . $horseName . "';");						        
        mysqli_close($con);
        return true;
    }
    //Else then the horse already exists (same name and time archived) so an error has occured 
    mysqli_close($con);
    return false;
    


}


// Change this so that remove saves the horse removed and adds the removed horse to a archivedDB

/*
 * Function name: remove_horse($horseName)
 * Description: remove a horse from the database.
 *              By the time "remove_horse($horseName)" is called, it is certain that the horse can be removed.
 * Parameters: 
 *      $horseName, the current name of the horse in the database.
 * Return Values:
 *      true, the existing horse was removed.
 */
function remove_horse($horseName) {

    //gets the horse from horseDB that has the inputted name
    $archived = retrieve_horse($horseName);
    //calls the archive_horse function to change the horses archived column to 1, and updates its dateArchived to the time the function was called. 
    archive_horse($archived);
    

    //Close the connection and return true.
    return true;
}


/*
 * Function name: getall_horseDB()
 * Description: retrieve all horses from the database into an array.
 * Parameters: None
 * Return Values:
 *      $theHorses, an array of Horse objects created using the horse information from the database.
 *      false, the horse table is empty.
 */
function getall_horseDB() {

    //Create a database connection and retrieve all horses from the database.
    $con=connect();
    $query = "SELECT * FROM horseDB ORDER BY horseName";
    $result = mysqli_query($con,$query);

    //If there are no horses in the database,
    if ($result == null || mysqli_num_rows($result) == 0) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create an array, convert each query row into a Horse object, and add the objects to the array.
    $result = mysqli_query($con,$query); //This line might be redundant.
    $theHorses = array();

    while ($result_row = mysqli_fetch_assoc($result)) {
        $theHorse = make_a_horse($result_row);
        $theHorses[] = $theHorse;
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $theHorses;
}


/*
 * Function name: getall_horse_names()
 * Description: retrieve all horse names from the database into an array.
 * Parameters: None
 * Return Values:
 *      $names, an array of horse names (strings) using the horse information from the database.
 *      false, the horse table is empty.
 */
function getall_horse_names() {

    //Create a database connection and retrieve all of the horse names.
    $con=connect();
    $query = "SELECT horseName FROM horseDB where archived = 0 ORDER BY horseName";
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
function getall_archived_horse_names() {

    //Create a database connection and retrieve all of the horse names.
    $con=connect();
    $query = "SELECT horseName FROM horseDB where archived = 1 ORDER BY horseName";
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
 

/*
 * Function name: get_numHorses()
 * Description: retrieve the number of horses in the database.
 *              It can certainly be optimized to have a "SELECT COUNT(*)" SQL query to get the count, instead of calling "getall_horse_names()".
 * Parameters: None
 * Return Values:
 *      0, if "getall_horse_names()" yields no rows.
 *      count($numNames), the number of horses in the database.
 */
function get_numHorses() {

    //If "getall_behavior_titles()" yields an empty query,
    if (getall_horse_names() == 0) {

        //return 0;
        return 0;
    }

    //Otherwise, save the returned array from "getall_horse_names()" and return the count of that array.
    $numNames = getall_horse_names();
    return count($numNames);
}


/*
 * Function name: make_a_horse($result_row)
 * Description: Convert an individual horse row into a Horse object and return it.
 * Parameters: 
 *      $result_row, the MySQL row containing behavior information.
 * Return Values:
 *      $theHorse, the Horse object created from the MySQL row.
 */
function make_a_horse($result_row) {
	 $arcTime = "0000-00-00";
	 $archiveNo = 0;
	 $trainer = "none";
    $theHorse = new Horse(
                $result_row['horseName'],
                $result_row['color'],
                $result_row['breed'],
                $result_row['pastureNum'],
                $result_row['colorRank'],
                $archiveNo,
                $arcTime,
                $trainer);
            
    return $theHorse;
}


//This function isn't used, but kept here in case the breed is needed for filtering purposes.
/*
function get_breed($horseName) {
    $con=connect();
    $query = "SELECT breed from horseDB WHERE horseName='" . $horseName . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return $result;
}
*/
