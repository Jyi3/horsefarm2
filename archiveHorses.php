<?php
    include('session.php');
?>

<?php
    include('database/dbinfo.php');
    include('domain/Horse.php');
    include('database/horsedb.php');
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
        </style> 

</head>
<body>
    <script>
        function archiveHorse(horseID) {
        if (confirm("Are you sure you want to archive this horse?")) {
            archive_horse(horseID);
        }
        }
        function activateHorse(horseID) {
            if (confirm("Are you sure you want to unarchive this horse?")) {
            }
        }
    </script>
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
                            <th>Name</th>
                            <th>Color</th>
                            <th>Breed</th>
                            <th>Pasture</th>
                            <th>Color Rank</th>
                            <th>Archive</th>
                        </tr>";
                    foreach ($allHorses as $horse) {
                        echo "<tr>
                                <td style='border-left: 1px solid black'><a href='horseprofile.php?horseID=" . $horse->get_horseID() . "' style='color: blue;'>" . $horse->get_horseName() . "</a></td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_color() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_breed() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_pastureNum() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_colorRank() . " </td>
                                <td style='border-left: 1px solid black'> <button onclick=\"archiveHorse('" . $horse->get_horseID() . "')\">Archive</button> </td>

                            </tr>";
                    }
                    echo "</table>";
                }
                

                echo "<br><br>";

                // Retrieve all horses from the database
                $allHorses = getallInactive_horses();

                // Display all inactive horses in a table with an Activate button for each
                echo "<br>";
                echo "<br>";
                echo "<h2><strong>List of Archived Horses</strong></h2>";
                echo "<br>";
                if (empty($allHorses)) {
                    echo "<tr><td colspan='5' style='text-align:center'>There are no horses in this category.</td></tr>";
                } else {
                    echo "<table>";
                    echo "<tr>
                            <th>Name</th>
                            <th>Color</th>
                            <th>Breed</th>
                            <th>Pasture</th>
                            <th>Color Rank</th>
                            <th>Activate</th>
                        </tr>";
                    foreach ($allHorses as $horse) {
                        echo "<tr>
                                <td style='border-left: 1px solid black'><a href='horseprofile.php?horseID=" . $horse->get_horseID() . "' style='color: blue;'>" . $horse->get_horseName() . "</a></td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_color() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_breed() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_pastureNum() . " </td>
                                <td style='border-left: 1px solid black'>  " . $horse->get_colorRank() . " </td>
                                <td style='border-left: 1px solid black'> <button onclick=\"activateHorse(" . $horse->get_horseID() . ")\">Activate</button> </td>
                            </tr>";
                    }
                    echo "</table>";
                }
                ?>
                
            </div>
            </div>
            <?php include('footer.php'); ?>
        </div>
    </body>
</html>