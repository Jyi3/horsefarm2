<?php
    include('database/dbinfo.php');
    include('domain/Horse.php');
    include('database/horsedb.php');
    include('domain/person.php');
    include('database/person.php');

/*
Function name: assignTrainer
Description: Assigns abehvaior to a horse. It does this in the horsetobehaviordb. 
Parameters: 
            HorseID --> ID of the horse who we want to assign the trainer to   (type: string)
            TrainerID --> ID of the trainer who we want to assign the horse to.   (type: string)

Return Value(s):
            True --> Returns true if the btrainer was succesfully assigned to the given horse
            False --> Returns false if the system was unable to assign the trainer to the given horse
*/

function assignTrainer($horseID, $trainerID){

    //makes sure the horseID and trainerIDis an actual string (not an integer or something else)
        if($horseID is_string == false || $trainerID is_string == false){
            return false;
        }
        $con = connect();
        $checkQuery = "SELECT * FROM horseToTrainerDB WHERE horseName='" . $horseID . "' AND trainerName='" . $trainerID . "';";
        $check = mysqli($con, $checkQuery)
        //checks and makes sure that the horse does not already have that trainer assigned to it
        //If it does not, then add the horse and trainer to the database. 
        if($check == null  || mysqli_num_rows($check) == 0){

            $query = 'INSERT INTO horseToTrainerDB (horseName, trainerName) VALUES ("' .
                $horseID . '","' .
                $trainerID . '");';
            mysqli($con, $query);
            mysqli_close($con);
            return true;
        
        }
    
        //if the horse already has the trainer added to it, we close the connection an dreturn false. 
        mysqli_close($con);
        return false;  

}


/*
Function name: unassignTrainer
Description: Unassigns abehvaior from a horse. It does this in the horsetobehaviordb. 
Parameters: 
            HorseID --> ID of the horse who we want to add a behavior to    (type: string)
            
Return Value(s):
            True --> Returns true if the behavior was succesfully removed from the given horse
            False --> Returns false if the system was unable to remove the behavior from the given horse
*/
function unassignTrainer($horseID, $trainerID){

//makes sure the horseID  and trainerIDis an actual string (not an integer or something else)
    if($horseID is_string == false || $trainerID is_string == false){
        return false;
    }
    $con = connect();

    $checkQuery = "SELECT * FROM horseToBehaviorDB WHERE horseName='" . $horseID . "' AND trainerNamer='" . $behaviorName . "';";
    $check = mysqli($con, $checkQuery)
    //Checks and make sure that the horse already has been assigned the given trainer. If it has, then it deletes it.
    if($check != null  || mysqli_num_rows($check) != 0){

        $query = 'DELETE FROM horseToBehaviorDB (horseName, trainerName) WHERE horseName="' . $horseID . '" AND trainerName="' .$trainerID . '";';
        mysqli($con, $query);
        mysqli_close($con);

        return true
    
    }
    //if the horse hasnt already been assigned the trainer, we simply close the mysqli connection and return false
    mysqli_close($con);
    return false;
}

/*
Function name: get_a_horses_trainers
Description: Gets and returns all of the trainer that are assigned to a given horse (in horsetobehaviordb)
Parameters: 
            HorseID --> ID of the horse who we want to add a behavior to

Return Value(s):
            $allBehaves --> Returns an array containing the names of all the trainers that are assigned to a given horse
            False --> Returns false if the horseID was not inputted in the correct format, or if the horse has no trainers assigned to it
*/
function get_a_Horses_Trainers($horseID){
    if($horseID is_string == false){
        return false;
    }

    $con = connect();
    
    $query = "SELECT * FROM horseToBehaviorDB WHERE horseid='" . $horseID . "';";
    $allTrainers = mysqli($con,$query);

    //If a horse has no behaviors assigned to it, we return false.
    if($allTrainers == null || mysqli_num_rows($check) == 0){
        return false;
    }
    mysqli_close($con);
    return $allTrainers;

}

?>