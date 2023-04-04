<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pp_username = $_POST["username"];

    include_once('database/dbinfo.php');

    $conn = connect();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (isset($_POST["archive"])) {
        $sql = "UPDATE persondb SET archive = 1, archiveDate = CURRENT_DATE() WHERE username = '$pp_username'";
        $action_success = mysqli_query($conn, $sql);
        if (!$action_success) {
            echo "Error updating record: " . mysqli_error($conn);
        }
        mysqli_close($conn);
    } elseif (isset($_POST["activate"])) {  
        $conn = mysqli_connect($servername, $db_username, $password, $dbname);
        $sql = "UPDATE persondb SET archive = 0 WHERE username = '$pp_username'";
        $action_success = mysqli_query($conn, $sql);
        if (!$action_success) {
            echo "Error updating record: " . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
    
    echo "<script>window.location.href = '" . $_SERVER["PHP_SELF"] . "?userName=$pp_username';</script>";
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css" type="text/css" />
    <style>
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
            align-items: center;
            text-align: center;
        }
        
        .profile-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            width: 80%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .profile-details {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            font-weight: bold;
        }

        .profile-details p {
            margin: 5px 0;
        }

        .notes-container {
            width: 50%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding-bottom: 100px; /* Add a padding-bottom of 50 pixels */

        }

        .notes-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .notes-table th {
            background-color: #f2f2f2;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .notes-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .horse-name {
            width: 10%; /* 1/3rd of the width */
        }

        .note-date {
            width: 20%; /* half of the width */
        }

        .note-cell {
            max-height: 50vh;
            overflow-y: auto;
        }


    </style>

</head>
<body>
    <div id="container">
            <?PHP include('header.php'); ?>
        
            <?php
            include_once('database/dbinfo.php');

            $conn = connect();
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Use $_GET to get the userName parameter
            $pp_username = $_GET["userName"];
            $sql = "SELECT * FROM persondb WHERE username = '$pp_username'";
            $notes = "SELECT n.horseID, h.horseName, n.note, n.noteDate 
            FROM notesdb n
            INNER JOIN horsedb h ON n.horseID = h.horseID 
            WHERE n.username = '$pp_username'";
            $result = mysqli_query($conn, $sql);
            $result2 = mysqli_query($conn, $notes);

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
            mysqli_close($conn);
            ?>
            <title><?php echo $pp_username; ?>'s Profile</title>


        <div id="content">
            <div class="profile-container">
                <div class="profile-pic">
                    <img src="images/cvhrIMG.png" alt="Profile Picture">
                </div>
                <div class="profile-name">
                    <h1><?php echo $firstName . " " . $lastName; ?>'s Profile</h1>
                </div>
                <div class="profile-details">
                    <p><?php echo $pp_username; ?> : Username</p>
                    <p><?php echo $firstName . " " . $lastName; ?> : Fullname</p>
                    <p><?php echo $email; ?> : Email</p>
                    <p><?php echo $phone; ?> : Phone</p>
                    <p><?php echo $userType; ?> : User Type</p>
                    <p><?php echo ($archive == 0 || $archive == NULL) ? 'Active' : 'Inactive'; ?> : Status</p>

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
                    
                    <form method="POST">
                        <input type="hidden" name="username" value="<?php echo $pp_username; ?>" />
                        <input type="submit" name="archive" value="Inactivate" <?php if ($archive == 1) echo 'style="display:none"'; ?> />
                        <input type="submit" name="activate" value="Activate" <?php if ($archive == 0 || $archive == NULL) echo 'style="display:none"'; ?> />
                    </form>

                                        
                </div>
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
                                <td class="horse-name"><?php echo $row['horseName']; ?></td>
                                <td class="note-cell"><?php echo nl2br($row['note']); ?></td>
                                <td class="note-date"><?php echo $row['noteDate']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                    </table>
                <?php endif; ?>
            </div>
        </div>
        <?PHP //include('footer.inc'); ?>
    </div>
</body>
</html>
