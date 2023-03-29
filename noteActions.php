/*
*noteActions.php
*
*@author Ethan Thompson @ ethomps5@mail.umw.edu
*/

<?php
include_once('database/nodedb.php');
include_once('database/dbinfo.php');
include_once('database/Notes.php');

$formAction = $_GET["formAction"];
$noteToAdd;
$noteToEdit;


function process_form($noteID , $Note, $action){

if($action == "add"){}

else if($action == "edit"){}

else if($action == "remove"){}


}

?>
<html>
    <head>
        <title>
            <?PHP
if($formAction=='searchNote'){echo ("Search Training Note")}

if($formAction=='addNote'){echo ("Add Training Note"}

if($formAction=='editNote'){echo ("Edit Training Note"}

if($formAction=='removeNote'){echo ("Remove Training Note"}



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
                if($formAction=='searchNote'){}

                if($formAction=='addNote'){}
                
                if($formAction=='editNote'){}
                
                if($formAction=='removeNote'){}
                ?>
            </div>
        </div>
</html>