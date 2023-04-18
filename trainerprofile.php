<?php
    include('session.php');
?>

<?php
    include('database/dbinfo.php');
    $conn = connect();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Use $_GET to get the username parameter
    $pp_username = $_GET["username"];
    
    // Fetch the count of active Head Trainers
    $sql = "SELECT COUNT(*) as count FROM persondb WHERE userType = 'Head Trainer' AND (archive = 0 OR archive IS NULL)";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $activeHeadTrainers = $row['count'];

    
    $sql_horse_list = 
        "SELECT h.horseID, h.horseName, IF(pt.username IS NULL, 0, 1) AS isConnected
        FROM horsedb h
        LEFT JOIN persontohorsedb pt ON pt.horseID = h.horseID AND pt.username = '$pp_username'
        WHERE h.archive != 1";

    // Query for associated horses
    $sql_associated_horses = 
        "SELECT h.horseID, h.horseName
        FROM horsedb h
        INNER JOIN persontohorsedb pt ON pt.horseID = h.horseID AND pt.username = '$pp_username' AND h.archive != 1";
    $associatedHorsesResult = mysqli_query($conn, $sql_associated_horses);

    
    include_once('database/dbinfo.php');

    $conn = connect();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Use $_GET to get the username parameter
    $pp_username = $_GET["username"];
    $sql = "SELECT * FROM persondb WHERE username = '$pp_username'";
    $notes = "SELECT n.horseID, h.horseName, n.note, n.noteDate, n.archive 
          FROM notesdb n
          INNER JOIN horsedb h ON n.horseID = h.horseID 
          WHERE n.username = '$pp_username' AND (n.archive = 0 OR n.archive IS NULL)";

    $archiveNotes = "SELECT n.horseID, h.horseName, n.note, n.noteDate, n.archive, n.archiveDate, p.firstName, p.lastName, p.username 
                    FROM notesdb n 
                    INNER JOIN horsedb h ON n.horseID = h.horseID 
                    INNER JOIN persondb p ON n.username = p.username 
                    WHERE n.username = '$pp_username' AND n.archive = 1";
    $result = mysqli_query($conn, $sql);
    $result2 = mysqli_query($conn, $notes);
    $result3 = mysqli_query($conn, $archiveNotes);
    $horseListResult = mysqli_query($conn, $sql_horse_list);


    if (mysqli_num_rows($result) != 1) {
        die("Error: Invalid username");
    }

    $row = mysqli_fetch_assoc($result);
    $firstName = $row["firstName"];
    $lastName = $row["lastName"];
    $pp_username = $row["username"];
    $email = $row["email"];
    $phone = $row["phone"];
    $userType = $row["userType"];
    $archive = $row["archive"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


        // Only users with permission level 3 can see or use the archive/activate buttons
        $permission_level = $_SESSION["permissions"];
        if ($permission_level < 3) {
            echo "Error: You do not have permission to perform this action.";
            exit();
        }

        

        if (isset($_POST["archive"])) {
            // Fetch userType for the specific user
            $sql = "SELECT userType FROM persondb WHERE username = '$pp_username'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $userType = $row['userType'];
        
            $sql = "UPDATE persondb SET archive = 1, archiveDate = CURRENT_DATE() WHERE username = '$pp_username'";
            $action_success = mysqli_query($conn, $sql);
            if (!$action_success) {

                echo "Error updating record: " . mysqli_error($conn);
            }
            mysqli_close($conn);
        } elseif (isset($_POST["activate"])) {
            $sql = "UPDATE persondb SET archive = 0 WHERE username = '$pp_username'";
            $action_success = mysqli_query($conn, $sql);
            if (!$action_success) {
                echo "Error updating record: " . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
        
        
        echo "<script>window.location.href = '" . $_SERVER["PHP_SELF"] . "?username=$pp_username';</script>";
        mysqli_close($conn);
        exit();
    }
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
        
        .horse-list-container {
            margin-top: 20px;
            margin-bottom: 20px;
            align-items: left;
            text-align: left;
            margin-right: auto;
        }

        .horse-list-form input[type="text"] {
            padding: 6px 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            align-items: left;
            text-align: left;
            margin-right: auto;
        }
        
    </style>
</head>
<body>
    <div id="container">
        <?PHP include('header.php'); ?>
            <title><?php echo $pp_username; ?>'s Profile</title>
            <div id="content">
                <h1 class="profile-name" style="justify-content: right; align-items: right; margin-top: 3%;">
                        <?php echo $firstName . " " . $lastName; ?>'s Profile
                    </h1>
                <div class="profile-container">
                    <div class="profile-details">
                        <p>Username : <?php echo $pp_username; ?></p>
                        <p>Fullname : <?php echo $firstName . " " . $lastName; ?> </p>
                        <p>Email : <?php echo $email; ?></p>
                        <p>Phone : <?php echo $phone; ?></p>
                        <p>User Type : <?php echo $userType; ?></p>
                        <div style="display: flex; align-items: center;">
                        <p style="margin: 0;">Status : <?php echo ($archive == 0 || $archive == NULL) ? 'Active' : 'Inactive'; ?></p>
                        <form method="POST" class="archive-form" style="display: flex; align-items: center; margin-left: 10px;" onsubmit="return validateForm(<?php echo $activeHeadTrainers; ?>, '<?php echo $userType; ?>');">
                            <input type="hidden" name="username" value="<?php echo $username; ?>" />
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
        </div>
        
        <!-- Add the new Horse List form container -->
        <div class="horse-list-container">
            <h2 style="text-align: left;">Horse List</h2>
            <div class="horse-list-container">
                <select id="horse-dropdown">
                    <option value="" selected disabled hidden>Select a horse</option>
                    <?php while ($row = mysqli_fetch_assoc($horseListResult)): ?>
                        <?php
                            $horseColor = ($row['isConnected'] == 1) ? 'green' : 'red';
                        ?>
                        <option value="<?php echo $row['horseID']; ?>" style="color: <?php echo $horseColor; ?>;">
                            <strong><?php echo $row['horseName']; ?></strong>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="hidden" id="selected_horse" name="horseID" value="" />
                <input type="submit" id="add-remove-button" name="add_remove_horse" value="Add/Remove" class="archive-form-button" />
            </div>
            <?php if (mysqli_num_rows($associatedHorsesResult) == 0): ?>
                <br>
                <p style="text-align: left;">No horses associated with <?php echo $firstName . ' ' . $lastName; ?></p>
            <?php else: ?>
                <ul>
                <?php while ($row = mysqli_fetch_assoc($associatedHorsesResult)): ?>
                    <li><a href='horseprofile.php?horseID=<?php echo $row['horseID']; ?>' style='color: blue;'><?php echo $row['horseName']; ?></a></li>
                <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div style="display: flex; justify-content: center; align-items: center;">
                <div id="content-inner" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <h2 style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">Notes
                    </h2>
                    <br>

                <?php if (mysqli_num_rows($result2) == 0): ?>
                    <p style="text-align: center;">No notes available by user</p>
                <?php else: ?>
                    <table class="notes-table">
                        <thead>
                            <tr>
                                <th class="horse-name">Horse Name</th>
                                <th>Note</th>
                                <th class="note-date">NoteDate</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result2)): ?>
                            <tr>
                                <td class="horse-name" ><a href='horseprofile.php?horseID=<?php echo $row['horseID']; ?>' style='color: blue;'><?php echo $row['horseName']; ?></a></td>
                                <td class="note-cell"><?php echo nl2br($row['note']); ?></td>
                                <td class="note-date"><?php echo $row['noteDate']; ?></td>
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
                    <h2 style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">Notes
                    </h2>
                    <br>

                <?php if (mysqli_num_rows($result3) == 0): ?>
                    <p style="text-align: center;">No notes available by user</p>
                <?php else: ?>
                    <table class="notes-table">
                        <thead>
                            <tr>
                                <th class="horse-name">Horse Name</th>
                                <th>Note</th>
                                <th class="note-date">NoteDate</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result3)): ?>
                            <tr>
                                <td class="horse-name" ><a href='horseprofile.php?horseID=<?php echo $row['horseID']; ?>' style='color: blue;'><?php echo $row['horseName']; ?></a></td>
                                <td class="note-cell"><?php echo nl2br($row['note']); ?></td>
                                
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
                <?php endif; ?>
            </div>
        </div>
        <br>

        </div>
        <?PHP include('footer.php'); ?>
    </div>

    <script>
        function validateForm(activeHeadTrainers, userType) {
            if (userType === 'Head Trainer' && activeHeadTrainers <= 1) {
                alert("There is only one active Head Trainer, please promote another head trainer and remove this head trainer.");
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const horseDropdown = document.getElementById('horse-dropdown');
            const selectedHorseInput = document.getElementById('selected_horse');

            function updateSelectedHorse() {
                selectedHorseInput.value = horseDropdown.value;
            }

            horseDropdown.addEventListener('change', updateSelectedHorse);

            // Set the initial value
            updateSelectedHorse();
        });

        document.getElementById("add-remove-button").addEventListener("click", function() {
            const horseDropdown = document.getElementById("horse-dropdown");
            const selectedHorse = horseDropdown.value;
            const username = "<?php echo $pp_username; ?>";

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "add_remove_horse.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    location.reload(); // Reload the page to update the horse list
                }
            };
            xhr.send("username=" + username + "&horseID=" + selectedHorse);
        });
    </script>

</body>
</html>