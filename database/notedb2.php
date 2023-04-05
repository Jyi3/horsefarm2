<?PHP

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Note.php');

/* construct_note() will return a new note object.
* $inputRow is a mySQL row that contains all parameters required for a new Note.
* returns NULL if not all parameters are present.
*/
function construct_note($inputRow){

$theNote = new Note(
    $inputRow['horseID'],
    $inputRow['noteDate'],
    $inputRow['noteTimestamp'],
    $inputRow['note'],
    $inputRow['username']);

return $theNote;
}

/* note_full() is a function that tell us if all parameters are present in a note.
* $note is the note that will be examined to be certain that all parameters are present.
* returns false if note contains null values, and true if it doesnt.
*/
function note_full($note){
if(!$note instanceof Note){
    die("Error, attempted to check for note in note_full() in notedb.php . Passed non-note variable.")

}

$status = true;

if($note->horseID == null){$status=false;}
if($note->noteDate == null){$status=false;}
if($note->noteTimestamp == null){$status=false;}
if($note->note == null){$status=false;}
if($note->username == null){$status=false;}

return $status

}

/* add_note() is a function that adds a note to the database, if all parameters are present in a note.
* $note is the note that we intend to add.
* returns true if note was added, and false if it was not.
*/

function add_note($note){


    //create a database connection.
    $con=connect();

    //attempt to see if the horse exists..
    $query = "SELECT * FROM horsedb WHERE horseID='" . $note->get_noteID() . "';";
    $result = mysqli_query($con,$query);

    //if the query is null, this means that the horse we want to add a note under doesn't exist.
    //this means that there does exist such a horse.
    if($result != null){
    //go ahead and add the note to the database.
    mysqli_query($con,'INSERT INTO noteDB VALUES("' .
        $note->get_horseID() . '","' .
        $note->get_noteDate() . '","' .
        $note->get_noteTimestamp() . '","' .
        $note->get_note() . '","' .
        $note->get_username() . '");');									        
        
        //Close the connection and return true.
        mysqli_close($con);
        return true;
    }

    mysqli_close($con);
return false;

}


/* edit_note($note, $Note) is a function that allows you to edit a given note.
*  $note is the text that will be replaced.
*  $Note is the actual Note object that is being edited.
*   returns true if the note was edited.
*/
function edit_note($note, $Note){
    if (!$Note instanceof Note) {
        die("Errors: edit_note type mismatch");
    }

    /Create a database connection and update the existing note.
    $con=connect();
    $query = "UPDATE noteDB SET note='" . $note->get_note() . "';";
    $result = mysqli_query($con,$query);
    
    //Close the connection and return true.
    mysqli_close($con);
    return true;

}
/*retrieve_horse_notes() gives us all the notes that are associated with a horse.
*returns an array containing all the notes that we encountered.
*/
function retrieve_horse_notes($horseID){

    //Create a database connection and retrieve all of the note ID numbers.
    $con=connect();
    $query = 'SELECT * FROM noteDB WHERE horseID = "' . $horseID . '"';
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
    while ($result_row = mysqli_fetch_assoc($result)) {
        $notes[] = $result_row[];
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $notes;

}


?>