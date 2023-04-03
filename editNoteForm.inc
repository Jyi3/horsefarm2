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



}

else { //$formAction == "editNote" or "confirmEdit"
}

?>



<?PHP

//Display the appropriate submit button text based on what the user wants to do.
if($formAction == "addNote" || $formAction == "confirmAdd") {
    echo("<input type='submit' value='Add Note'/>");
}
else {  //$formAction == "editNote" or "confirmEdit"
    echo("<input type='submit' value='Edit " . $oldName . "'/>");
}
?>