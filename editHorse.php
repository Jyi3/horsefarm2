<?php
    include('session.php');
    include_once('database/dbinfo.php');
    include_once('database/horsedb.php');
    include_once('domain/Horse.php');

    // Check if the user has the necessary permissions (permissions level 2)
    if ($_SESSION['permissions'] < 2) {
        header("Location: index.php");
        exit;
    }

    $horses = getAll_horseDB();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_horse"])) {
        $horseID = $_POST["horseID"];
        $horseName = $_POST["horseName"];
        $diet = $_POST["diet"];
        $color = $_POST["color"];
        $breed = $_POST["breed"];
        $colorRank = $_POST["colorRank"];
        $pastureNum = $_POST["pastureNum"];
        $archive = $_POST["archive"];
        $archiveDate = $_POST["archiveDate"];
        $status = $_POST["status"];

        // update Horse data in the database
        $conn = connect();
        $sql = "UPDATE horseDB SET horseName='$horseName', diet='$diet', color='$color', breed='$breed', pastureNum='$pastureNum', colorRank='$colorRank', archive='$status' WHERE horseID='$horseID'";

        // execute SQL query
        if (mysqli_query($conn, $sql)) {
            header("Location: editHorse.php");
        } else {
            echo "<p>Error updating horse: " . mysqli_error($conn) . "</p>";
        }

        // close database connection
        mysqli_close($conn);
    } else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["horseID"])) {
        $horseID = $_GET["horseID"];
        
        $horseName = $_POST["horseName"];
        $diet = $_POST["diet"];
        $color = $_POST["color"];
        $breed = $_POST["breed"];
        $colorRank = $_POST["colorRank"];
        $pastureNum = $_POST["pastureNum"];
        $archive = $_POST["archive"];
        $archiveDate = $_POST["archiveDate"];
        $status = $_POST["status"];

        // update Horse data in the database
        $conn = connect();
        $sql = "UPDATE horseDB SET horseName='$horseName', diet='$diet', color='$color', breed='$breed', pastureNum='$pastureNum', colorRank='$colorRank', archive='$status' WHERE horseID='$horseID'";

        // execute SQL query
        if (mysqli_query($conn, $sql)) {
            header("Location: editHorse.php");
        } else {
            echo "<p>Error updating horse: " . mysqli_error($conn) . "</p>";
        }

        // close database connection
        mysqli_close($conn);
    } else {
        $horseID = "";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Horse | CVHR Horse Training Management System</title>
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
                margin: 0 auto;
            }

            p {
                font-size: 18px;
                line-height: 1.6;
                margin: 0 auto;
            }

            label {
                display: inline-block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            input[type="text"], input[type="email"], input[type="password"], select {
                width: 100%;
                padding: 8px;
                border-radius: 5px;
                border: 1px solid #cccccc;
                margin-bottom: 20px;
                box-sizing: border-box;
            }

            input[type="submit"], button.btn-default {
                background-color: #4b6c9e;
                color: #ffffff;
                padding: 8px 16px;
                border: none;
                border-radius: 5px;
                font-weight: bold;
                cursor: pointer;
            }

            input[type="submit"]:hover, button.btn-default:hover {
                background-color: #374c6f;
            }

            .error {
                color: #ff0000;
                font-weight: bold;
            }

            .success {
                color: #008000;
                font-weight: bold;
            }

            .archive {
                color: #ff0000;
                font-weight: bold;
            }

            @media (max-width: 768px) {
                h1 {
                    font-size: 28px;
                }

                p {
                    font-size: 16px;
                    max-width: 90%;
                }

                #container {
                    padding: 10px;
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
                <h1>Edit Horse</h1>
                <br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="max-width: 500px; width: 90%; padding: 20px; background-color: #ffffff; border: 1px solid #cccccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin: 0 auto;">
                    <div class="form-group">
                        <label for="horse">Select Horse:</label>
                        <select name="horseID" class="form-control" onchange="this.form.submit()">
                            <option value="">--Select Horse--</option>
                            <?php foreach ($horses as $horse) {
                                $selected = "";
                                if (isset($_POST["horseID"]) && $_POST["horseID"] == $horse->get_horseID()) {
                                    $selected = "selected";
                                }
                                echo "<option value=\"" . $horse->get_horseID() . "\" $selected>" . $horse->get_horseName() . "</option>";
                            } ?>
                        </select>
                    </div>
                </form>
                <?php
                if (isset($_REQUEST['horseID']) && $_REQUEST['horseID'] != "") {
                    $horseID = $_REQUEST['horseID'];
                    $horse = retrieve_horse_by_id($horseID); ?>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="max-width: 500px; width: 90%; padding: 20px; background-color: #ffffff; border: 1px solid #cccccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin: 0 auto;">
                        <div class="form-group">
                            <label for="horseName">Horse Name:</label>
                            <input type="text" name="horseName" class="form-control" value="<?php echo $horse->get_horseName(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="diet">Diet:</label>
                            <input type="text" name="diet" class="form-control" value="<?php echo $horse->get_diet(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="color">Color:</label>
                            <input type="text" name="color" class="form-control" value="<?php echo $horse->get_color(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="breed">Breed:</label>
                            <input type="text" name="breed" class="form-control" value="<?php echo $horse->get_breed(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="pastureNum">Pasture Number:</label>
                            <input type="number" name="pastureNum" class="form-control" value="<?php echo $horse->get_pastureNum(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="colorRank">Color Rank:</label>
                            <select name="colorRank" class="form-control">
                                <option value="Green" <?php if ($horse->get_colorRank() == "Green") echo "selected"; ?>>Green</option>
                                <option value="Yellow" <?php if ($horse->get_colorRank() == "Yellow") echo "selected"; ?>>Yellow</option>
                                <option value="Red" <?php if ($horse->get_colorRank() == "Red") echo "selected"; ?>>Red</option>
                            </select>
                        </div>
                        <?php if ($_SESSION['permissions'] >= 3) { ?>
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php if ($horse->get_archive() == 1) echo "selected"; ?>>Archive</option>
                                    <option value="0" <?php if ($horse->get_archive() == 0) echo "selected"; ?>>Active</option>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="status" value="<?php echo $horse->get_archive(); ?>">
                        <?php } ?>
                        <input type="hidden" name="horseID" value="<?php echo $horse->get_horseID(); ?>">
                        <button type="submit" name="update_horse" class="btn btn-default">Update Horse</button>
                    </form>
                <?php } ?>
            </div>
            <br>
            </div>
            <?PHP include('footer.php'); ?>
        </div>
    </body>

</html>