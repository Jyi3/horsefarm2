<?php
    include 'session.php';
    include_once 'database/behaviordb.php';
    include_once 'database/dbinfo.php';

    // Check if the user has the necessary permissions
    if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] < 3) {
        die("You do not have permission to access this page.");
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if (!isset($_SESSION['permissions']) || ($_SESSION['permissions'] != 3 && $_SESSION['permissions'] != 5)) {
            header("Location: createBehavior.php");
            exit();
        }

        if ($_POST['action'] == 'Delete Behavior') 
        {
            $behaviorLevel = $_POST['behaviorLevel'];
            // Confirm with the user before deleting the rank and its associated titles and behavior levels
            echo '<script language="javascript">';
            echo 'if(confirm("Are you sure you want to delete the rank ' . $behaviorLevel . ' and its associated titles and behavior levels?")){';
            // Delete the rank and its associated titles and behavior levels
            $conn = connect();
            $deleteHorsetoBehaviorSql = "DELETE FROM horsetobehaviordb WHERE title IN (SELECT title FROM behaviordb WHERE behaviorLevel = '$behaviorLevel')";
            $deleteTitlesSql = "DELETE FROM behaviordb WHERE behaviorLevel = '$behaviorLevel'";
            if (mysqli_query($conn, $deleteHorsetoBehaviorSql) && mysqli_query($conn, $deleteTitlesSql)) {
                echo 'alert("Rank and associated titles and behavior levels have been deleted.");';
            } else {
                echo 'alert("Error deleting rank: ' . mysqli_error($conn) . '");';
            }
            echo '} else {';
            echo 'window.location.href = "deleteRanks.php";';
            echo '}';
            mysqli_close($conn);
        } 

    }
?>




<!DOCTYPE html>
<html>
    <head>
        <title>Remove Rank | CVHR Horse Training Management System</title>
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

            .my-form {
                max-width: 500px;
                width: 90%;
                padding: 20px;
                background-color: #ffffff;
                border: 1px solid #cccccc;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                margin: 0 auto;
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
                margin: 0 auto; /* add this line to center the content horizontally */
            }


            h1 {
                color: #4b6c9e;
                font-size: 36px;
                margin-bottom: 20px;
                text-align: center;
                margin: 0 auto;
            }

            .view-behavior-button {
                background-color: #4b6c9e;
                color: #ffffff;
                padding: 8px 16px;
                border: none;
                border-radius: 5px;
                font-weight: bold;
                cursor: pointer;
            }

            .view-behavior-button:hover {
                background-color: #374c6f;
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

            input[type="text"], input[type="email"], input[type="password"] {
                width: 100%;
                padding: 8px;
                border-radius: 5px;
                border: 1px solid #cccccc;
                margin-bottom: 20px;
                box-sizing: border-box;
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
                
                p {
                    font-size: 16px;
                    max-width: 90%;
                }

                #container {
                    padding: 10px;
                }

                .my-form {
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
                    <h1>Add Behavior</h1>
                    <p>To delete a rank:</p>
                    <p>Select the rank to delete</p>
                    <p>Click the "Remove Rank" button.</p>
                    <p>You will receive a confirmation prompt.</p>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="my-form">
                        <div class="form-group">
                            <label for="behaviorLevel"><span style="color: red">*  </span>Behavior Rank: </label>
                            <select name="behaviorLevel" class="form-control">
                                <?php
                                $conn = connect();
                                $behaviorLevelSql = "SELECT DISTINCT behaviorLevel FROM behaviordb ORDER BY 
                                    CASE behaviorLevel
                                        WHEN 'Green' THEN 1
                                        WHEN 'Yellow' THEN 2
                                        WHEN 'Red' THEN 3
                                        ELSE 4
                                    END, 
                                    behaviorLevel ASC";
                                $behaviorLevelResult = mysqli_query($conn, $behaviorLevelSql);
                                while($row = mysqli_fetch_array($behaviorLevelResult)) {
                                    echo "<option value='" . $row['behaviorLevel'] . "' ";
                                    if(isset($_POST['behaviorLevel']) && $_POST['behaviorLevel'] == $row['behaviorLevel']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $row['behaviorLevel'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <input type="submit" name="action" value="Delete Behavior" class="btn btn-primary">
                        <div style="margin-top: 20px;">
                            <input type="button" class="view-behavior-button" onclick="window.location.href = 'viewBehavior.php'" value="View Behaviors">
                        </div>
                    </form>
                </div>
            </div>
            <?php include('footer.php'); ?>
        </div>
        <script>
            // Check user permissions and show popup if necessary
            if (!<?php echo isset($_SESSION['permissions']) && ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) ? 'true' : 'false'; ?>) {
                alert("You do not have permission to create a behavior.");
            }
        </script>
    </body>
</html>
 