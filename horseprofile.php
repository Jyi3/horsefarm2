<?php
    include_once('session.php');
?>

<?php
    include_once('database/dbinfo.php');
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Use $_GET to get the horseid parameter
    $hp_horseID = $_GET["horseID"];

    // Get the list of trainers
    $trainerListSql = "SELECT p.username, p.firstName, p.lastName, p.archive, IF(pt.horseID IS NULL, 0, 1) AS isConnected
        FROM persondb p
        LEFT JOIN persontohorsedb pt ON p.username = pt.username AND pt.horseID = '$hp_horseID'
        WHERE p.archive != 1 AND p.userType NOT IN ('Viewer', 'Admin')";


    $sql = "SELECT * FROM horsedb WHERE horseID = '$hp_horseID'";
    $aNotes = "SELECT n.horseID, n.noteID, t.firstName, t.lastName, n.note, n.noteDate, t.username, n.archive, n.archiveDate
            FROM notesdb n
            INNER JOIN horsedb h ON n.horseID = h.horseID 
            INNER JOIN persondb t ON n.username = t.username
        WHERE n.horseID = '$hp_horseID' AND (n.archive = 0 OR n.archive IS NULL)
        ORDER BY n.noteDate DESC, n.noteTimestamp DESC";

    $arNotes = "SELECT n.horseID, n.noteID, t.firstName, t.lastName, n.note, n.noteDate, t.username, n.archive, n.archiveDate, h.diet
        FROM notesdb n
        INNER JOIN horsedb h ON n.horseID = h.horseID 
        INNER JOIN persondb t ON n.username = t.username
        WHERE n.horseID = '$hp_horseID' AND n.archive = 1
        ORDER BY n.noteDate DESC, n.noteTimestamp DESC";




    $hBehaviors = "SELECT h.title 
                    FROM horsetobehaviordb h 
                    WHERE h.horseID = '$hp_horseID' ";  
    $result = mysqli_query($conn, $sql);
    $activeNotes = mysqli_query($conn, $aNotes);
    $archiveNotes = mysqli_query($conn, $arNotes);
    $trainerListResult = mysqli_query($conn, $trainerListSql);
    $assignedBehaviors = mysqli_query($conn, $hBehaviors);

    $row = mysqli_fetch_assoc($result);
    $horseName = $row["horseName"];
    $hp_horseID = $row["horseID"];
    $diet = $row["diet"];
    $color = $row["color"];
    $breed = $row["breed"];
    $pastureNum = $row["pastureNum"];
    $colorRank = $row["colorRank"];
    $archive = $row["archive"];
    $archiveDate = $row["archiveDate"];
    $behaviorSql = "SELECT b.title, b.behaviorLevel, h.horseID 
    FROM behaviordb b 
    LEFT JOIN horsetobehaviordb h ON b.title = h.title AND h.horseID = '$hp_horseID'
    ORDER BY 
        CASE b.behaviorLevel
            WHEN 'Green' THEN 1
            WHEN 'Yellow' THEN 2
            WHEN 'Red' THEN 3
            ELSE 4
        END,
        b.title";
    $behave = mysqli_query($conn, $behaviorSql);
    $behaviorlist = mysqli_fetch_all($behave);




    // Query for associated horses
    $sql_associated_trainer = 
        "SELECT p.username, p.firstName, p.lastName, p.archive
        FROM persondb p
        INNER JOIN personToHorsedb ph ON ph.username = p.username
        WHERE ph.horseID = '$hp_horseID' AND p.archive != 1";
    $associatedTrainerResult = mysqli_query($conn, $sql_associated_trainer);

    if (mysqli_num_rows($result) != 1) {
        die("Error: Invalid username");
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Edit_Behaviors'])) {
        $hp_horseID = $_POST['horseID'];
        $submitted_behaviors = isset($_POST['behaviors']) ? $_POST['behaviors'] : array();
    
        // Connecting to the database
        include_once('database/dbinfo.php');
        $conn = connect();
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        // Get all behaviors from behaviordb
        $all_behaviors_sql = "SELECT title FROM behaviordb";
        $all_behaviors_result = mysqli_query($conn, $all_behaviors_sql);
        $all_behaviors = array();
        while ($behavior = mysqli_fetch_assoc($all_behaviors_result)) {
            $all_behaviors[] = $behavior['title'];
        }
    
        // Loop through all behaviors
        foreach ($all_behaviors as $behavior) {
            // Check if the behavior is in the submitted behaviors
            if (in_array($behavior, $submitted_behaviors)) {
                // Check if the behavior is already in horsetobehaviordb
                $query = "SELECT * FROM horsetobehaviordb WHERE title = '$behavior' AND horseID = '$hp_horseID'";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) == 0) {
                    // If not in horsetobehaviordb, insert it
                    $insert_query = "INSERT INTO horsetobehaviordb (title, horseID) VALUES ('$behavior', '$hp_horseID')";
                    mysqli_query($conn, $insert_query);
                }
            } else {
                // If the behavior is not in the submitted behaviors and exists in horsetobehaviordb, delete it
                $delete_query = "DELETE FROM horsetobehaviordb WHERE title = '$behavior' AND horseID = '$hp_horseID'";
                mysqli_query($conn, $delete_query);
            }
        }
    
        mysqli_close($conn);
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $hp_horseID = $_POST["horseID"];


        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        else if (isset($_POST["archive"])) {
            if (!isset($_SESSION['permissions']) || ($_SESSION['permissions'] != 3 && $_SESSION['permissions'] != 5)) {
                echo '<script>alert("You do not have permission to archive or activate a horse.");</script>';
            }
            $sql = "UPDATE horsedb SET archive = 1, archiveDate = CURRENT_DATE() WHERE horseID = '$hp_horseID'";
            $action_success = mysqli_query($conn, $sql);
            if (!$action_success) {
                echo "Error updating record: " . mysqli_error($conn);
            }
            mysqli_close($conn);
        } elseif (isset($_POST["activate"])) {  
            if (!isset($_SESSION['permissions']) || ($_SESSION['permissions'] != 3 && $_SESSION['permissions'] != 5)) {
                echo '<script>alert("You do not have permission to archive or activate a horse.");</script>';
            }
            $sql = "UPDATE horsedb SET archive = 0 WHERE horseID = '$hp_horseID'";
            $action_success = mysqli_query($conn, $sql);
            if (!$action_success) {
                echo "Error updating record: " . mysqli_error($conn);
            }
            mysqli_close($conn);
        }

        echo "<script>window.location.href = '" . $_SERVER["PHP_SELF"] . "?horseID=$hp_horseID';</script>";
        exit();
    }

    
    
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            display: flex;
            flex-direction: column;
            min-height: 500px;
        }

        #appLink:visited {
            color: gray; 
        }

       
        #content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: left;
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
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #4b6c9e;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .profile-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .profile-name {
            vertical-align: top;
            text-align: center;
            margin-top: 3vh;
            align-items: center;
        }

        .profile-details {
            margin-top: 5%;
            display: left;
            flex-direction: column;
            align-items: left;
            font-weight: bold;
            text-align: left;
            padding-top: 5%;    
        }

        .archive-form input[type="submit"] {
            background-color: #4b6c9e;
            color: #fff;
            border: none;
            padding: 8px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 20px;
        }
        
        .archive-form input[type="submit"]:hover {
            background-color: #fff;
            color: #4b6c9e;
            border: 2px solid #4b6c9e;
        }

        .archive-form-button {
            background-color: #4b6c9e;
            color: #fff;
            border: none;
            padding: 8px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 20px;
        }

        .archive-form-button:hover {
            background-color: #fff;
            color: #4b6c9e;
            border: 2px solid #4b6c9e;
        }

        .notes-container {
            text-align: center;
            max-width: 800px;
            width: 100%;
            min-height: 500px;
        }

        
        .trainer-list-container {
            margin-top: 20px;
            margin-bottom: 20px;
            align-items: left;
            text-align: left;
            margin-right: auto;
        }

        .trainer-list-form input[type="text"] {
            padding: 6px 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            align-items: left;
            text-align: left;
            margin-right: auto;
        }

        li {
            list-style-type: none;
            align-items: center;
        }
        /* Footer */
        .footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 10px 0;
            text-align: center;
            margin-top: auto;
        }
        .footer p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }
        
        .columns-container {
            display: flex;
        }

        .profile-details,
        .profile-name,
        .add-behaviors-container {
            width: 33.33%;
            box-sizing: border-box;
            padding: 0 10px;
        }

        .add-behaviors-container {
            margin-top: 5%;
            align-items: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            max-width: 600px; /* You can adjust this value to fit your needs */
            
        }

        .behavior-list {
            margin-left: auto;
            text-align: right;
        }
        .behavior-column {
            text-align: right;
            align-items: right;
            display: inline-block;
            box-sizing: border-box;
            margin: 0 auto; /* Center the container */
        }
                
        .behavior-list h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .behavior-list h3 {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        .behavior-list ul {
            text-align: right;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .behavior-column li {
            text-align: right;
            display: block;
            margin-left: 5px; 
        }
        .behavior-column li:nth-child(3n+1) {
            clear: left;
            text-align: right;
        }
        .behavior-submit-button {
            background-color: #4b6c9e;
            color: #fff;
            border: none;
            padding: 8px 20px;
            font-size: 16px;
            cursor: pointer;
            float: right; /* Aligns the button to the right */
        }

        .behavior-submit-button:hover {
            background-color: #fff;
            color: #4b6c9e;
            border: 2px solid #4b6c9e;
        }


        @media (max-width: 768px) {
            #container {
                padding: 10px;
            }
            h1 {
                font-size: 24px;
                margin-top: 30px;
            }
            p {
                font-size: 16px;
                margin-top: 20px;
                line-height: 1.5;
            }
            table {
                font-size: 14px;
            }
            .profile-container {
                flex-direction: column;
                align-items: center;
            }
            .profile-name {
                margin-top: 20px;
            }
            .profile-details {
                margin-top: 20px;
                text-align: center;
                padding-top: 0;
            }
            .trainer-list-container {
                align-items: center;
                text-align: center;
                margin-top: 20px;
            }
            .trainer-list-form input[type="text"] {
                width: 80%;
                margin-right: 0;
                margin-bottom: 10px;
            }
            .archive-form input[type="submit"],
            .archive-form-button {
                margin-top: 20px;
                margin-left: 0;
                width: 80%;
            }
            .notes-container {
                min-height: 400px;
            }
            .behavior-column {
                display: block;
                width: 100%;
                padding-right: 0;
            }
            .add-behaviors-container {
                width: 100%;
                align-items: center;
                margin-top: 20px;
                margin-bottom: 20px;
            }
            .behavior-submit-button {
                width: 80%;
                margin-top: 20px;
                margin-bottom: 20px;
                margin-left: 0;
            }
        }

    </style>

</head>
<body>
    <div id="container">
        <?PHP include('header.php'); ?>
        
            
        <title><?php echo $horseName; ?>'s Profile</title>


        <div id="content">
            <div class="profile-container">
                <div class="columns-container">
                    <div class="profile-details">
                        <p><span style="font-size:105%;">  Horse Name : </span>  <span style="color:Grey"> <?php echo $horseName; ?></span></p>
                        <p><span style="font-size:105%;">  Difficulty : </span> <span style="color:Grey"> <?php echo $colorRank; ?></span>  </p>
                        <p><span style="font-size:105%;">  Diet: </span><span style="color:Grey"> <?php echo $diet; ?></span></p>
                        <p><span style="font-size:105%;">Pasture : </span><span style="color:Grey"> <?php echo $pastureNum; ?></span></p>
                        <p><span style="font-size:105%;">Breed : </span><span style="color:Grey"><?php echo $breed; ?></span></p>
                        <p><span style="font-size:105%;">Color : </span><span style="color:Grey"><?php echo $color; ?></span></p>
                        <div style="display: flex; align-items: center;">
                            <p style="margin: 0;">Status : <?php echo ($archive == 0 || $archive == NULL) ? 'Active' : 'Inactive'; ?></p>
                            <?php if ($_SESSION['permissions'] >= 3): ?>
                                <form method="POST" class="archive-form" style="display: flex; align-items: center; margin-left: 10px;">
                                    <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>" />
                                    <input type="submit" name="archive" value="Inactivate" <?php if ($archive == 1) echo 'style="display:none"'; ?> style="margin-left: 10px;" />
                                    <input type="submit" name="activate" value="Activate" <?php if ($archive == 0 || $archive == NULL) echo 'style="display:none"'; ?> style="margin-left: 10px;" />
                                </form>
                            <?php endif; ?>
                        </div>


                        <?php
                        if ($archive == NULL || $archive == 0) 
                        {
                            $buttonLabel = "Archive";
                        }
                        if ($archive == 1) 
                        {
                            $buttonLabel = "Activate";
                        }
                        ?>
                        

                                            
                    </div>


                    <div class="profile-name">
                        <h1><?php echo $horseName; ?></h1>
                        <?php if ($_SESSION['permissions'] >= 2): ?>
                            <form method="POST" action="editHorse.php">
                                <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                                <button type="submit" class="archive-form-button">Edit <?php echo $horseName?>'s Profile</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    
                    <div class="add-behaviors-container">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?horseID=' . $hp_horseID; ?>">
                            <div class="behavior-list">
                                <h2>Add Behaviors</h2>
                                <?php
                                include_once('database/dbinfo.php');
                                $conn = connect();

                                if (!$conn) {
                                    die("Connection failed: " . mysqli_connect_error());
                                }

                                // Use $_GET to get the horseid parameter
                                $hp_horseID = $_GET["horseID"];

                                $behaviorSql = "SELECT b.title, b.behaviorLevel, h.horseID 
                                    FROM behaviordb b 
                                    LEFT JOIN horsetobehaviordb h ON b.title = h.title AND h.horseID = '$hp_horseID'
                                    WHERE b.title NOT LIKE '' AND b.title NOT LIKE '% '
                                    ORDER BY 
                                        CASE b.behaviorLevel
                                            WHEN 'Green' THEN 1
                                            WHEN 'Yellow' THEN 2
                                            WHEN 'Red' THEN 3
                                            ELSE 4
                                        END ASC,
                                        b.behaviorLevel ASC,
                                        b.title ASC";
                                $behaviorResult = mysqli_query($conn, $behaviorSql);

                                $current_behaviorLevel = "";
                                while ($behaviorRow = mysqli_fetch_assoc($behaviorResult)) {
                                    if ($current_behaviorLevel != $behaviorRow['behaviorLevel']) {
                                        if ($current_behaviorLevel != "") {
                                            echo "</div>"; // close previous behavior-column div
                                        }
                                        $current_behaviorLevel = $behaviorRow['behaviorLevel'];
                                        echo "<h3>" . ucfirst($current_behaviorLevel) . "</h3>";
                                        echo '<div class="behavior-column">'; // open new behavior-column div
                                        echo "<ul>"; // add ul tag
                                    }
                                    $color = isset($behaviorRow['horseID']) ? 'green' : 'red';
                                    echo '<li style="color: black;"><input type="checkbox" name="behaviors[]" value="' . $behaviorRow['title'] . '"' . ($color == 'green' ? ' checked' : '') . '> ' . $behaviorRow['title'] . '</li>';
                                }
                                if ($current_behaviorLevel != "") {
                                    echo "</ul>"; // close ul tag
                                    echo "</div>"; // close last behavior-column div
                                }

                                mysqli_close($conn);
                                ?>
                            </div>
                            <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                            <?php if ($_SESSION['permissions'] >= 2): ?>
                                <input type="submit" name="Edit_Behaviors" value="Edit Behaviors" class="behavior-submit-button">
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Add the new Trainer List form container -->
            <div class="trainer-list-container;">
                <h2 style="text-align: left;">Trainer List</h2>
                <div class="trainer-list-container">
                    <?php if ($_SESSION['permissions'] >= 3): ?>
                    <select id="trainer-dropdown">
                        <option value="" selected disabled hidden>Select a trainer</option>
                        <?php while ($row = mysqli_fetch_assoc($trainerListResult)): ?>
                            <?php
                                $trainerColor = ($row['isConnected'] == 1) ? 'green' : 'red';
                                $trainerName = $row['firstName'] . ' ' . $row['lastName'];
                            ?>
                            <option value="<?php echo $row['username']; ?>" style="color: <?php echo $trainerColor; ?>;">
                                <strong><?php echo $trainerName; ?></strong>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <input type="hidden" id="selected_trainer" name="username" value="" />
                        <input type="submit" id="add-remove-button" name="add_remove_trainer" value="Add/Remove" class="archive-form-button" />
                    <?php endif; ?>
                </div>
                <?php if (mysqli_num_rows($associatedTrainerResult) == 0): ?>
                    <br>
                    <p style="text-align: left;">No trainers associated with <?php echo $horseName; ?></p>
                <?php else: ?>
                    <ul>
                    <?php while ($row = mysqli_fetch_assoc($associatedTrainerResult)): ?>
                        <li><a href='trainerprofile.php?username=<?php echo $row['username']; ?>' style='color: blue;'><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></a></li>
                    <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div style="display: flex; justify-content: center; align-items: center;">
                <div id="content-inner" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <h2 style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">Notes
                        <?php if ($_SESSION['permissions'] >= 2): ?>
                            <form method="POST" class="archive-form" action="addNotePage.php">
                                <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                                <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                                <input type="submit" name="add_note" value="Add Note" class="archive-form-button" />
                            </form>
                        <?php endif; ?>
                    </h2>

                    <?php if (mysqli_num_rows($activeNotes) == 0): ?>
                        <p style="text-align: center;">No notes available for <?php echo $horseName; ?></p>
                    <?php else: ?>
                        <table class="notes-table">
                            <thead>
                                <tr>
                                    <th class="person-name">Trainer Name</th>
                                    <th>Note</th>
                                    <th class="note-date">Note Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($activeNotes)): ?>
                                    <tr>
                                        <?php
                                        // Hides archived notes for non head-trainer accounts
                                        if ($row['archive'] == 1 && $_SESSION['permissions'] < 2) {
                                            continue;
                                        }
                                        ?>
                                        <td>
                                            <div style="text-align: center;">
                                                <a href='trainerprofile.php?username=<?php echo $row['username']; ?>' style='color: blue;'><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></a>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="text-align: center;"><?php echo nl2br($row['note']); ?></div>
                                        </td>
                                        <td>
                                            <div style="text-align: center;">
                                                <?php if ($_SESSION['permissions'] >= 3): ?>
                                                    <form class="archive-form" method="POST" action="editNotePage.php">
                                                        <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                                                        <input type="hidden" name="noteID" value="<?php echo $row['noteID']; ?>">
                                                        <input type="submit" name="editNote" value="<?php echo $row['noteDate']; ?>">
                                                    </form>
                                                <?php elseif ($_SESSION['permissions'] <= 2): ?>
                                                    <p><?php echo $row['noteDate']; ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <?php if ($row['archive'] == 1): ?>
                                            <td class="note-cell">Archived</td>
                                            <td class="note-cell"><?php echo $row['archiveDate']; ?></td>
                                        <?php endif; ?>
                                        <?php if ($_SESSION['permissions'] == 3): ?>
                                            <td>
                                                <?php if ($row['archive'] != 1): ?>
                                                    <form class="archive-form" method="POST" action="archiveNotePage.php">
                                                        <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                                                        <input type="hidden" name="noteID" value="<?php echo $row['noteID']; ?>">
                                                        <input type="submit" name="removeNote" value="Archive">
                                                    </form>
                                                <?php else: ?>
                                                    <form class="archive-form" method="POST" action="dearchiveNotePage.php">
                                                        <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                                                        <input type="hidden" name="noteID" value="<?php echo $row['noteID']; ?>">
                                                        <input type="submit" name="unremoveNote" value="Activate">
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            <br>
            <div style="display: flex; justify-content: center; align-items: center;">
            <div id="content-inner" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                <?php if ($_SESSION['permissions'] == 3): ?>
                    <h2 style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">Archived Notes
                    <?php if (mysqli_num_rows($archiveNotes) == 0): ?>
                        <p style="text-align: center;">No archived notes available for <?php echo $horseName; ?></p>
                    <?php else: ?>
                        <table class="notes-table">
                            <thead>
                                <tr>
                                    <th class="person-name" style="text-align: center;">Trainer Name</th>
                                    <th class="note-date" style="text-align: center;">Note</th>
                                    <th class="note-date" style="text-align: center;">Note Date</th>
                                    <th class="note-date" style="text-align: center;">Archived Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($archiveNotes)): ?>
                                    <tr>
                                        <?php
                                        // Hides archived notes for non head-trainer accounts
                                        if ($row['archive'] == 1 && $_SESSION['permissions'] < 2) {
                                            continue;
                                        }
                                        ?>
                                        <td class="person-name" style="text-align: center;">
                                            <a href='trainerprofile.php?username=<?php echo $row['username']; ?>' style='color: blue;'><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></a>
                                        </td>
                                        <td class="note-cell" style="text-align: center;"><?php echo nl2br($row['note']); ?></td>
                                        <td>
                                            <form class="archive-form" method="POST" action="editNotePage.php">
                                                <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                                                <input type="hidden" name="noteID" value="<?php echo $row['noteID']; ?>">
                                                <input type="submit" name="editNote" value="<?php echo $row['noteDate']; ?>">
                                            </form>
                                        </td>
                                        <td class="note-cell" style="text-align: center;" ><?php echo $row['archiveDate']; ?></td>
                                        <?php if ($_SESSION['permissions'] == 3): ?>
                                            <td>
                                                <?php if ($row['archive'] != 1): ?>
                                                    <form class="archive-form" method="POST" action="archiveNotePage.php">
                                                        <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                                                        <input type="hidden" name="noteID" value="<?php echo $row['noteID']; ?>">
                                                        <input type="submit" name="removeNote" value="Archive">
                                                    </form>
                                                <?php else: ?>
                                                    <form class="archive-form" method="POST" action="dearchiveNotePage.php">
                                                        <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                                                        <input type="hidden" name="noteID" value="<?php echo $row['noteID']; ?>">
                                                        <input type="submit" name="unremoveNote" value="Activate">
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                            <br>
                        <?php endif; ?>
                <?php endif; ?>
            </div>
            </div>
            <br>
        </div>


        <?php include('footer.php'); ?>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trainerDropdown = document.getElementById('trainer-dropdown');
            const selectedTrainerInput = document.getElementById('selected_trainer');

            function updateSelectedTrainer() {
                selectedTrainerInput.value = trainerDropdown.value;
            }

            trainerDropdown.addEventListener('change', updateSelectedTrainer);

            // Set the initial value
            updateSelectedTrainer();
        });

        document.getElementById("add-remove-button").addEventListener("click", function() {
            
            // Check user permissions and show popup if necessary
            if (!<?php echo isset($_SESSION['permissions']) && ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) ? 'true' : 'false'; ?>) {
                alert("You do not have permission to add a trainer to the horse.");
            }
            else
            {
                const trainerDropdown = document.getElementById("trainer-dropdown");
                const selectedTrainer = trainerDropdown.value;
                const horseId = "<?php echo $hp_horseID; ?>";

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "add_remove_trainer.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        location.reload(); // Reload the page to update the trainer list
                    }
                };
                xhr.send("horseID=" + horseId + "&username=" + selectedTrainer);

            }
        });

        // Get the archive and de-archive forms
        const archiveForm = document.getElementById("archive-form");
        const dearchiveForm = document.getElementById("dearchive-form");

        // Helper function to submit the form using Fetch API
        function submitForm(form) {
            form.addEventListener("submit", async (event) => {
                event.preventDefault();

                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData,
                });

                if (response.ok) {
                    location.reload();
                } else {
                    alert("Error: Unable to process the request.");
                }
            });
        }

        // Attach the submit event listener to both forms
        submitForm(archiveForm);
        submitForm(dearchiveForm);

    </script>



</body>
</html>
