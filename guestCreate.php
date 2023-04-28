<?php
    include_once('domain/Person.php');
    include_once('database/persondb.php');
    include_once('database/dbinfo.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // create new Person object
        $username = str_replace("-", "", $_POST["phone"]); // remove dashes from phone number
        $username = $_POST["username"]; // combine name and phone number
        
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
            $sql = "INSERT INTO persondb (firstName, lastName, fullName, phone, email, username, pass, userType, archive, archiveDate) VALUES ('" . $_POST["firstName"] . "', '" . $_POST["lastName"] . "', '" . $_POST["firstName"] . " " . $_POST["lastName"] . "', '" . $_POST["phone"] . "', '" . $_POST["email"] . "', '" . $username . "', '" . $hash . "', 'Recruit', false, null)";

            // execute SQL query
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Your username is: ".$username."\nPlease contact the head trainer to update your permissions.')</script>";
                header("Location: login_form.php");
            } else {
                echo "<p>Error adding new user: " . mysqli_error($conn) . "</p>";
            }
        }

        // close database connection
        mysqli_close($conn);
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

            form {
                max-width: 500px;
                width: 90%;
                padding: 20px;
                background-color: #ffffff;
                border: 1px solid #cccccc;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                margin: 0 auto; /* add this line to center the content horizontally */
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
            @media screen and (max-width: 767px) {
                #container {
                    padding: 10px;
                    min-height: auto;
                }
                form {
                    max-width: 100%;
                }
                h1 {
                    font-size: 28px;
                }
                p {
                    font-size: 16px;
                    max-width: 90%;
                }
                input[type="text"],
                input[type="email"],
                input[type="password"] {
                    width: 100%;
                    padding: 8px;
                    border-radius: 5px;
                    border: 1px solid #cccccc;
                    margin-bottom: 10px;
                    box-sizing: border-box;
                    font-size: 16px;
                }
                input[type="submit"] {
                    padding: 8px 16px;
                    font-size: 16px;
                }
            }

            </style> 
            
    </head>
    <body>
        <div id="container">
            <?php include('header-no-menu.php'); ?>
            <div id="content">
                <div id="content-inner">
                    <h1>Create User</h1>
                    <br>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="firstName"><span style="color: red">*  </span>First Name:</label>
                            <input type="text" name="firstName" class="form-control" value="<?php echo isset($_POST["firstName"]) ? htmlspecialchars($_POST["firstName"]) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastName"><span style="color: red">*  </span>Last Name:</label>
                            <input type="text" name="lastName" class="form-control" value="<?php echo isset($_POST["lastName"]) ? htmlspecialchars($_POST["lastName"]) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone"><span style="color: red">*  </span>Phone: <span style="color: grey"><br> (xxx-xxx-xxxx) </br> </span></label>
                            <input type="tel" name="phone" class="form-control" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="username"><span style="color: red">*  </span>Username:</label>
                            <input type="username" name="username" class="form-control" value="<?php echo isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="pass"><span style="color: red">*  </span>Password:</label>
                            <input type="password" name="pass" class="form-control" minlength="8">
                        </div>
                        <input type="submit" value="Create User" class="btn btn-primary">
                    </form>
                    <br>
                </div>
            </div>
            <?php include('footer-no-session.php'); ?>
        </div>
    </body>
</html>
 