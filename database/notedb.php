<?PHP

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Note.php');

/*get_next_note_id() returns the next available noteID.
*
*
*/
function get_next_note_id() {
    // create a database connection
    $con = connect();

    // retrieve the maximum noteID value from the notesdb table
    $query = "SELECT MAX(noteID) as max_id FROM notesdb";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    // if there are no notes in the table, set the next ID to 1
    if ($row['max_id'] == null) {
        $next_id = 1;
    }
    // otherwise, set the next ID to 1 greater than the maximum ID value
    else {
        $next_id = $row['max_id'] + 1;
    }

    // close the database connection and return the next ID
    mysqli_close($con);
    return $next_id;
}


/* construct_note() will return a new note object.
* $inputRow is a mySQL row that contains all parameters required for a new Note.
* returns NULL if not all parameters are present.
*/
function construct_note($inputRow){
$ID = get_next_note_id();
$theNote = new Note(
    $ID,
    $inputRow['horseID'],
    $inputRow['noteDate'],
    $inputRow['noteTimestamp'],
    $inputRow['note'],
    $inputRow['username'],
    $inputRow['archive'],
    $inputRow['archiveDate']);

return $theNote;
}


/*construct_note_now allows us to seemlessly construct a note without having to collect the time in other pages.
* the parameters used here are the same that we would typically use in the construction of a note.
*
*/
function construct_note_now($horseID , $note , $username){

$noteTimestamp = time();
$noteDate = date('Y-m-d');
$ID = get_next_note_id();
$archive = 0;
$archiveDate=null;
$theNote = new Note($ID,$horseID,$noteDate,$noteTimestamp,$note,$username,$archive,$archiveDate);
return $theNote;

}

/* note_full() is a function that tell us if all parameters are present in a note.
* $note is the note that will be examined to be certain that all parameters are present.
* returns false if note contains null values, and true if it doesnt.
*/
function note_full($note){
if(!$note instanceof Note){
    die("Error, attempted to check for note in note_full() in notesdb.php . Passed non-note variable.");

}
$status = true;
if($note->horseID == null){$status=false;}
if($note->noteDate == null){$status=false;}
if($note->noteTimestamp == null){$status=false;}
if($note->note == null){$status=false;}
if($note->username == null){$status=false;}
return $status;
}

/* add_note() is a function that adds a note to the database, if all parameters are present in a note.
* $note is the note that we intend to add.
* returns true if note was added, and false if it was not.
*/
function add_note($note){
    //create a database connection.
    $con=connect();

    $query = "SELECT * FROM notesdb WHERE noteID='" . $note->get_noteID() . "';";
    $result = mysqli_query($con,$query);

    //if the query is null, this means that the horse we want to add a note under doesn't exist.
    //this means that there does exist such a horse.
    if($result != null){
    //go ahead and add the note to the database.
    mysqli_query($con,'INSERT INTO notesdb VALUES("' .
        $note->get_horseID() . '","' .
        $note->get_noteID() . '","' .
        $note->get_noteDate() . '","' .
        $note->get_noteTimestamp() . '","' .
        $note->get_note() . '","' .
        $note->get_username() . '","' .
        $note->get_archive() . '","' .
        $note->get_archiveDate() . '");');									        
        
        //Close the connection and return true.
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
return false;
}


/* edit_note($noteText, $Note) is a function that allows you to edit a given note.
*  $noteText is the text that will inside of the note.
*  $Note is the actual Note object that is being edited.
*   returns true if the note was edited.
*/
function edit_note($noteText, $Note){
    if (!$Note instanceof Note) {
        die("Errors: edit_note type mismatch");
    }

    //Create a database connection and update the existing note.
    $con=connect();
    $query = "UPDATE notesdb SET note='" . $noteText . " WHERE noteID= " . $Note->get_noteID() . "';";
    $result = mysqli_query($con,$query);
    
    //Close the connection and return true.
    mysqli_close($con);
    return true;
}
/*retrieve_horse_notes() gives us all the notes that are associated with a horse.
*returns an array containing all the notes that we encountered.
*horseID is an integer that matches an existing horse in the horsedb.
*/
function retrieve_horse_notes($horseID){

    //Create a database connection and retrieve all of the note ID numbers.
    $con=connect();
    $query = "SELECT * FROM notesdb WHERE horseID = '" . $horseID . "'ORDER BY noteDate DESC";
    $result = mysqli_query($con,$query);

    //If the note table is empty,
    if ($result == null || mysqli_num_rows($result) == 0) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }


    $result = mysqli_query($con,$query); //This line might be redundant.

    //Otherwise, create an array and add each note
    $notes = array();
    while ($result_row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $notes[] = $result_row;
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $notes;
}


?>