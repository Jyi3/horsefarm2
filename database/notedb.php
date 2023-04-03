<?php

/* 
 * notedb.php: PHP file containing all functions to access and manipulate the notedb database.
 */

//Include the MySQL connection and Note class.
include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Note.php');

/*
 * Function name: add_note($note)
 * Description: add a note to the database.
 * Parameters: 
 *      $note, a note object with the information to add to the database.
 * Return Values:
 *      true, the note was added to the database.
 *      false, the note was NOT added to the database.
 */
function add_note($note) {
    
    //Legacy code check to ensure the parameter is a note object.
    if (!$note instanceof Note) {
        die("Error: add_note type mismatch");
    }
    
    //Create a database connection.
    $con=connect();
    					        
    //Create a database connection and retrieve an existing note.
    $con=connect();
    $query = "SELECT * FROM noteDB WHERE noteID='" . $note->get_noteID() . "';";
    $result = mysqli_query($con,$query);

    //If the query is empty, meaning the note doesn't exist in the database,
    if ($result == null || mysqli_num_rows($result) == 0) {
        
        //add the note to the database.
        mysqli_query($con,'INSERT INTO noteDB VALUES("' .
        $note->get_horseName() . '","' .
        $note->get_date() . '","' .
        $note->get_timestamp() . '","' .
        $note->get_noteText() . '","' .
        $note->get_trainerFirstName() . '","' .
        $note->get_trainerLastName() . '");');									        
        
        //Close the connection and return true.
        mysqli_close($con);
        return true;
    }

    //Otherwise, close the connection and return false.
    mysqli_close($con);
    return false;
}


/*
 * Function name: edit_note($name, $note)
 * Description: edit a note in the database.
 *              By the time "edit_note($name, $note)" is called, it is certain that the database can be edited.
 * Parameters: 
 *      $name, the current name of the note in the database.
 *      $note, a note object that holds the updated information to edit the database with.
 * Return Values:
 *      true, the existing note was edited.
 */
function edit_note($noteText, $note) {

    //Legacy code check to ensure the parameter is a note object.
    if (!$note instanceof note) {
        die("Errors: edit_note type mismatch");
    }

    //Create a database connection and update the existing note.
    $con=connect();
    $query = "UPDATE noteDB SET noteText='" . $note->get_noteText() . "';";
    $result = mysqli_query($con,$query);
    
    //Close the connection and return true.
    mysqli_close($con);
    return true;
}

/*
 * Function name: retrieve_note($noteID)
 * Description: retrieve a note from the database based on its name.
 * Parameters: 
 *      $noteID, the current id of the note in the database.
 * Return Values:
 *      $thenote, a note object created using the note information from the database.
 *      false, a note with the name "$noteID" doesn't exist.
 */
function retrieve_note($noteID) {
    
    //Create a database connection and retrieve the note from the database.    
    $con=connect();
    $query = "SELECT * FROM noteDB WHERE noteID='" . $noteID . "';";
    $result = mysqli_query($con,$query);

    //If the note does NOT exist in the database,
    if (mysqli_num_rows($result) != 1) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create a note object from the query row and return the object.
    $result_row = mysqli_fetch_assoc($result);
    $thenote = make_a_note($result_row);
    return $thenote;
}
    
// Change this so that remove saves the note removed and adds the removed note to a archivedDB

/*
 * Function name: remove_note($noteName)
 * Description: remove a note from the database.
 *              By the time "remove_note($noteName)" is called, it is certain that the note can be removed.
 * Parameters: 
 *      $noteName, the current name of the note in the database.
 * Return Values:
 *      true, the existing note was removed.
 */
function remove_note($noteID) {

    //Create a database connection and remove the note from the database.
    $con=connect();

    
    //Saves the note that is being deleted
    $archived = retrieve_note($noteID);
    //Adds the deleted note to the archived DB using the archive_note function
    
    // TODO: ARCHIVE NOTES!!!!!!!!!!!!!!!!!!!!!!
    //archive_note($archived);
    

    $query = 'DELETE FROM noteDB WHERE noteID = "' . $noteID . '"';
    $result = mysqli_query($con,$query);

    //Close the connection and return true.
    mysqli_close($con);
    return true;
}


/*
 * Function name: getall_noteDB()
 * Description: retrieve all notes from the database into an array.
 * Parameters: None
 * Return Values:
 *      $thenotes, an array of note objects created using the note information from the database.
 *      false, the note table is empty.
 */
function getall_noteDB() {

    //Create a database connection and retrieve all notes from the database.
    $con=connect();
    $query = "SELECT * FROM noteDB ORDER BY noteID";
    $result = mysqli_query($con,$query);

    //If there are no notes in the database,
    if ($result == null || mysqli_num_rows($result) == 0) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create an array, convert each query row into a note object, and add the objects to the array.
    $result = mysqli_query($con,$query); //This line might be redundant.
    $thenotes = array();

    while ($result_row = mysqli_fetch_assoc($result)) {
        $thenote = make_a_note($result_row);
        $thenotes[] = $thenote;
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $thenotes;
}


/*
 * Function name: getall_note_names()
 * Description: retrieve all notes from a particular horse
 * Parameters: $horseName, the name of the horse to querey the database for.
 * Return Values:
 *      $names, an array of note names (strings) using the note information from the database.
 *      false, the note table is empty.
 */
function getall_notes_from_horse($horseName) {

    //Create a database connection and retrieve all of the note ID numbers.
    $con=connect();
    $query = 'SELECT noteID FROM noteDB WHERE horseName = "' . $horseName . '"';
    $result = mysqli_query($con,$query);

    //If the note table is empty,
    if ($result == null || mysqli_num_rows($result) == 0) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }


    $result = mysqli_query($con,$query); //This line might be redundant.

    //Otherwise, create an array and add each note id to the array.
    $names = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $noteIDs[] = $result_row['noteID'];
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $noteIDs;
}

/*
 * Function name: get_numNotes()
 * Description: retrieve the number of Notes in the database.
 * Parameters: None
 * Return Values: the number of Notes (numNotes)
 */
function get_numNotes() {

    $con=connect();
    $query = 'SELECT COUNT(*) FROM noteDB ';
    $result = mysqli_query($con,$query);
    $numNotes = $result->fetch_row()[0];
    return count($numNotes);
}

 

/*
 * Function name: make_a_note($result_row)
 * Description: Convert an individual note row into a note object and return it.
 * Parameters: 
 *      $result_row, the MySQL row containing behavior information.
 * Return Values:
 *      $thenote, the note object created from the MySQL row.
 */
function make_a_note($result_row) {
    //get a new ID.
    $con = connect();
    $query = 'SELECT MAX(noteID) FROM noteDB ';
    $result = mysqli_query($con,$query);
    $newID = -2147483648;
    //this value above is the BOTTOM of the integer value space.
    if($result==null){$newID=-2147483648;}
    else{
    if($result==2147483647){
        //this is a HIGHLY UNLIKELY CASE, BUT THIS MEANS THAT WE RAN OUT OF SPACE
        //IN A 32 BIT INTEGER. WE CANNOT ADD ANY MORE NOTES.
        echo("CRITICAL ERROR. MAXIMUM ID VALUE ACHIEVED. NO MORE SPACE IN NOTEID.");

    }
    else{$newID = $result + 1;}    
        
    }
    $thenote = new Note(
                $result_row['horseName'],
                $result_row['date'],
                $result_row['timestamp'],
                $result_row['noteText'],
                $result_row['trainerFirstName'],
                $result_row['trainerLastName'],
                $newID
            );

    return $thenote;
}

