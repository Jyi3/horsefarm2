<?php
    include('session.php');

    // Check if the user has the necessary permissions (permissions level 2)
    if ($_SESSION['permissions'] < 2) {
        header("Location: index.php");
        exit;
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

                form {
                    max-width: 100%;
                }
            }
        </style> 
            <?php
                include_once('database/horsedb.php');
                include_once('database/dbinfo.php');

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // create new Horse object
						  if("" == trim($_POST['BehaviorName'])){
    					      echo "<p>Error adding new behavior:</p>";
    					      
						  }      
                    $conn = connect();
                    $sql = "SELECT username FROM persondb WHERE username='$username'";
                    // insert new Horse data into the database
                    $sql = "INSERT INTO behaviorDB (title, behaviorLevel) VALUES ('" . $_POST["BehaviorName"] . "', '" . $_POST["behaviorRank"] . "')";

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

            <h1>Create Horse</h1>
            <br>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="my-form">
                <div class="form-group">
                    <label for="BehaviorName">Behavior Name:</label>
                    <input type="text" name="BehaviorName" class="form-control" value="<?php echo isset($_POST["horseName"]) ? htmlspecialchars($_POST["horseName"]) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="behaviorRank">behavior Rank:</label>
                    <select name="behaviorRank" class="form-control">
                        <option value="Green" <?php if(isset($_POST['behaviorRank']) && $_POST['behaviorRank'] == 'Green') echo 'selected'; ?>>Green</option>
                        <option value="Yellow" <?php if(isset($_POST['behaviorRank']) && $_POST['behaviorRank'] == 'Yellow') echo 'selected'; ?>>Yellow</option>
                        <option value="Red" <?php if(isset($_POST['behaviorRank']) && $_POST['behaviorRank'] == 'Red') echo 'selected'; ?>>Red</option>
                        <option value="Misc" <?php if(isset($_POST['behaviorRank']) && $_POST['behaviorRank'] == 'Misc') echo 'selected'; ?>>Misc</option>
                    </select>
                </div>
                <input type="submit" value="Create Horse" class="btn btn-primary">
            </form>
            <br>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>

    </body>
</html>
 