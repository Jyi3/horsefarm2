<?php
    include('session.php');

        
    // Check if the user has the necessary permissions
    if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] < 3) {
        die("You do not have permission to access this page.");
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create User | CVHR Horse Training Management System</title>
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
                include_once('domain/Person.php');
                include_once('database/persondb.php');
                include_once('database/dbinfo.php');

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    if (!isset($_SESSION['permissions']) || ($_SESSION['permissions'] != 3 && $_SESSION['permissions'] != 5)) {
                        header("Location: createUser.php");
                        exit();
                    }

                    $username = $_POST["username"];
                    
                    $hash = $_POST["pass"];
                    $hash = password_hash($hash, PASSWORD_BCRYPT); // use bcrypt algorithm

                    $conn = connect();
                    // check if username already exists
                    echo $username;
                    $sql = "SELECT username FROM persondb WHERE username='$username'";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        echo "DUPLICATE FOUND";
                        echo "<script>alert('Your username is: ".$username."\nPlease contact the head trainer to update your permissions.')</script>";
                    } else {
                        // insert new Person data into the database
                        $sql = "INSERT INTO persondb (firstName, lastName, fullName, phone, email, username, pass, userType, archive, archiveDate) VALUES ('" . $_POST["firstName"] . "', '" . $_POST["lastName"] . "', '" . $_POST["firstName"] . " " . $_POST["lastName"] . "', '" . $_POST["phone"] . "', '" . $_POST["email"] . "', '" . $username . "', '" . $hash . "', '" . $_POST["userType"] . "', false, null)";


                        // execute SQL query
                        if (mysqli_query($conn, $sql)) {
                            echo "<script>alert('Your username is: ".$username."\nPlease contact the head trainer to update your permissions.')</script>";
                            header("Location: index.php");
                        } else {
                            echo "<p>Error adding new user: " . mysqli_error($conn) . "</p>";
                        }
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
            <h1>Create User</h1>
            <br>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="max-width: 500px; width: 90%; padding: 20px; background-color: #ffffff; border: 1px solid #cccccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin: 0 auto;">
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" name="firstName" class="form-control" value="<?php echo isset($_POST["firstName"]) ? htmlspecialchars($_POST["firstName"]) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" name="lastName" class="form-control" value="<?php echo isset($_POST["lastName"]) ? htmlspecialchars($_POST["lastName"]) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" name="phone" class="form-control" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" class="form-control" value="<?php echo isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="pass">Password:</label>
                    <input type="password" name="pass" class="form-control">
                </div>
                <div class="form-group">
                    <label for="userType">User Type:</label>
                    <select name="userType" class="form-control">
                        <option value="Recruit">Recruit</option>
                        <option value="Trainer">Trainer</option>
                        <option value="Head Trainer">Head Trainer</option>
                    </select>
                </div>
                <input type="submit" value="Create User" class="btn btn-primary">
            </form>
            <br>
        </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <script>
        // Check user permissions and show popup if necessary
        if (!<?php echo isset($_SESSION['permissions']) && ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) ? 'true' : 'false'; ?>) {
            alert("You do not have permission to create a user.");
        }
    </script>
    </body>
</html>
 