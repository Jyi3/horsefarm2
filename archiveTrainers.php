<?php
    include('session.php');
    include_once('database/persondb.php');
    include_once('domain/Person.php');
    // Check if the user has the necessary permissions (permissions level 3)
    if ($_SESSION['permissions'] < 3) {
        header("Location: index.php");
        exit;
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
    <div id="container">
    <?php include('header.php'); ?>
    <div id="content">
        <div id="content-inner">
            <?php
                include_once('domain/Person.php');
                include_once('database/dbinfo.php');
                include_once('database/persondb.php');
                $allPersons = getall_persondb();
                
                echo "<hr style='clear:both;'>";
                echo "<h2><strong>List of Active People</strong></h2>";
                echo "<br>";
                if (empty($allPersons)) {
                    echo "<tr><td colspan='5' style='text-align:center'>There are no trainers in this category.</td></tr>";
                } else 
                {
                    echo "<table style='float: left; margin-right: 20px;'>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Archive</th>
                            </tr>";
                    
                    for ($x = 0; $x < count($allPersons); $x++) {
                        $username = $allPersons[$x]->get_username();
                        echo "<tr>
                                <td style='border-left: 1px solid black'><a href='trainerprofile.php?username=$username' style='color: blue;'>" . $allPersons[$x]->get_firstName() . "</a></td>
                                <td style='border-left: 1px solid black'><a href='trainerprofile.php?username=$username' style='color: blue;'>" . $allPersons[$x]->get_lastName() . "</a></td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_phone() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_email() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_userType() . " </td>
                                <td style='border-left: 1px solid black'> 
                                    <button onclick=\"confirmArchive('" . $username . "')\">Archive</button> 
                                </td>
                            </tr>";
                    }
                    echo "</table>";
                }

                
                
                // Second table
                $allPersons = getinactive_persondb();
                echo "<hr style='clear:both;'>";
                echo "<hr style='clear:both;'>";
                echo "<h2><strong>List of Inactived People</strong></h2>";
                echo "<br>"; 
                if (empty($allPersons)) {
                    echo "<tr><td colspan='5' style='text-align:center'>There are no trainers in this category.</td></tr>";
                } else 
                {
                    echo "<table style='float: right;'>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Activate</th>
                            </tr>";
                    
                    for ($x = 0; $x < count($allPersons); $x++) {
                        $username = $allPersons[$x]->get_username();
                        echo "<tr>
                                <td style='border-left: 1px solid black'><a href='trainerprofile.php?username=$username' style='color: blue;'>" . $allPersons[$x]->get_firstName() . "</a></td>
                                <td style='border-left: 1px solid black'><a href='trainerprofile.php?username=$username' style='color: blue;'>" . $allPersons[$x]->get_lastName() . "</a></td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_phone() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_email() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_userType() . " </td>
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
