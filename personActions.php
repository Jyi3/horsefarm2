<?php
/*
 * Copyright 2015 by Allen Tucker. This program is part of RMHC-Homebase, which is free 
 * software.  It comes with absolutely no warranty. You can redistribute and/or 
 * modify it under the terms of the GNU General Public License as published by the 
 * Free Software Foundation (see <http://www.gnu.org/licenses/ for more information).
 */
/*
 * 	behaviorEdit.php
 *  oversees the editing of a behavior to be added, edited, or deleted from the database
 */

session_start();
include_once('database/persondb.php');
include_once('database/dbinfo.php');
include_once('domain/Person.php');



$formAction = $_GET["formAction"];
$personToAdd;
$personToEdit;
$oldFirstName;
$oldLastName;
$oldFullName;
$oldPhone;
$oldEmail;
$oldUsername;
$oldPass;
$oldUserType;
$arcTime;

function process_form($name, $person, $action) {

    //If the user used the form to add a person, 
    if ($action == "add") {

        //try to add a new person to the database.

        //Check if there's already an entry.
        $dup = retrieve_person_by_username($name);

        //If there's already a person with this name,
        if ($dup == true) {

            //print an error message.
            echo('<p class="error">Unable to alter the database. <br>Another person named ' . $name . ' already exists.<br><br>'); 
            echo('<p>If you wish to add another person, please click "Add person" after "person Actions."</p>');
        }

        //Else, this person would be a new entry,
        else {            

            //so add it to the database.
            $result = add_person($person);

            //Another check to see if the person to add already exists.
            if (!$result) 
                echo('<p class="error">Unable to add to the database. <br>Please report this error.');
            else 
                echo('<p>You have successfully added ' . $person->get_firstName() . ' ' . $person->get_lastName() . ' to the database! If you wish to add another person, please click "Add person" after "person Actions."</p>');

        }
    }

    //Else, if the user used the form to edit a behavior,
    else if($action == "edit") {

        //edit the existing behavior in the database.
        $result = edit_person($name, $person);

        if (!$result) 
            echo('<p class="error">Unable to edit the database. <br>Please report this error.');
        else 
            echo('<p>You have successfully edited the database! If you wish to edit another person, please click "Edit person" after "person Actions."</p>');
    }

    //Else, the user wants to remove a behavior (FOR LATER),
    else {

        //so remove a behavior from the database.
        $result = remove_person($name); 
        if (!$result) 
            echo('<p class="error">Unable to remove from the database. <br>Please report this error.');
        else 
            if (!$person) {
            echo('<p class="error">Invalid person object.</p>');
            return;
            }
            echo('<p>You have successfully removed ' . $person->get_firstName() . ' ' . $person->get_lastName() . ' the database! If you wish to remove another person, please click "Remove person" after "person Actions."</p>');
    }
}

?>
<html>
    <head>
        <title>
            <?PHP
            
                //Set the page title based on what the user wants to do.
                if($formAction == 'searchPeople') {
                    echo("Search People");
                }
                else if($formAction == 'addPerson')  {
                    echo('Add Person Information');
                }
                else if($formAction == 'confirmAdd') {
                    echo('Add Person');
                }
                else if($formAction == 'selectPerson') {
                    echo("Select Person to Edit");
                }
                else if($formAction == 'editPerson') {
                    echo("Edit Person Information");
                }
                else if($formAction == 'confirmEdit') {
                    echo("Edit Person");
                }
                else if($formAction == 'removePerson') {
                    echo("Select Person to Remove");
                }
                else { //$formAction == 'confirmRemove'
                    echo("Remove Person");
                }
            ?>
        </title>
        <style>
            th, tr, td 
            {
                border-left: 1px solid black;
                border-right: 1px solid black;
                border-top: 1px solid black;
                border-bottom: 1px solid black;
            }
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

                //If the user wanted to search all people,
                if($formAction == 'searchPeople') {

                    //check if there are people in the database to edit.
                    $numPersons = get_numPersons();

                    //If there aren't any people in the database, 
                    if($numPersons == 0) {

                        //display an error messsage.
                        echo("<p><strong>There are no people to search.</strong></p>");
                        echo('<p>Please add people using the "Add Trainer" link next to "Trainer Actions".</p><br>');
                    }
                    //Else, there are people in the database,
                    else {
                        //so retrieve and show all of the people in a table.
                        $allPersons = getall_persondb();
                    
                        echo("<h2><strong>List of Active People</strong></h2>");
                        echo("<br>");
                        echo("<table style='float: left; margin-right: 20px;'>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>");
                    
                        for($x = 0; $x < count($allPersons); $x++) {
                            $userName = $allPersons[$x]->get_userName();
                            echo("<tr>
                                <td style='border-left: 1px solid black'><a href='trainerprofile.php?userName=$userName' style='color: blue;'>" . $allPersons[$x]->get_firstName() . "</a></td>
                                <td style='border-left: 1px solid black'><a href='trainerprofile.php?userName=$userName' style='color: blue;'>" . $allPersons[$x]->get_lastName() . "</a></td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_phone() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_email() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_userType() . " </td>
                            </tr>");
                        }
                        echo("</table>");
                    
                        // Second table
                        $allPersons = getall_persondb();
                    
                        echo("<h2><strong>List of Inactive People</strong></h2>");
                        echo("<br>");
                        echo("<table style='float: right;'>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>");
                    
                        for($x = 0; $x < count($allPersons); $x++) {
                            $userName = $allPersons[$x]->get_userName();
                            echo("<tr>
                                <td style='border-left: 1px solid black'><a href='profilepage.php?userName=$userName' style='color: blue;'>" . $allPersons[$x]->get_firstName() . "</a></td>
                                <td style='border-left: 1px solid black'><a href='profilepage.php?userName=$userName' style='color: blue;'>" . $allPersons[$x]->get_lastName() . "</a></td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_phone() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_email() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_userType() . " </td>
                            </tr>");
                        }
                        echo("</table>");
                    }
                    
                    
                }
                //Else, if the user wants to add a behavior,
                else if($formAction == 'addPerson') {


                    //show the form to add/edit behavior information.
                    include('editPersonForm.inc');
                }

                //Else, if the user has submitted behavior information to add,
                else if($formAction == 'confirmAdd') {

                    //attempt to validate and process the form.
                    include('personValidate.inc'); 

                    //If the form has not been submitted (somehow),
                    if ($_POST['_form_submit'] != 1) {

                        //show it again.
                        include('editPersonForm.inc');
                    }

                    //Else, the form has been submitted,
                    else {

                        //so retrieve the form answers and validate it.
                        $newFirstName = $_POST['firstName'];
                        $newLastName = $_POST['lastName'];

                        //If newFirstName = "Owen" and newLastName = "Chong, then newFullName = "Owen Chong".
                        $newFullName = $newFirstName . " " . $newLastName; 
                        $newPhone = $_POST['phone'];
                        $newEmail = $_POST['email'];

                        //If newFirstName = "Owen" and newLastName = "Chong, then newUsername and newPass = "ochong".
                        $newUsername = strtolower(substr($newFirstName, 0, 1)) . strtolower($newLastName);
                        $newPass = strtolower(substr($newFirstName, 0, 1)) . strtolower($newLastName);
                        $newUserType = $_POST['userType'];
                        $newPerson = new Person($newFirstName, $newLastName, $newFullName, $newPhone, $newEmail, $newUsername, $newPass, $newUserType);

                        $errors = validate_form($newPerson);
                        //errors array lists problems on the form submitted

                        //If the user left required fields blank,
                        if ($errors) {

                            //display the errors and the form to fix.
                            show_errors($errors);
                            include('editPersonForm.inc');
                        }

                        //Else, this was a successful form submission,
                        else {

                            //so process the form to add a behavior.
                            process_form($newFullName, $newPerson, "add");
                            echo ('</div>');
                            echo('</div></body></html>');
                            die();
                        }

                    }
                }

                //Else, if the user wants to edit a person,
                else if($formAction == 'selectPerson') {

                    //check if there are persons in the database to edit.
                    $numpersons = get_numPersons();

                    //If there aren't any persons in the database, 
                    if($numpersons == 0) {
                        echo("<p><strong>There are no people to edit.</strong></p>");
                        echo('<p>Please add people using the "Add Trainer" link next to "Trainer Actions".</p><br>');
                    }

                    //Else, display the form for selecting a person to edit.
                    else {
                        include('getPersonForm.inc');
                    }    
                }

                //Else, if the user has selected a person to edit,
                else if($formAction == 'editPerson') {

                    //get the old title of the person, in case the user edited the person.
                    $oldName = $_POST['username'];

                    //Then, display the form for adding/editing behaviors.
                    include("editPersonForm.inc");
                }

                //Else, if the user has submitted behavior information to edit,
                else if($formAction == 'confirmEdit') {
                    
                    //attempt to validate and process the form.
                    include('personValidate.inc'); 
                    
                    //so retrieve the form answers and validate it.
                    $oldName = $_POST['username'];
                    $newFirstName = $_POST['firstName'];
                    $newLastName = $_POST['lastName'];

                    //If newFirstName = "Owen" and newLastName = "Chong, then newFullName = "Owen Chong".
                    $newFullName = $newFirstName . " " . $newLastName; 
                    $newPhone = $_POST['phone'];
                    $newEmail = $_POST['email'];

                    //This time, the user can directly edit the username and password. 
                        //Thus, duplicate usernames and passwords can happen very easily.
                    $newUsername = $newFirstName . $newLastName . str_replace('-', '', $newPhone);
                    $newPass = $_POST['pass'];
                    $newUserType = $_POST['userType'];
                    
                    //If the form has not been submitted (somehow, cuz this code shouldn't run),
                    if ($_POST['_form_submit'] != 1) {

                        //show the form again.
                        include('editPersonForm.inc');
                    }

                    //Else, the form has been submitted,
                    else {

                        //so validate it. BTW, the parameter doesn't matter, because "validate_form" uses the form's $_POST values, NOT the parameter.
                        $errors = validate_form($oldName);

                        //errors array lists problems on the form submitted.

                        //If the user left required fields blank,
                        if ($errors) {

                            //display the errors and the form again.
                            show_errors($errors);
                            include('editPersonForm.inc');
                        }
                                
                        //Else, if the user changed the name of a person to a name that already exists,
                            //Conditions: (1) The person must exist, and (2) the user wants to change the name of the existing person.
                            //If the user left the name the same, then the existing person will be edited under the same name.
                        else if((retrieve_person_by_username($newFullName)) && (strcmp($oldName, $newFullName) != 0)) {
                            
                            //print that the user cannot change a person name to an existing name, and then show the form again.
                            echo("<h4 style='color:FF0000'>" . $newFullName . " is the name of an existing person. Please enter another first and last name combination.</h4><br>");
                            include("editPersonForm.inc");
                        }
                    
                        //Else, this was a successful form submission,
                        else {

                            //so create a Behavior object and process the form to edit a behavior.
                            $personToEdit = new Person($newFirstName, $newLastName, $newFullName, $newPhone, $newEmail, $newUsername, $newPass, $newUserType);
                            echo $newUsername;
                            process_form($oldName, $personToEdit, "edit");
                            echo ('</div>');
                            //include('footer.inc');
                            echo('</div></body></html>');
                            die();
                        }
                    } 
                }

                //Else, if the user wants to remove a person,
                else if ($formAction == 'removePerson') { 
                    
                    //check if there are people in the database to edit.
                    $numPersons = get_numPersons();

                    //If there are no people to remove, 
                    if($numPersons == 0) {

                        //display a message as such.
                        echo("<p><strong>There are no people to remove.</strong></p>");
                        echo('<p>Please add people using the "Add Trainer" link next to "Trainer Actions".</p><br>');
                    }

                    //Else, display the form for selecting a person to edit.
                    else {
                        include('getPersonForm.inc');
                    } 
                    
                }

                //Else, if the user has selected a person to remove,
                else if($formAction == 'confirmRemove') {
                    
                    $oldName = $_POST['username'];

                    //If the form has not been submitted (somehow).
                    if ($_POST['_form_submit'] != 1) {

                        //show the form again.
                        include('editPersonForm.inc');
                    }

                    //Else, the form has been submitted,
                    else {

                        //so validate it. BTW, the parameter doesn't matter, because "validate_form" uses the form's $_POST values, NOT the parameter.
                        //$errors = validate_form($behavior);
                        //errors array lists problems on the form submitted.

                        //If the user left required fields blank,
                        if ($errors) {

                            //display the errors and the form again.
                            show_errors($errors);
                            include('editPersonForm.inc');
                        }
                                                    
                        //Else, this was a successful form submission,
                        else {

                            //so create a Behavior object and process the form to remove a behavior.
                            //$personToRemove = new person($Name, $Color, $Breed, $PastNum, $ColRank);
                           
                             $personToRemove = retrieve_person_by_username($oldName);
                           
                            process_form($oldName, $personToRemove, "remove");
                            echo ('</div>');
                            //include('footer.inc');
                            echo('</div></body></html>');
                            die();
                        }
                    } 
                }
                else {
                    //Nothing should get here!
                }

                ?>
            </div>
            <?PHP include('footer.php'); ?>
        </div>
    </body>
</html>    