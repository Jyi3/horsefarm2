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
    
    $sql_horse_list = 
        "SELECT h.horseID, h.horseName, IF(pt.username IS NULL, 0, 1) AS isConnected
        FROM horsedb h
        LEFT JOIN persontohorsedb pt ON pt.horseID = h.horseID AND pt.username = '$pp_username'
        WHERE h.archive != 1";

    $sql = "SELECT * FROM persondb WHERE username = '$pp_username'";
    $notes = "SELECT n.horseID, h.horseName, n.note, n.noteDate 
    FROM notesdb n
    INNER JOIN horsedb h ON n.horseID = h.horseID 
    WHERE n.username = '$pp_username'";
    $result = mysqli_query($conn, $sql);
    $result2 = mysqli_query($conn, $notes);
    $horseListResult = mysqli_query($conn, $sql_horse_list);

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
    $notes = "SELECT n.horseID, h.horseName, n.note, n.noteDate 
    FROM notesdb n
    INNER JOIN horsedb h ON n.horseID = h.horseID 
    WHERE n.username = '$pp_username'";
    $result = mysqli_query($conn, $sql);
    $result2 = mysqli_query($conn, $notes);
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
            margin: 3% auto 0 auto; /* 3% from the top, centered horizontally */
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
            padding-bottom: 3%;
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
                <div class="profile-container">
                    <div class="profile-details">
                        <p>Username : <?php echo $pp_username; ?></p>
                        <p>Fullname : <?php echo $firstName . " " . $lastName; ?> </p>
                        <p>Email : <?php echo $email; ?></p>
                        <p>Phone : <?php echo $phone; ?></p>
                        <p>User Type : <?php echo $userType; ?></p>
                        <div style="display: flex; align-items: center;">
                        <p style="margin: 0;">Status : <?php echo ($archive == 0 || $archive == NULL) ? 'Active' : 'Inactive'; ?></p>

                        <?php
                        if ($archive == NULL || $archive == 0) {
                            $buttonLabel = "Archive";
                        }
                        if ($archive == 1) {
                            $buttonLabel = "Activate";
                        }
                        ?>
                        
                        <?php if ($permission_level == 3): ?>
                            <form method="POST" class="archive-form" style="display: flex; align-items: center; margin-left: 10px;">
                            <input type="hidden" name="username" value="<?php echo $hp_horseID; ?>" />
                            <input type="submit" name="archive" value="Inactivate" <?php if ($archive == 1) echo 'style="display:none"'; ?> style="margin-left: 10px;" />
                            <input type="submit" name="activate" value="Activate" <?php if ($archive == 0 || $archive == NULL) echo 'style="display:none"'; ?> style="margin-left: 10px;" />
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="profile-name" style="justify-content: center; align-items: center; margin-top: 3%; ">
                        <h1><?php echo $firstName . " " . $lastName; ?>'s Profile</h1>
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

        <div class="notes-container">
            <h2 style="text-align: center;">Notes</h2>
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
        <?PHP include('footer.php'); ?>
    </div>

    <script>
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