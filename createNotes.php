<?php
    include('session.php');

    // Check if the user has the necessary permissions (permissions level 2)
    if ($_SESSION['permissions'] < 2) {
        header("Location: index.php");
        exit;
    }

    // Include the database connection file
    include_once('database/dbinfo.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the horseID, username, and note from the POST data
        $horseID = $_POST["horseID"];
        $username = $_POST["username"];
        $note = $_POST["note"];
        $noteDate = date("Y-m-d");
        $noteTimestamp = date("Y-m-d H:i:s");
        $noteID = 1;

        // Get the last noteID for this horseID
        $conn = connect();
        $query = "SELECT MAX(noteID) as maxNoteID FROM notesDB WHERE horseID = $horseID";
        $result = mysqli_query($conn, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            $noteID = $row['maxNoteID'] + 1;
        }

        // Insert the new note into the database
        $query = "INSERT INTO notesDB (horseID, noteID, noteDate, noteTimestamp, note, username, archive, archiveDate) VALUES ($horseID, $noteID, '$noteDate', '$noteTimestamp', '$note', '$username', NULL, NULL)";
        if (mysqli_query($conn, $query)) {
            header("Location: createNotes.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
            exit;
        }

        // Close the database connection
        mysqli_close($conn);
    }

    // Get all horses whose archive == 0 or NULL
    $conn = connect();
    $query = "SELECT * FROM horseDB WHERE archive IS NULL OR archive = 0";
    $result = mysqli_query($conn, $query);

    // Initialize an array to hold the horse options
    $horse_options = array();

    // Add each horse as an option to the array
    while ($row = mysqli_fetch_assoc($result)) {
        $horseID = $row['horseID'];
        $horse_name = $row['horseName'];
        $horse_options[$horseID] = $horse_name;
    }

    // Close the database connection
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Note | CVHR Horse Training Management System</title>
        <script src="script.js"></script>
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
                margin: 0 auto;
            }

            h1 {
                color: #4b6c9e;
                font-size: 36px;
                margin-bottom: 20px;
                text-align: center;
            }


            label {
                display: block;
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            input[type="text"], textarea {
                width: 100%;
                padding: 8px;
                border-radius: 5px;
                border: 1px solid #cccccc;
                margin-bottom: 20px;
                box-sizing: border-box;
            }

            textarea {
                height: 150px;
            }

            input[type="submit"] {
                background-color: #4b6c9e;
                color: #ffffff;
                padding: 8px 16px;
                border: none;
                border-radius: 5px;
                font-weight: bold;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: #374c6f;
            }

            .error {
                color: #ff0000;
                font-weight: bold;
            }

            @media (max-width: 768px) {
                h1 {
                    font-size: 28px;
                }

                form {
                    max-width: 100%;
                }
            }
        </style>
    </head>
    <body>
    <div id="container">
        <?php include('header.php'); ?>
        <div id="content">
            <div id="content-inner">
                <h1>Create Note</h1>
                <br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="max-width: 500px; width: 90%; padding: 20px; background-color: #ffffff; border: 1px solid #cccccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin: 0 auto;">
                    <label for="horseID">Horse:</label>
                    <select name="horseID" id="horseID">
                        <?php foreach ($horse_options as $id => $name) : ?>
                            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <label for="note">Note:</label>
                    <textarea name="note" id="note" rows="10" cols="50"></textarea>
                    <br>
                    <br>
                    <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                    <input type="submit" value="Submit Note">
                </form>
                <br>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
</body>
</html>
