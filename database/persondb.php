<?php
include_once('dbinfo.php');
include_once('./domain/Person.php');

function add_person($person) {
 
    //Legacy code check to ensure the parameter is a Person object.
    if (!$person instanceof Person) {
        die("Error: add_person userType mismatch");
    }
    
    //Create a database connection and check if the person to add already exists.
    $con=connect();
    $query = "SELECT * FROM persondb WHERE firstName='" . $person->get_firstName() . "' AND lastName='" . $person->get_lastName() . "' AND fullName='" . $person->get_fullName() . "' AND phone='" . $person->get_phone() . "' AND username='" . $person->get_username() . "';";

    $result = mysqli_query($con,$query);

    //If the person to add doesn't exist,
    if ($result == null || mysqli_num_rows($result) == 0) {
        
        //add the person to the database.
        mysqli_query($con,'INSERT INTO persondb (firstName, lastName, fullName, phone, email, username, pass, userType) VALUES("' .
                $person->get_firstName() . '","' .
                $person->get_lastName() . '","' .
                $person->get_fullName() . '","' .
                $person->get_phone() . '","' .
                $person->get_email() . '","' .
                $person->get_username() . '","' .
                $person->get_pass() . '","' .
                $person->get_userType() . '");');							        
        
        //Close the connection and return true.
        mysqli_close($con);
        return true;
    }

    //Otherwise, close the connection and return false.
    mysqli_close($con);
    return false;
}

function delete_person($username) {
 
    //Create a database connection and delete the person from the database based on their username.
    $con = connect();
    $query = "DELETE FROM persondb WHERE username='" . $username . "'";
    $result = mysqli_query($con, $query);

    //If the query was successful, return true. Otherwise, return false.
    if ($result) {
        mysqli_close($con);
        return true;
    } else {
        mysqli_close($con);
        return false;
    }
}

function edit_person($oldUsername, $person) 
{

    //Legacy code check to ensure the parameter is a Person object.
    if (!$person instanceof Person) {
        die("Errors: edit_person userType mismatch");
    }

    //Create a database connection and update the database.
    $con=connect();    
    $query = "UPDATE persondb SET firstName='" . $person->get_firstName() . "', 
                                  lastName='" . $person->get_lastName() . "', 
                                  fullName='" . $person->get_fullName() . "', 
                                  phone='" . $person->get_phone() . "', 
                                  email='" . $person->get_email() . "', 
                                  username='" . $person->get_username() . "', 
                                  pass='" . $person->get_pass() . "', 
                                  userType='" . $person->get_userType() . "'
                                  WHERE username='" . $oldUsername . "';";

    $result = mysqli_query($con,$query);
    
    //Close the connection and return true.
    mysqli_close($con);
    return true;
}

function remove_person($username) {

    //Create a database connection and remove the trainer from the database.
    $con=connect();

    //Saves the trainer that is being deleted
    $archived = retrieve_person_by_username($username);
    
    // Sets the archive boolean and archive date for the trainer being removed
    $query = 'UPDATE persondb SET archive=true, archiveDate=CURDATE() WHERE username = "' . $username . '"';
    $result = mysqli_query($con,$query);
    
    //Close the connection and return true.
    mysqli_close($con);
    return true;
}

function retrieve_person_by_username($username) {
    global $db;
    $sql = "SELECT * FROM persons WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        return new Person($result);
    } else {
        return null;
    }
}

function getall_persondb() {

    //Create a connection and retrieve all the people information.
    $con = connect();
    $query = "SELECT * FROM persondb WHERE archive IS NULL OR archive = 0 ORDER BY lastName, firstName";
    $result = mysqli_query($con, $query);
    

    //If the person table is empty,
    if ($result == null || mysqli_num_rows($result) == 0) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create an array, create a Person object for each query row, and add it to the array.
    $thePersons = array();

    while ($result_row = mysqli_fetch_assoc($result)) {
        $thePerson = make_a_person($result_row);
        $thePersons[] = $thePerson;
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $thePersons;
}

function getinactive_persondb() {

    //Create a connection and retrieve all the inactive people information.
    $con = connect();
    $query = "SELECT * FROM persondb WHERE archive = 1 ORDER BY lastName, firstName";
    $result = mysqli_query($con, $query);
    

    //If the inactive person table is empty,
    if ($result == null || mysqli_num_rows($result) == 0) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create an array, create a Person object for each query row, and add it to the array.
    $thePersons = array();

    while ($result_row = mysqli_fetch_assoc($result)) {
        $thePerson = make_a_person($result_row);
        $thePersons[] = $thePerson;
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $thePersons;
}

function getall_person_names() {

    //Create a database connection and retrieve all of the full names.
    $con=connect();
    $query = "SELECT fullName FROM persondb ORDER BY fullName";
    $result = mysqli_query($con,$query);

    //If the person table is empty,
    if ($result == null || mysqli_num_rows($result) == 0) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create an array, and add each full name to the array.
    $result = mysqli_query($con,$query); //This line might be redundant.
    $names = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $names[] = $result_row['fullName'];
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $names;
}
  
function getall_usernames() {

    //Create a connection and retrieve all the usernames.
    $con=connect();
    $query = "SELECT username FROM persondb WHERE archive IS NULL OR archive = 0 ORDER BY username";
    $result = mysqli_query($con,$query);
    

    //If the person table is empty,
    if ($result == null || mysqli_num_rows($result) == 0) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create an array and add each username to the array.
    $usernames = array();

    while ($result_row = mysqli_fetch_assoc($result)) {
        $usernames[] = $result_row['username'];
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $usernames;
}
  
function getall_usernames_inactive() {

    //Create a connection and retrieve all the usernames.
    $con=connect();
    $query = "SELECT username FROM persondb WHERE archive = 1 ORDER BY username";
    $result = mysqli_query($con,$query);
    

    //If the person table is empty,
    if ($result == null || mysqli_num_rows($result) == 0) {

        //close the connection and return false.
        mysqli_close($con);
        return false;
    }

    //Otherwise, create an array and add each username to the array.
    $usernames = array();

    while ($result_row = mysqli_fetch_assoc($result)) {
        $usernames[] = $result_row['username'];
    }

    //Close the connection and return the array.
    mysqli_close($con);
    return $usernames;
}

function make_a_person($result_row) {
    $username = str_replace('-', '', $result_row['phone']);
    $thePerson = new Person(
                $result_row['firstName'],
                $result_row['lastName'],
                $result_row['fullName'],
                $result_row['phone'],
                $result_row['email'],
                $result_row['username'],
                $result_row['pass'],
                $result_row['userType'],
                $result_row['archive'],
                $result_row['archiveDate']
                );
    return $thePerson;
}


function get_numPersons() {

    //If "getall_behavior_titles()" yields an empty query,
    if (getall_person_names() == 0) {

        //return 0.
        return 0;
    }

    //Otherwise, save the returned array from "getall_person_names()" and return the count of that array.
    $numNames = getall_person_names();
    return count($numNames);
}