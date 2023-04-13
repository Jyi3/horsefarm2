
<?PHP

include_once('database/notedb.php');
include_once('database/dbinfo.php');
include_once('domain/Note.php');
include_once('database/horsedb.php');
include_once('domain/horse.php');



$horseID=null;
$noteID=null;
$noteText;
$theHorse;
$theNote;
$theHorseNotes;
$numNotes;
$username;

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
$TLRrow = mysqli_fetch_assoc($trainerListResult);

$username=$TLRrow['username'];

    //auxillary note related funcitons here.

    function addNoteForm($theHorse,$username){
        echo("<p>Please enter information that will be associated with " . $theHorse->get_horseName() ."</p>");
        echo("</br>");
        //echo("<p>YEEEEEEEEEEEEEEHAWWWWWWWWW</p>");
        $horseID = $theHorse->get_horseID();

        echo("
    <form action='/horse/horsefarm2/noteAddForm.php' method='POST'>
    <label for='horseID'>Horse ID:</label>
    <input type='text' id='horseID' name='horseID' value='" . $horseID . "' required readonly><br>
    <label for='note'>Note:</label><br>
    <textarea id='note' name='note' rows='4' cols='50' value='test' required></textarea><br>
    <label for='username'>Username:</label>
    <input type='text' id='username' name='username' value='". $username ."' required readonly><br>
    <input type='submit' value='Create Note'>
    <input type='hidden' name='noteDate' value='" . date('Y-m-d') . "'>
    <input type='hidden' name='noteTimestamp' value='" . time() . "'>
    <input type='hidden' name='addNoteSubmit' value='1'>
    </form>
    </body>"
    );
}

    function handleNoteSubmission(){
        if(isset($_POST['addNoteSubmit'])){
            $submissionSet = array();
            //yes this is weird, because this isn't what I think of when I think of an array, but this makes the right variable type for PHP.
            $submissionSet['noteID'] = get_next_note_id(); 
            $submissionSet['horseID'] = $_POST['horseID'];
            $submissionSet['noteDate'] = $_POST['noteDate'];
            $submissionSet['noteTimestamp'] = $_POST['noteTimestamp'];
            $submissionSet['note'] = $_POST['note'];
            $submissionSet['username'] = $_POST['username'];
            $submissionSet['archive'] = 0;
            $submissionSet['archiveDate'] = NULL;
            $theNote = construct_note($submissionSet);
            $status = add_note($theNote);
            echo("\n\n");
            echo("LETS LOOK FOR A SPACE");
            //print_r($theNote);
            //echo("\n\n");

            echo("note addition status: ". (boolean)$status."<br>");
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
            <?PHP include('header.php'); ?>
            <div id="content">
                <div id="content-inner">
                    <?PHP
                    include_once('domain/Horse.php');
                    include_once('database/dbinfo.php');
                    include_once('database/horsedb.php');
                    date_default_timezone_set('America/New_York');
                    ?>
                    <h1>Welcome to the</br>Central Virginia Horse Rescue</br>Database</h1>
                    <p>
                        This is the CVHR Horse Training Management System, designed to help manage horses, trainers, and training activities at the Central Virginia Horse Rescue organization. Use the navigation menu to explore the system and manage records for horses, trainers, and training sessions. If you are not a registered user, recruit, trainer, or head admin, please visit the primary Central Virginia Horse Rescue website at <a href="https://centralvahorserescue.org/" target="_blank">https://centralvahorserescue.org/</a>.
                    </p>

                <?php
                print_r($_POST);
                ?>

                <br><br>
                <?php
                handleNoteSubmission();
                addNoteForm($theHorse,$username);
                ?>

                <form method="GET" action = "horseprofile.php">
                <input type="submit" name="horseID" value = <?php echo $horseID; ?>>Return to horse profile</input>
                        </form>

                </div>
            </div>
            <?PHP include('footer.php'); ?>
        </div>
    </body>
</html>
