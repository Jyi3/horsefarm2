<?php
    include('session.php');
    include_once('database/persondb.php');
    include_once('domain/Person.php');
    // Check if the user has the necessary permissions
    if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] < 3) {
        echo "<script>
                alert('You do not have permission to access this page.');
                window.location.href = 'index.php';
            </script>";
    }

?>

<?php
    include_once('database/persondb.php');
    include_once('database/dbinfo.php');

    $conn = connect();
    $query = "SELECT COUNT(*) as count FROM persondb WHERE userType = 'Head Trainer' AND (archive = 0 OR archive IS NULL)";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Error executing query: ' . mysqli_error($conn));
    }

    $activeHeadTrainers = mysqli_fetch_assoc($result);
    mysqli_close($conn);
?>


<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['status'])) {
        $username = $_POST['username'];
        $status = $_POST['status'];
        $archive_date = ($status == 1) ? date('Y-m-d') : NULL;

        $query = "UPDATE persondb SET archive = :status, archiveDate = :archive_date WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(':status', $status);
        $statement->bindValue(':archive_date', $archive_date);
        $statement->bindValue(':username', $username);
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
            h1 {
                color: #4b6c9e;
                font-size: 36px;
                margin-bottom: 20px;
                text-align: center;
                margin: 0 auto;
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
                table {
                    font-size: 14px;
                }
                th, td {
                    padding: 4px;
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
                                <th style='text-align: center'>First Name</th>
                                <th style='text-align: center'>Last Name</th>
                                <th style='text-align: center'>Phone</th>
                                <th style='text-align: center'>Email</th>
                                <th style='text-align: center'>Role</th>
                                <th style='text-align: center'>Archive</th>
                            </tr>";
                    
                    for ($x = 0; $x < count($allPersons); $x++) {
                        $username = $allPersons[$x]->get_username();
                        echo "<tr>
                                <td style='border-left: 1px solid black'><a href='trainerprofile.php?username=$username' style='color: blue;'>" . $allPersons[$x]->get_firstName() . "</a></td>
                                <td style='border-left: 1px solid black'><a href='trainerprofile.php?username=$username' style='color: blue;'>" . $allPersons[$x]->get_lastName() . "</a></td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_phone() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_email() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_userType() . " </td>
                                <td style='border-left: 1px solid black'><button class='archive-form-button' onclick=\"archivePerson('" . $allPersons[$x]->get_username() . "', '" . $allPersons[$x]->get_userType() . "')\">Archive</button></td>
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
                                <th style='text-align: center'>First Name</th>
                                <th style='text-align: center'>Last Name</th>
                                <th style='text-align: center'>Phone</th>
                                <th style='text-align: center'>Email</th>
                                <th style='text-align: center'>Role</th>
                                <th style='text-align: center'>Activate</th>
                            </tr>";
                    
                    for ($x = 0; $x < count($allPersons); $x++) {
                        $username = $allPersons[$x]->get_username();
                        echo "<tr>
                                <td style='border-left: 1px solid black'><a href='trainerprofile.php?username=$username' style='color: blue;'>" . $allPersons[$x]->get_firstName() . "</a></td>
                                <td style='border-left: 1px solid black'><a href='trainerprofile.php?username=$username' style='color: blue;'>" . $allPersons[$x]->get_lastName() . "</a></td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_phone() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_email() . " </td>
                                <td style='border-left: 1px solid black'> " . $allPersons[$x]->get_userType() . " </td>
                                <td style='border-left: 1px solid black'><button class='archive-form-button' onclick=\"archivePerson('" . $allPersons[$x]->get_username() . "', '" . $allPersons[$x]->get_userType() . "')\">Activate</button></td>

                            </tr>";
                    }
                    echo "</table>";
                }
                
                ?>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <script>
        
        // Check user permissions and show popup if necessary
        if (!<?php echo isset($_SESSION['permissions']) && ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) ? 'true' : 'false'; ?>) {
            alert("You do not have permission to archive/activate a trainer.");
        }
        const activeHeadTrainers = <?php echo $activeHeadTrainers['count']; ?>;

        function archivePerson(username, userType) {
            // If the person is a Head Trainer, check the number of active Head Trainers
            // Check user permissions and show popup if necessary
            if (!<?php echo isset($_SESSION['permissions']) && ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) ? 'true' : 'false'; ?>) {
                alert("You do not have permission to archive a trainer.");
            }
            else
            {
                if (userType == 'Head Trainer' && activeHeadTrainers <= 1) {
                alert("There is only one active Head Trainer, please promote another head trainer and remove this head trainer.");
                } else {
                    const xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            location.reload(); // Reload the page after updating the person status
                        }
                    };
                    xhttp.open("POST", "update_trainer_status.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("username=" + username + "&status=1"); // Pass 1 as the status to archive the person
                }
            }
        }

        function activatePerson(username) {
            // Check user permissions and show popup if necessary
            if (!<?php echo isset($_SESSION['permissions']) && ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) ? 'true' : 'false'; ?>) {
                alert("You do not have permission to activate a trainer.");
            }
            else
            {
                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        location.reload(); // Reload the page after updating the person status
                    }
                };
                xhttp.open("POST", "update_trainer_status.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("username=" + username + "&status=0"); // Pass 1 as the status to archive the person
            }
        }
    </script>
    </body>
</html>
