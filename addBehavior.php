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
        if($behavior != instanceof Behavior ){
            return false;
        }
        $behaviorName = $behavior->get_title();
    //makes sure the horseID is an actual string (not an integer or something else)
        elseif($horseID is_string == false){
            return false;
        }
        $con = connect();
        $checkQuery = "SELECT * FROM horsetobehaviordb WHERE horseid='" . $horseID . "' AND behavior='" . $behaviorName . "';";
        $check = mysqli($con, $checkQuery)
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
    if($behavior != instanceof Behavior ){
        return false;
    }
    $behaviorName = $behavior->get_title();
//makes sure the horseID is an actual string (not an integer or something else)
    elseif($horseID is_string == false){
        return false;
    }
    $con = connect();

    $checkQuery = "SELECT * FROM horseToBehavior WHERE horseid='" . $horseID . "' AND behavior='" . $behaviorName . "';";
    $check = mysqli($con, $checkQuery)
    //Checks and make sure that the horse already has been assigned the given behavior. If it has, then it deletes it.
    if($check != null  || mysqli_num_rows($check) != 0){

        $query = 'DELETE FROM horseToBehaviorDB (horseid, behavior) WHERE horseID="' . $horseID . '" AND behavior="' .$behaviorName . '";';
        mysqli($con, $query);
        mysqli_close($con);

        return true
    
    }
    //if the horse hasnt already been assigned the beavior, we simply close the mysqli connection and return false
    mysqli_close($con);
    return false;
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
    if($horseID is_string == false){
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