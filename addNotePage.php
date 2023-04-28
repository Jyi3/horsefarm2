
<?PHP

include_once('database/notedb.php');
include_once('database/dbinfo.php');
include_once('domain/Note.php');
include_once('database/horsedb.php');
include_once('domain/horse.php');


$horseID = $_POST['horseID'] ?? null;
$noteID = $_POST['noteID'] ?? null;
$username = $_POST['username'] ?? null;

$theHorse = $theNote = null;
$theHorseNotes = [];
$numNotes = 0;

if(isset($_POST['horseID'])){$horseID=$_POST['horseID'];}
if(isset($_POST['noteID'])){$noteID=$_POST['noteID'];}
if(isset($_POST['username'])){$username=$_POST['username'];}

if($horseID!=null){
$theHorse = retrieve_horse_by_id($horseID);
}

if($theHorse!=null){
$theHorseNotes= retrieve_horse_notes($horseID);
}

if($noteID!=null){
    $theNote = retrieve_note_by_id($noteID);
}

if($horseID!=null){
    $numNotes = get_num_horse_notes($horseID);
}

$conn = connect();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$trainerListSql = "SELECT p.username, p.firstName, p.lastName, p.archive, IF(pt.horseID IS NULL, 0, 1) AS isConnected
        FROM persondb p
        LEFT JOIN persontohorsedb pt ON p.username = pt.username AND pt.horseID = '$horseID'
        WHERE p.archive != 1";
    $trainerListResult = mysqli_query($conn, $trainerListSql);
    while ($row = mysqli_fetch_assoc($trainerListResult)) {
        if ($row['isConnected'] == 1) {
            $username = $row['username'];
            break; // Stop looping once you find a connected trainer
        }
    }


    //auxillary note related funcitons here.

    function addNoteForm($theHorse,$username){

        echo("<p>Please enter information that will be associated with " . $theHorse->get_horseName() ."</p>");
        echo("</br>");
        //echo("<p>YEEEEEEEEEEEEEEHAWWWWWWWWW</p>");
        $horseID = $theHorse->get_horseID();
        $horseName = $theHorse->get_horseName();

        echo("
        <form action='/horse/horsefarm2/addNotePage.php' method='POST'>
        <label>Add a new note for $horseName:</label><br>
        <input type='hidden' name='horseID' value='$horseID'>
        <textarea id='note' name='note' rows='4' cols='50' value='test' required></textarea><br>
        <label for='username'>Username:</label>
        <input type='hidden' name='username' value='<?php echo $username; ?>' required readonly><br>
        <input type='submit' value='Create Note'>
        <input type='hidden' name='noteDate' value='" . date('Y-m-d') . "'>
        <input type='hidden' name='noteTimestamp' value='" . time() . "'>
        <input type='hidden' name='addNoteSubmit' value='1'>
        </form>"
        
        );
        
}

function handleNoteSubmission(){
        if(isset($_POST['addNoteSubmit'])){
            $submissionSet = array(
                'noteID' => get_next_note_id(),
                'horseID' => $_POST['horseID'],
                'noteDate' => $_POST['noteDate'],
                'noteTimestamp' => $_POST['noteTimestamp'],
                'note' => $_POST['note'],
                'username' => $_POST['username'],
                'archive' => 0,
                'archiveDate' => null
            );
            $theNote = construct_next_note($submissionSet);
            $status = add_note($theNote);
            echo "note addition status: " . (boolean)$status . "<br>";
    }
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
            @media (max-width: 480px) {
                #container {
                    max-width: 100%;
                    padding: 10px;
                }
                #content-inner {
                    max-width: 90%;
                }
                h1 {
                    font-size: 24px;
                }
                p {
                    font-size: 16px;
                    line-height: 1.4;
                }
            }

        </style> 
    </head>
    <body>
        <div id="container">
            <?PHP include('header.php'); ?>
            <div id="content">
                <div id="content-inner">
                <?PHP
                include_once('domain/Horse.php');
                include_once('database/dbinfo.php');
                include_once('database/horsedb.php');
                date_default_timezone_set('America/New_York');

                echo("<h1>Add a note</br></h1>");
                echo("<br><p>Please enter information that will be associated with " . $theHorse->get_horseName() ."</p>");
                echo("<p>After you have updated your note with additional information, please select 'Edit Note'.<br>Your page will refresh and then return to the horse profile.</p>");
                echo("</br>");
                
                handleNoteSubmission();
                addNoteForm($theHorse, $username);
                ?>

                <form method="GET" action="horseprofile.php">
                    <input type="hidden" name="horseID" value="<?php echo $horseID; ?>">
                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                    <input type="submit" name="return" value="Return to horse profile">
                </form>


                </div>
            </div>
            <?PHP include('footer.php'); ?>
        </div>

    </body>
</html>
