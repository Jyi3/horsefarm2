<?php
    include('session.php');
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
        WHERE n.horseID = '$hp_horseID' AND (n.archive = 0 OR n.archive IS NULL)";

    $arNotes = "SELECT n.horseID, n.noteID, t.firstName, t.lastName, n.note, n.noteDate, t.username, n.archive, n.archiveDate, h.diet
        FROM notesdb n
        INNER JOIN horsedb h ON n.horseID = h.horseID 
        INNER JOIN persondb t ON n.username = t.username
        WHERE n.horseID = '$hp_horseID' AND n.archive = 1";



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
	 $behaviorsql = "SELECT title FROM horsetobehaviordb WHERE horseID = '$hp_horseID'";
	 $behave = mysqli_query($conn, $behaviorsql);
	 $behaviorlist = mysqli_fetch_array($behave);
    

    // Query for associated horses
    $sql_associated_trainer = 
        "SELECT p.username, p.firstName, p.lastName, p.archive
        FROM personDB p
        INNER JOIN personToHorseDB ph ON ph.username = p.username
        WHERE ph.horseID = '$hp_horseID' AND p.archive != 1";
    $associatedTrainerResult = mysqli_query($conn, $sql_associated_trainer);

    if (mysqli_num_rows($result) != 1) {
        die("Error: Invalid username");
    }
    



    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $hp_horseID = $_POST["horseID"];

        include_once('database/dbinfo.php');

        $conn = connect();
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        if (!isset($_SESSION['permissions']) || ($_SESSION['permissions'] != 3 && $_SESSION['permissions'] != 5)) {
            echo '<script>alert("You do not have permission to archive or activate a  horse.");</script>';
        }
        else if (isset($_POST["archive"])) {
            $sql = "UPDATE horsedb SET archive = 1, archiveDate = CURRENT_DATE() WHERE horseID = '$hp_horseID'";
            $action_success = mysqli_query($conn, $sql);
            if (!$action_success) {
                echo "Error updating record: " . mysqli_error($conn);
            }
            mysqli_close($conn);
        } elseif (isset($_POST["activate"])) {  
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
            text-align: center;
            margin-top: 3vh;
            align-items: center;
        }

        .profile-details {
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
        
        @media (max-width: 768px) {
            #container {
                padding: 10px;
            }
            h1 {
                font-size: 24px;
            }
            p {
                font-size: 16px;
            }
            table {
                font-size: 14px;
            }
            .profile-container {
                flex-direction: column;
                align-items: center;
            }
            .profile-details {
                padding-top: 0;
                text-align: center;
            }
            .trainer-list-container {
                align-items: center;
                text-align: center;
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
                <div class="profile-details">
                    <p>Horse Name : <?php echo $horseName; ?></p>
                    <p>Difficulty : <?php echo $colorRank; ?> </p>
                    <p>Diet: <?php echo $diet; ?></p>
                    <p>Pasture : <?php echo $pastureNum; ?></p>
                    <p>Breed : <?php echo $breed; ?></p>
                    <p>Color : <?php echo $color; ?></p>
                    <div style="display: flex; align-items: center;">
                        <p style="margin: 0;">Status : <?php echo ($archive == 0 || $archive == NULL) ? 'Active' : 'Inactive'; ?></p>
                        <form method="POST" class="archive-form" style="display: flex; align-items: center; margin-left: 10px;">
                            <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>" />
                            <input type="submit" name="archive" value="Inactivate" <?php if ($archive == 1) echo 'style="display:none"'; ?> style="margin-left: 10px;" />
                            <input type="submit" name="activate" value="Activate" <?php if ($archive == 0 || $archive == NULL) echo 'style="display:none"'; ?> style="margin-left: 10px;" />
                        </form>
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
                <h1><?php echo $horseName; ?>'s Profile</h1>
                <form method="POST" action="editHorse.php">
                    <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                    <button type="submit" class="archive-form-button">Edit Horse Profile</button>
                </form>
                </div>

                <div class="profile-behaviors">
                    <ul>
                    <h2>
                    <input type="submit" name="edit_behaviors" value="Edit Behaviors" class="archive-form-button" /> Behaviors</h2>
                    <?php
                        // Your code for retrieving behaviors here
                       
                        foreach ($behave as $be) {
     								
          							echo "<li>" . $be['title'] . "</li>";
     								
								}
                    ?>
                    </ul>
                </div>
                
            </div>
            
            <!-- Add the new Trainer List form container -->
            <div class="trainer-list-container;">
                <h2 style="text-align: left;">Trainer List</h2>
                <div class="trainer-list-container">
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
                        <form method="POST" class="archive-form" action="addNotePage.php">
                            <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                            <input type="submit" name="add_note" value="Add Note" class="archive-form-button" />
                        </form>
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
                                        <td class="person-name">
                                            <a href='trainerprofile.php?username=<?php echo $row['username']; ?>' style='color: blue;'><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></a>
                                        </td>
                                        <td class="note-cell"><?php echo nl2br($row['note']); ?></td>
                                        <td>
                                            <form method="POST" class="archive-form" action="editNotePage.php">
                                                <input type="hidden" name="horseID" value="<?php echo $hp_horseID; ?>">
                                                <input type="hidden" name="noteID" value="<?php echo $row['noteID']; ?>">
                                                <input type="submit" name="editNote" value="<?php echo $row['noteDate']; ?>">
                                            </form>
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
