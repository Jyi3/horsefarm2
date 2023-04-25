<?php
    include 'session.php';
    include_once 'database/behaviordb.php';
    include_once 'database/dbinfo.php';

    // Check if the user has the necessary permissions
    if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] < 3) {
        die("You do not have permission to access this page.");
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    }
    
    
    
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if (!isset($_SESSION['permissions']) || ($_SESSION['permissions'] != 3 && $_SESSION['permissions'] != 5)) {
            header("Location: createBehavior.php");
            exit();
        }

        if ($_POST['action'] == 'Create Behavior') 
        {
            // create new behavior
            if (trim($_POST['BehaviorName']) == '') {
                echo "<p>Error adding new behavior:</p>";
            } else {
                $conn = connect();


                $sql = "INSERT INTO behaviordb (title, behaviorLevel) VALUES ('" . $_POST['BehaviorName'] . "', '" . $_POST['behaviorLevel'] . "')";

                // execute SQL query
                if (mysqli_query($conn, $sql)) {
                    header("Location: createBehavior.php");
                } else {
                    echo "<p>Error adding new behavior: " . mysqli_error($conn) . "</p>";
                }
                
                // remove behavior with title containing only whitespace
                $sql_remove = "DELETE FROM `behaviordb` WHERE 'title' = ''";
                if (mysqli_query($conn, $sql_remove)) {
                    // Successfully removed behavior with title containing only whitespace
                }

                // close database connection
                mysqli_close($conn);
            }
        } 
        else if ($_POST['action'] == 'Add Rank') 
        {
            // create new behavior with whitespace name and posted rank as behavior level
            $conn = connect();
            $sql = "INSERT INTO behaviordb (title, behaviorLevel) VALUES ('', '" . $_POST['BehaviorName'] . "')";

            // execute SQL query
            if (mysqli_query($conn, $sql)) {
                header("Location: createBehavior.php");
            } else {
                echo "<p>Error adding new behavior: " . mysqli_error($conn) . "</p>";
            }

            // close database connection
            mysqli_close($conn);
        }

    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Create Behavior | CVHR Horse Training Management System</title>
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
        <?php
            include_once('database/behaviordb.php');
            include_once('database/dbinfo.php');

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Check user permissions
                if (!isset($_SESSION['permissions']) || ($_SESSION['permissions'] != 3 && $_SESSION['permissions'] != 5)) {
                    header("Location: createBehavior.php");
                    exit();
                }
                // create new Behavior 
                    if("" == trim($_POST['BehaviorName'])){
                        echo "<p>Error adding new behavior:</p>";
                        
                    }      
                $conn = connect();
                $sql = "INSERT INTO behaviorDB (title, behaviorLevel) VALUES ('" . $_POST["BehaviorName"] . "', '" . $_POST["behaviorLevel"] . "')";

                // execute SQL query
                if (mysqli_query($conn, $sql)) {
                    header("Location: createBehavior.php");
                } else {
                    echo "<p>Error adding new behavior: " . mysqli_error($conn) . "</p>";
                }

                // close database connection
                mysqli_close($conn);

            }
        ?>
    </head>
    <body>
        <div id="container">
            <?php include('header.php'); ?>
            <div id="content">
                <div id="content-inner">
                    <h1>Create Behavior</h1>
                    <div style="display: flex;">
                        <ol style="margin-right: 20px;">
                            <p>To create a new behavior:</p>
                            <li>Select the behavior rank from the drop-down menu.</li>
                            <li>Type in the name of the new behavior.</li>
                            <li>Click the "Create Behavior" button to add the behavior to the database.</li>
                        </ol>
                        <ol>
                            <p>To create a new rank:</p>
                            <li>Type in the name of the new behavior rank.</li>
                            <li>Click the "Create Rank" button to add a new rank to the database.</li>
                        </ol>
                    </div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="my-form">
                        <div class="form-group">
                            <label for="BehaviorName"><span style="color: red">*  </span>Behavior Name/Rank:</label>
                            <input type="text" name="BehaviorName" class="form-control" value="<?php echo isset($_POST["BehaviorName"]) ? htmlspecialchars($_POST["BehaviorName"]) : ''; ?>">
                        </div>
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
                        <input type="submit" name="action" value="Add Behavior" class="btn btn-primary">
                        <input type="submit" name="action" value="Add Rank" class="btn btn-secondary" style="margin-left: 20%;">
                        <div style="margin-top: 20px;">
                            <input type="button" class="view-behavior-button" onclick="window.location.href = 'viewBehavior.php'" value="View Behaviors">
                        </div>
                    </form>
                <br>    
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
 