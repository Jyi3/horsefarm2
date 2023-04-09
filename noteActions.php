<?PHP
include_once('database/notedb.php');
include_once('database/dbinfo.php');
include_once('domain/Note.php');
include_once('database/horsedb.php');


//global variables here.

if( isset($_POST["formAction"])){
$formAction = $_POST["formAction"]; 
}
else{$formAction=null;}

//HARDCODE THE FORMACTION FOR TESTING.
$formAction="addNote";
//THIS CAN NOT BE NULL


if(isset($_POST["selectedHorse"])){
    $selectedHorse = $_POST["selectedHorse"];
}
else{$selectedHorse=null;}

if(isset($_POST["selectedNote"])){
$selectedNote = $_POST["selectedNote"];
}
else{$selectedNote=null;}

?>




<?PHP


function selectNote($selectedHorse){

    //once we get the Horse, we can present the user with all the notes relating to that horse, and allow the user to act on them.

    echo("yippee!");



}

function processForm($formAction, $selectedHorse,$selectedNote){
    echo("DOES THIS HAPPEN?");
    echo($formAction);
    echo($selectedHorse);
    echo($selectedNote);

    if($selectedHorse==null && $selectedNote==null){
        echo("DOES THIS HAPPEN?");

        if($formAction=='searchNote'){
            echo("Here we want to search for a note, but we need to select a horse first.");
            selectHorse($formAction);
        }

        if($formAction=='addNote'){
            echo("Here we want to add a note, but we need to select a horse first.");
            selectHorse($formAction);
        }
        
        if($formAction=='editNote'){
            echo("Here we want to edit a note, but we need to select a horse first.");
            selectHorse($formAction);
        }
        
        if($formAction=='removeNote'){
            echo("Here we want to remove a note, but we need to select a horse first.");
            selectHorse($formAction);
        }
}
    if($formAction!=null && $selectedHorse!=null && $selectedNote==null){
        if($formAction=='searchNote'){
            echo("Here we want to search for a note, and we also have a selected horse. ('$selectedHorse')");
            selectNote($selectedHorse);
        }

        if($formAction=='addNote'){
            echo("Here we want to add a note, and we also have a selected horse. ('$selectedHorse')");
            selectNote($selectedHorse);
        }
        
        if($formAction=='editNote'){
            echo("Here we want to edit a note, and we also have a selected horse. ('$selectedHorse')");
            selectNote($selectedHorse);
        }
        
        if($formAction=='removeNote'){
            echo("Here we want to remove a note, and we also have a selected horse. ('$selectedHorse')");
            selectNote($selectedHorse);
        }
    }
}


function selectHorse($formAction){
$theHorse = null;

//present the user with a form to select a horse.
echo("<form method='POST' action='/horse/horsefarm2/noteActions.php?formAction='" . $formAction . ">");
echo("<fieldset>");
echo("<legend>Horse Name:</legend>");
echo("<select name='selectedHorse' tabindex='1'>");
//Create an array of all of the existing behavior titles for the user to select from.
$names = getall_horse_names();
foreach ($names as $n) {
    echo("<option value='$n' >$n</option>");
}
echo("
</select>
<script src='lib/>jquery-1.9.1.js'></script>
<script src='lib/>jquery-ui.js'></script>
");
echo("</fieldset>");

echo("<input type='submit' value='Select Horse'/>");
echo("</form>");
//once the user selects a horse, the form should(?) resubmit.

    if($theHorse!=null){
    return $theHorse;
}

return null;

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
                min-height: 150vh;
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
                <h1>Welcome to the</br>Central Virginia Horse Rescue</br>Database</h1>
                    <p>
                        This is the CVHR Horse Training Management System, designed to help manage horses, trainers, and training activities at the Central Virginia Horse Rescue organization. Use the navigation menu to explore the system and manage records for horses, trainers, and training sessions. If you are not a registered user, recruit, trainer, or head admin, please visit the primary Central Virginia Horse Rescue website at <a href="https://centralvahorserescue.org/" target="_blank">https://centralvahorserescue.org/</a>.
                    </p>
                    <?PHP
                    include_once('domain/Horse.php');
                    include_once('database/dbinfo.php');
                    include_once('database/horsedb.php');
                    date_default_timezone_set('America/New_York');
                    $formAction="addNote";
                    //selectHorse($formAction);
                    processForm($formAction,$selectedHorse,$selectedNote);
                    
                    ?>

                </div>
            </div>
            <?PHP include('footer.php'); ?>
        </div>
    </body>
</html>
