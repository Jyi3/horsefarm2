<?php
    include('database/dbinfo.php');
    include('domain/Horse.php');
    include('database/horsedb.php');
    include('domain/Behavior.php');
    include('database/behaviordb.php');
    include('domain/person.php');
    include('database/person.php');


/*
Function name: assignBehavior
Description: Assigns abehvaior to a horse. It does this in the horsetobehaviordb. 
Parameters: 
            HorseID --> ID of the horse who we want to add a behavior to    (type: string)
            Behavior --> Name of the behavior we want to give said horse.   (type: behavior)
Return Value(s):
            True --> Returns true if the behavior was succesfully added to the given horse
            False --> Returns false if the system was unable to assign the behavior to the given horse
*/

function assignBehavior($horseID, $behavior){
    //makes sure the inputted behavior is actually a Behavior type (if not it returns false)
        if(!$behavior instanceof Behavior ){
            return false;
        }
        //makes sure the horseID is an actual string (not an integer or something else)
        elseif(is_string($horseID) == false){
            return false;
        }
        $behaviorName = $behavior->get_title();

        $con = connect();
        $checkQuery = "SELECT * FROM horsetobehaviordb WHERE horseid='" . $horseID . "' AND behavior='" . $behaviorName . "';";
        $check = mysqli($con, $checkQuery);
        //checks and makes sure that the horse does not already have that behavior assigned to it
        //If it does not, then add the horse and behavioe to the database. 
        if($check == null  || mysqli_num_rows($check) == 0){

            $query = 'INSERT INTO horsetobehaviordb (horseid, behavior) VALUES ("' .
                $horseID . '","' .
                $behaviorName . '");';
            mysqli($con, $query);
            mysqli_close($con);

            return true;
        
        }
    
        //if the horse already has the hebaior added to it, we close the connection an dreturn false. 
        mysqli_close($con);
        return false;  

}


/*
Function name: unassignBehavior
Description: Unassigns abehvaior from a horse. It does this in the horsetobehaviordb. 
Parameters: 
            HorseID --> ID of the horse who we want to add a behavior to    (type: string)
            
Return Value(s):
            True --> Returns true if the behavior was succesfully removed from the given horse
            False --> Returns false if the system was unable to remove the behavior from the given horse
*/
function unassignBehavior($horseID, $behavior){
//makes sure the inputted behavior is actually a Behavior type (if not it returns false)
    if(!$behavior instanceof Behavior ){
        return false;
    }
    //makes sure the horseID is an actual string (not an integer or something else)
    elseif(is_string($horseID) == false){
        return false;
    }
    $con = connect();

    $behaviorName = $behavior->get_title();

    $checkQuery = "SELECT * FROM horsetobehaviordb WHERE horseid='" . $horseID . "' AND behavior='" . $behaviorName . "';";
    $check = mysqli($con, $checkQuery);
    //Checks and make sure that the horse already has been assigned the given behavior. If it has, then it deletes it.
    if($check != null  || mysqli_num_rows($check) != 0){

        $query = 'DELETE FROM horseyobehaviordb (horseid, behavior) WHERE horseID="' . $horseID . '" AND behavior="' .$behaviorName . '";';
        mysqli($con, $query);
        mysqli_close($con);

        return true;
    
    }
    //if the horse hasnt already been assigned the beavior, we simply close the mysqli connection and return false
    mysqli_close($con);
    return false;
}

//Will add a function here that takes in a horseID, and depending on their rank automatically add the right behaviors of the same color rank and below
/*
Function name: autoAssignBehaviors
Description: Automatically assigns all behaviors of certain levels, depending on the colorRank of the horse.
             So if the horse is Red, it automatically gets all green, yellow and red behaviors. 
             If it is yellow it automatically gets all green and yellow behaviors.
             If it is green it only gets all green behaviors assigned to it. 
Parameters: 
            HorseID --> ID of the horse who we want to add a behavior to    (type: string)

Return Value(s):
            True --> Returns true if the behavior was succesfully added to the given horse
            False --> Returns false if the system was unable to assign the behavior to the given horse
*/
function autoAssignBehaviors($horseID){
    //Horse object that corresponds to the ID inputted
    $horse = retrieve_horse($horseID);
    //Color of the inputted horse (defines what behaviors get assigned to it)
    $color = $horse->get_colorRank();
    //If the horse is has a green colorRank, then it gets all the green behaviors
    if($color == "Green"){

        $behaviors = get_all_green_behaviors();
        //Gets the all the titles of all green behaviors (get it in a seperate array so we can call it by its index in the for loop below)
        $behaviorTitles = array_keys($behaviors);
        //number of behaviors to be assigned
        $numBehaves = count($behaviors);
        $con = connect();

        //Loops through all the behaviors that need to be assigned to the inputted horse
        for($i=0; $i < $numBehaves; $i++){
            
            //Title of the current behavior that the loop is on
            $behaveName = $behaviorTitles[$i];
            //Query that gets all the info of the current behavior so we can then make a behavior object out of it to then be asigned
            $query = "SELECT * FROM behaviorDB WHERE title='". $behaveName . "';";
            $result= mysqli($con, $query);
            $curBehavior = make_a_behavior($result);
            //assigns the current behavior to the given horse
            assignBehavior($horseID,$curBehavior);
            return TRUE;
        }

    }
    elseif($color == "Yellow"){
        $greens = get_all_green_behaviors();
        $yellows = get_all_yellow_behaviors();
        //One big array of all green and yellow behaviors
        $behaviors = array_merge($greens,$yellows);
        //Gets all the titles of all yellow and green behaviors and puts it into a seperate array (so we can call the titles by its index in the for loop below)
        $behaviorTitles = array_keys($behaviors);
        //number of behaviors to be assigned
        $numBehaves = count($behaviors);
        $con = connect();

        //Loops through all the behaviors needed to be assigned
        for($i=0; $i < $numBehaves; $i++){
            //Title of the current behavior the loop is on
            $behaveName = $behaviorTitles[$i];
            //Query that gets all info of the current behavior to then make a behavior object out of it
            $query = "SELECT * FROM behaviorDB WHERE title='". $behaveName . "';";
            $result = mysqli($con,$query);
            $curBehavior = make_a_behavior($result);
            //assigns current behavior to the given horse
            assignBehavior($horseID, $curBehavior);
        }
        return TRUE;
    }
    elseif($color == "Red"){

        $greens = get_all_green_behaviors();
        $yellows = get_all_yellow_behaviors();
        $reds = get_all_red_behaviors();
        //One big array of all green, yellow, and red behaviors
        $behaviors = array_merge($greens,$yellows,$reds);
        //Gets all the titles of all red, yellow and green behaviors and puts it into a seperate array (so we can call the titles by its index in the for loop below)
        $behaviorTitles = array_keys($behaviors);
        //number of behaviors to be assigned
        $numBehaves = count($behaviors);
        $con = connect();

        //Loops through all the behaviors needed to be assigned
        for($i=0; $i < $numBehaves; $i++){
            //Title of the current behavior the loop is on
            $behaveName = $behaviorTitles[$i];
            //Query that gets all info of the current behavior to then make a behavior object out of it
            $query = "SELECT * FROM behaviorDB WHERE title='". $behaveName . "';";
            $result = mysqli($con,$query);
            $curBehavior = make_a_behavior($result);
            //assigns current behavior to the given horse
            assignBehavior($horseID, $curBehavior);
        }
        return TRUE;
    }
    else{
        return FALSE;
    }
}



/*
Function name: get_a_horses_behaviors
Description: Gets and returns all of the behaviors that are assigned to a given horse (in horsetobehaviordb)
Parameters: 
            HorseID --> ID of the horse who we want to add a behavior to

Return Value(s):
            $allBehaves --> Returns an array containing the names of all the behaviors that are assigned to a given horse
            False --> Returns false if the horseID was not inputted in the correct format, or if the horse has no behaviors assigned to it
*/
function get_a_Horses_Behaviors($horseID){
    if(is_string($horseID) == FALSE){
        return false;
    }

    $con = connect();
    
    $query = "SELECT * FROM horseToBehaviorDB WHERE horseid='" . $horseID . "';";
    $allBehaves = mysqli($con,$query);

    //If a horse has no behaviors assigned to it, we return false.
    if($allBehaves == null || mysqli_num_rows($check) == 0){
        return false;
    }
    mysqli_close($con);
    return $allBehaves;

}

?>