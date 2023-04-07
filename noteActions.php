<?PHP
include_once('database/notedb.php');
include_once('database/dbinfo.php');
include_once('domain/Note.php');
include_once('database/horsedb.php');


//global variables here.
$formAction = $_GET["formAction"]; 
$selectedHorse = $_POST["horse"];
$selectedNote = $_POST["note"];
?>




<?PHP

function selectHorse(){

    //we need to show the user all the horses in the database, and allow the user to select one.
$allHorses = 

    //after we do this, we need to resubmit the page.


}

function selectNote($selectedHorse){

    //once we get the Horse, we can present the user with all the notes relating to that horse, and allow the user to act on them.





}

function processForm($formAction, $selectedHorse,$selectedNote){


    if($formAction=='searchNote'){
        echo("time to look for some notes.");
    }

    if($formAction=='addNote'){
    echo("time to add some notes.");
    }
    
    if($formAction=='editNote'){
        echo("time to edit some notes.");
        include('getHorseForm.inc');

    }
    
    if($formAction=='removeNote'){
        echo("time to remove some notes.");

    }
}


?>

<style>
            th, tr, td 
            {
                border-left: 1px solid black;
                border-right: 1px solid black;
                border-top: 1px solid black;
                border-bottom: 1px solid black;
            }
        </style>
        <link rel="stylesheet" href="lib/jquery-ui.css" />
        <link rel="stylesheet" href="styles.css" type="text/css" />
        <script src="lib/jquery-1.9.1.js"></script>
        <script src="lib/jquery-ui.js"></script>      
</head>

<body>
        <div id="container">
        <?PHP include('header.php'); ?>


            <div id="content">
                <?PHP

                ?>
            </div>
        </div>
</html>