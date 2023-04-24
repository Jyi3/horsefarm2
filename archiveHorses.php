<?php
    include('session.php');
    
    // Check if the user has the necessary permissions
    if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] < 3) {
        die("You do not have permission to access this page.");
    }
?>

<?php
    include('database/dbinfo.php');
    include('domain/Horse.php');
    include('database/horsedb.php');
    // Check user permissions

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['horseID']) && isset($_POST['status'])) {
        $horseID = $_POST['horseID'];
        $status = $_POST['status'];
        $archive_date = ($status == 1) ? date('Y-m-d') : NULL;

        $query = "UPDATE horse SET archive = :status, archiveDate = :archive_date WHERE horseID = :horseID";
        $statement = $db->prepare($query);
        $statement->bindValue(':status', $status);
        $statement->bindValue(':archive_date', $archive_date);
        $statement->bindValue(':horseID', $horseID);
        $statement->execute();
        $statement->closeCursor();
    }
?>
<!DOCTYPE html>
<html>
<head>
        <title>
            CVHR Horse Training Management System
        </title>
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
                flex: 1 0 auto;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            #content-inner {
                text-align: center;
                max-width: 800px;
                width: 100%;
                min-height: 500px;
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
                text-align: center;
                padding: 8px;
            }
            th {
                background-color: #4b6c9e;
                color: white;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
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
            @media (max-width: 480px) {
                #container {
                    max-width: 100%;
                    padding: 10px;
                }
                #content-inner {
                    max-width: 90%;
                    min-height: auto;
                }
                h1 {
                    font-size: 24px;
                }
                p {
                    font-size: 16px;
                    line-height: 1.4;
                }
                .archive-form input[type="submit"], .archive-form-button {
                    font-size: 14px;
                    padding: 6px 12px;
                    margin-left: 10px;
                }
                th, td {
                    padding: 4px;
                    font-size: 14px;
                }
                .footer p {
                    font-size: 12px;
                }
            }

        </style> 

</head>
<body>
    <div id="container">
        <?php include('header.php'); ?>
        <div id="content">
            <div id="content-inner">
                <?php

                // Retrieve all horses from the database
                $allHorses = getall_horseDB();

                // Display all horses in a table with an Archive button for each
                echo "<br>";
                echo "<br>";
                echo "<h2><strong>List of Horses</strong></h2>";
                echo "<br>";
                if (empty($allHorses)) {
                    echo "<tr><td colspan='5' style='text-align:center'>There are no horses in this category.</td></tr>";
                } else {
                    echo "<table>";
                    echo "<tr>
                            <th style='text-align:center'>Name</th>
                            <th style='text-align:center'>Color</th>
                            <th style='text-align:center'>Breed</th>
                            <th style='text-align:center'>Pasture</th>
                            <th style='text-align:center'>Color Rank</th>
                            <th style='text-align:center'>Activate</th>
                        </tr>";
                    foreach ($allHorses as $horse) {
                        echo "<tr>
                                <td style='border-left: 1px solid black'><a href='horseprofile.php?horseID=" . $horse->get_horseID() . "' style='color: blue;'>" . $horse->get_horseName() . "</a></td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_color() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_breed() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_pastureNum() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_colorRank() . " </td>
                                <td style='border-left: 1px solid black'> <button class='archive-form-button' onclick=\"archiveHorse('" . $horse->get_horseID() . "')\">Archive</button> </td>

                            </tr>";
                    }
                    echo "</table>";
                }
                

                echo "<br>";

                // Retrieve all horses from the database
                $allHorses = getallInactive_horses();

                // Display all inactive horses in a table with an Activate button for each
                echo "<br>";
                echo "<br>";
                echo "<h2><strong>List of Archived Horses</strong></h2>";
                echo "<br>";
                if (empty($allHorses)) {
                    echo "<tr><td colspan='5' style='text-align:center'>There are no horses in this category.</td></tr>";
                    echo "<br>";
                    echo "<br>";
                } else {
                    echo "<table>";
                    echo "<tr>
                            <th style='text-align:center'>Name</th>
                            <th style='text-align:center'>Color</th>
                            <th style='text-align:center'>Breed</th>
                            <th style='text-align:center'>Pasture</th>
                            <th style='text-align:center'>Color Rank</th>
                            <th style='text-align:center'>Activate</th>
                        </tr>";
                    foreach ($allHorses as $horse) {
                        echo "<tr>
                                <td style='border-left: 1px solid black'><a href='horseprofile.php?horseID=" . $horse->get_horseID() . "' style='color: blue; '>" . $horse->get_horseName() . "</a></td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_color() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_breed() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_pastureNum() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_colorRank() . " </td>
                                <td style='border-left: 1px solid black'> <button class='archive-form-button' onclick=\"activateHorse(" . $horse->get_horseID() . ")\">Activate</button> </td>

                            </tr>";
                    }
                    echo "</table>";
                    echo "<br>";
                    echo "<br>";
                }
                ?>
                
            </div>
            </div>
            <?php include('footer.php'); ?>
        </div>
        <script>
        // Check user permissions and show popup if necessary
        if (!<?php echo isset($_SESSION['permissions']) && ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) ? 'true' : 'false'; ?>) {
            alert("You do not have permission to archive/activate a horse.");
        }
        function archiveHorse(horseID) {
            // Check user permissions and show popup if necessary
            if (!<?php echo isset($_SESSION['permissions']) && ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) ? 'true' : 'false'; ?>) {
                alert("You do not have permission to archive a horse.");
            }
            else
            {
                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        location.reload(); // Reload the page after updating the horse status
                    }
                };
                xhttp.open("POST", "update_horse_status.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("horseID=" + horseID + "&status=1"); // Pass 1 as the status to archive the horse
            }
        }

        function activateHorse(horseID) {
            // Check user permissions and show popup if necessary
            if (!<?php echo isset($_SESSION['permissions']) && ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) ? 'true' : 'false'; ?>) {
                alert("You do not have permission to activate a horse.");
            }
            else
            {
                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        location.reload(); // Reload the page after updating the horse status
                    }
                };
                xhttp.open("POST", "update_horse_status.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("horseID=" + horseID + "&status=0"); // Pass 0 as the status to activate the horse
            }
        }
        </script>
    </body>
</html>
