<?PHP

if($formAction == "addHorse" || $formAction == "confirmAdd") {

echo("<p><strong>Add Note Information</strong></p>");
echo("<p>Add note information using the fields below.</p>");
echo('<p>When finished, hit <b>"Add Note"</b> at the bottom of this page.</p>');
echo("<p>(<span style='font-size:x-small;color:FF0000'>*</span> denotes required information).</p>");

if($formAction=="addNote"){echo("<form method='POST' action='/horse/horsefarm2/noteActions.php?formAction=confirmAdd'>");
}

if($formAction=="confirmAdd"){

    echo("<p> MAKE A WAY TO CONFIRM THAT A NOTE HAS BEEN ADDED TO THE SYSTEM.</p>");

}
?>

<fieldset>
<lengend>Note information:</lengend>

<?PHP
echo("<p>Horse ID<span style='font-size:x-small;color:FF0000'>*</span>: <input type='text' name='horseID' tabindex='1' value='$horseID'></p>");
                echo("<p>Note Text: <input type='text' name='note' tabindex='1' value='$note'></p>");
                echo("<p>username: <input type='text' name='username' tabindex='1' value='$username'></p>");
}

else { //$formAction == "editNote" or "confirmEdit"
}

?>

<script src="lib/>jquery-1.9.1.js"></script>
<script src="lib/>jquery-ui.js"></script>
</fieldset>

<?PHP

//Display the appropriate submit button text based on what the user wants to do.
if($formAction == "addNote" || $formAction == "confirmAdd") {
    echo("<input type='submit' value='Add Note'/>");
}
else {  //$formAction == "editNote" or "confirmEdit"
    echo("<input type='submit' value='Edit " . $oldName . "'/>");
}
?>