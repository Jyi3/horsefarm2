<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hp_horseID = $_POST["username"];

    include_once('database/dbinfo.php');

    $conn = connect();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (isset($_POST["archive"])) {
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
?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .person-name {
            width: 10%; /* 1/3rd of the width */
        }

        .note-date {
            width: 20%; /* half of the width */
        }

        .note-cell {
            max-height: 50vh;
            overflow-y: auto;
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

        a {
            color: #4b6c9e;
            text-decoration: underline;
        }

        a:visited {
            color: #4b6c9e;
        }

        @media (max-width: 768px) {
            .profile-container {
                flex-direction: column;
                align-items: center;
            }

            .profile-pic {
                margin-bottom: 20px;
            }

            .profile-name {
                margin: 0;
                text-align: center;
            }

            .profile-details {
                margin-top: 20px;
                align-items: center;
                text-align: center;
            }

            .notes-container {
                padding-bottom: 50px;
            }

            .notes-table th {
                font-size: 14px;
            }

            .notes-table td {
                font-size: 14px;
            }

            h1 {
                font-size: 28px;
                margin-bottom: 10px;
            }

            p {
                font-size: 16px;
            }
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
            $hp_horseID = $_GET["horseID"];
            $sql = "SELECT * FROM horsedb WHERE horseID = '$hp_horseID'";
            $notes = "SELECT n.horseID, t.firstName, t.lastName, n.note, n.noteDate 
                    FROM notesdb n
                    INNER JOIN horsedb h ON n.horseID = h.horseID 
                    INNER JOIN persondb t ON n.username = t.username
                    WHERE n.horseID = '$hp_horseID'";
            $result = mysqli_query($conn, $sql);
            $result2 = mysqli_query($conn, $notes);

            if (mysqli_num_rows($result) != 1) {
                die("Error: Invalid username");
            }

            $row = mysqli_fetch_assoc($result);
            $horseName = $row["horseName"];
            $hp_horseID = $row["horseID"];
            $color = $row["color"];
            $breed = $row["breed"];
            $pastureNum = $row["pastureNum"];
            $colorRank = $row["colorRank"];
            $archive = $row["archive"];
            $archiveDate = $row["archiveDate"];
            mysqli_close($conn);
            ?>
            <title><?php echo $horseName; ?>'s Profile</title>


        <div id="content">
            <div class="profile-container">
                <div class="profile-pic">
                    <img src="images/cvhrIMG.png" alt="Profile Picture">
                </div>
                <div class="profile-name">
                    <h1><?php echo $horseName; ?>'s Profile</h1>
                </div>
                <div class="profile-details">
                    <p><?php echo $horseName; ?> : Horse Name</p>
                    <p><?php echo $colorRank; ?> : Difficulty</p>
                    <p><?php echo $pastureNum; ?> : Pasture</p>
                    <p><?php echo $breed; ?> : Breed</p>
                    <p><?php echo $color; ?> : Color</p>
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
                        <input type="hidden" name="username" value="<?php echo $hp_horseID; ?>" />
                        <input type="submit" name="archive" value="Inactivate" <?php if ($archive == 1) echo 'style="display:none"'; ?> />
                        <input type="submit" name="activate" value="Activate" <?php if ($archive == 0 || $archive == NULL) echo 'style="display:none"'; ?> />
                    </form>

                                        
                    </div>
            </div>
            <div class="notes-container">
                <h2 style="text-align: center;">Notes</h2>
                <?php if (mysqli_num_rows($result2) == 0): ?>
                    <p style="text-align: center;">No notes available for horse</p>
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
                        <?php while ($row = mysqli_fetch_assoc($result2)): ?>
                            <tr>
                                <td class="person-name"><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></td>
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
</body>
</html>