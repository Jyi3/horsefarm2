<?php
session_start();
if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] < 3) {
    echo "<script>
            alert('You do not have permission to access this page.');
            window.location.href = 'index.php';
          </script>";
}

include_once 'database/notedb.php';
include_once 'database/dbinfo.php';
include_once 'domain/Note.php';
include_once 'database/horsedb.php';
include_once 'domain/horse.php';

$horseID = $_POST['horseID'] ?? null;
$noteID = $_POST['noteID'] ?? null;
$username = $_POST['username'] ?? null;
$editText = $_POST['editText'] ?? null;

$theHorse = null;
$theNote = null;
$theHorseNotes = null;
$numNotes = null;
$noteText = null;

if ($horseID) {
    $theHorse = retrieve_horse_by_id($horseID);
    $theHorseNotes = retrieve_horse_notes($horseID);
    $numNotes = get_num_horse_notes($horseID);
}

if ($noteID) {
    $theNote = retrieve_note_by_id($noteID);
    $noteText = $theNote->get_note();
}

if (isset($_POST['addNoteSubmit'])) {
    // Connect to the database
    $conn = connect();

    // Prepare the SQL query
    $query = "UPDATE notesdb SET note=? WHERE noteID=?";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Check if the statement preparation was successful
    if (!$stmt) {
        die('Error preparing statement: ' . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("si", $editText, $noteID);

    // Execute the statement
    if (!$stmt->execute()) {
        die('Error executing statement: ' . $stmt->error);
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    mysqli_close($conn);

    // Redirect to the horseprofile.php page with the given horseID
    header("Location: horseprofile.php?horseID=$horseID");
}

$conn = connect();

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

$trainerListSql = "SELECT p.username, p.firstName, p.lastName, p.archive, IF(pt.horseID IS NULL, 0, 1) AS isConnected
                    FROM persondb p
                    LEFT JOIN persontohorsedb pt ON p.username = pt.username AND pt.horseID = '$horseID'
                    WHERE p.archive != 1";
$trainerListResult = mysqli_query($conn, $trainerListSql);
$TLRrow = mysqli_fetch_assoc($trainerListResult);
$username = $TLRrow['username'];

function editNoteForm($theHorse, $username, $noteText, $noteID) {
    //auxiliary note related functions here.
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            CVHR Horse Training Management System
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css" />
        
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f3f3f3;
                color: #333;
                margin: 0;
            }
            #container {
                max-width: 1200px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                position: relative;
                min-height: 100vh;
            }
            #appLink:visited {
                color: gray; 
            }
            #content {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            #content-inner {
                text-align: center;
                max-width: 800px;
                width: 100%;
            }
            h1 {
                color: #4b6c9e;
                font-size: 36px;
                margin-bottom: 20px;
                text-align: center;
                margin: 0 auto;
            }
            p {
                font-size: 18px;
                line-height: 1.6;
                margin: 0 auto;
            }

                
            .editNote-form input[type="submit"] {
                background-color: #4b6c9e;
                color: #fff;
                border: none;
                padding: 8px 20px;
                font-size: 16px;
                cursor: pointer;
                margin-left: 20px;
            }

            .editNote-form input[type="submit"]:hover {
                background-color: #fff;
                color: #4b6c9e;
                border: 2px solid #4b6c9e;
            }
            
            .editNote-form-button {
                background-color: #4b6c9e;
                color: #fff;
                border: none;
                padding: 8px 20px;
                font-size: 16px;
                cursor: pointer;
                margin-left: 20px;
            }

            .editNote-form-button:hover {
                background-color: #fff;
                color: #4b6c9e;
                border: 2px solid #4b6c9e;
            }
            
            .editNote-form-button {
                background-color: #4b6c9e;
                color: #fff;
                border: none;
                padding: 8px 20px;
                font-size: 16px;
                cursor: pointer;
                margin-left: 20px;
            }

            .editNote-form-button:hover {
                background-color: #fff;
                color: #4b6c9e;
                border: 2px solid #4b6c9e;
            }


            @media (max-width: 768px) {
                h1 {
                    font-size: 28px;
                }

                p {
                    font-size: 16px;
                    max-width: 90%;
                }

                #container {
                    padding: 10px;
                }
            }
        </style> 
    </head>
    <body>
        <div id="container">
            <?php include('header.php'); ?>
            <div id="content">
                <div id="content-inner">
                    <?php
                    include_once('domain/Horse.php');
                    include_once('database/dbinfo.php');
                    include_once('database/horsedb.php');
                    date_default_timezone_set('America/New_York');

                    $horseID = $theHorse->get_horseID();
                    $horseName = $theHorse->get_horseName();
                    ?>
                <br>
                    <p>
                        Please enter information that will be associated with <?php echo $theHorse->get_horseName(); ?>
                    </p>
                    <p>
                        After you have updated your note with additional information, please select 'Edit Note'.<br>
                        Your page will refresh and then return to the horse profile.
                    </p>
                    <br>
                    <form class="editNote-form" action='editNotePage.php' method='POST' onsubmit="prependEdited()">
                        <label>Edit <?php echo $horseName ?>'s note:</label><br>
                        <input type='hidden' name='horseID' value='<?php echo $horseID; ?>'>
                        <textarea id='note' name='editText' rows='4' cols='50' required><?php echo $noteText; ?></textarea><br>
                        <input type='hidden' name='username' value='<?php echo $username; ?>' required readonly><br>
                        <button type='submit' name='submitBtn' class='editNote-form-button'>Edit Note</button>
                        <input type='hidden' name='noteDate' value='<?php echo date('Y-m-d'); ?>'>
                        <input type='hidden' name='noteTimestamp' value='<?php echo time(); ?>'>
                        <input type='hidden' name='noteID' value='<?php echo $noteID; ?>'>
                        <input type='hidden' name='addNoteSubmit' value='1'>
                    </form>
                    <br>
                    <form class="editNote-form" method="GET" action="horseprofile.php">
                        <input type="hidden" name="horseID" value="<?php echo $horseID; ?>">
                        <input type="submit" name="return" value="Return to horse profile">
                    </form>
                    <br>
                </div>
            </div>
            <?php include('footer.php'); ?>
        </div>
        <script>
            function prependEdited() {
                const noteTextarea = document.getElementById('note');
                if (!noteTextarea.value.startsWith("(edited)")) {
                    noteTextarea.value = "(edited) " + noteTextarea.value;
                }
            }
        </script>

    </body>


</html>
