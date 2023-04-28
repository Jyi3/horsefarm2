<?php
    include('session.php');
    include_once('database/dbinfo.php');
    include_once('database/persondb.php');
    include_once('domain/Person.php');

    // Check if the user has the necessary permissions
    if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] < 3) {
        die("You do not have permission to access this page.");
    }

    $trainers = getall_persondb();

    if ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_trainer"])) {
            // Check user permissions
            if (!isset($_SESSION['permissions']) || ($_SESSION['permissions'] != 3 && $_SESSION['permissions'] != 5)) {
                header("Location: editTrainer.php");
                exit();
            }
            $username = $_POST["username"];
            $firstName = htmlspecialchars(trim($_POST["firstName"]));
            $lastName = htmlspecialchars(trim($_POST["lastName"]));
            $email = htmlspecialchars(trim($_POST["email"]));
            $phoneNumber = htmlspecialchars(trim($_POST["phone"]));
            $username = htmlspecialchars(trim($_POST["username"]));
            $userType = $_POST['userType'];
            $pass = $_POST["pass"];

            // update Trainer data in the database
            $conn = connect();

            if ($pass != '') {
                $hash = password_hash($_POST["pass"], PASSWORD_BCRYPT);
            } else {
                // get password hash from persondb
                $result = mysqli_query($conn, "SELECT pass FROM persondb WHERE username='$username'");
                $row = mysqli_fetch_assoc($result);
                $hash = $row['pass'];
            }

            $sql = "UPDATE persondb SET firstName='$firstName', lastName='$lastName', email='$email', phone='$phoneNumber', pass='$hash', userType='$userType' WHERE username='$username'";


            // execute SQL query
            if (mysqli_multi_query($conn, $sql)) {
                header("Location: editTrainer.php");
            } else {
                echo "<p>Error updating trainer: " . mysqli_error($conn) . "</p>";
            }

            // close database connection
            mysqli_close($conn);


        }
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
                #container {
                    max-width: 100%;
                    padding: 10px;
                }
                #content-inner {
                    max-width: 90%;
                }
                h1 {
                    font-size: 28px;
                }
                p {
                    font-size: 16px;
                }
            }

        </style>

    </head>
    <body>
        <div id="container">
        <?php include('header.php'); ?>
        <div id="content">
            <div id="content-inner">
                <h1>Edit Trainer</h1>
                <br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="max-width: 500px; width: 90%; padding: 20px; background-color: #ffffff; border: 1px solid #cccccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin: 0 auto;">
                    <div class="form-group">
                        <label for="trainer">Select Trainer:</label>
                        <select name="username" class="form-control" onchange="this.form.submit()">
                            <option value="">--Select Trainer--</option>
                            <?php foreach ($trainers as $trainer) {
                                $selected = "";
                                if (isset($_POST["username"]) && $_POST["username"] == $trainer->get_username()) {
                                    $selected = "selected";
                                }
                                echo "<option value=\"" . $trainer->get_username() . "\" $selected>" . $trainer->get_fullName() . "</option>";
                            } ?>
                        </select>
                    </div>
                </form>
                <?php
                    if (isset($_POST["username"]) && $_POST["username"] != "") {
                        $trainer = retrieve_person_by_username($_POST["username"]);
                    ?>

                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="max-width: 500px; width: 90%; padding: 20px; background-color: #ffffff; border: 1px solid #cccccc; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin: 0 auto;">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="hidden" name="username" value="<?php echo $trainer->get_username(); ?>">
                            <span class="form-control-plaintext" readonly><?php echo $trainer->get_username(); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="firstName">First Name:</label>
                            <input type="text" name="firstName" class="form-control" value="<?php echo $trainer->get_firstName(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name:</label>
                            <input type="text" name="lastName" class="form-control" value="<?php echo $trainer->get_lastName(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $trainer->get_email(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number:</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo $trainer->get_phone(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="pass">Password:</label>
                            <input type="password" name="pass" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="userType">User Type:</label>
                            <select name="userType" class="form-control">
                                <option value="Recruit" <?php if ($trainer->get_userType() == 'Recruit') echo 'selected'; ?>>Recruit</option>
                                <option value="Trainer" <?php if ($trainer->get_userType() == 'Trainer') echo 'selected'; ?>>Trainer</option>
                                <option value="Head Trainer" <?php if ($trainer->get_userType() == 'Head Trainer') echo 'selected'; ?>>Head Trainer</option>
                            </select>
                        </div>
                        <button type="submit" name="update_trainer" class="btn btn-default">Update Trainer</button>
                    </form>

                    <?php
                    }
                    ?>
            </div>
            <br>
            </div>
            <?php include('footer.php'); ?>
        </div>
        <script>
            // Check user permissions and show popup if necessary
            if (!<?php echo isset($_SESSION['permissions']) && ($_SESSION['permissions'] == 3 || $_SESSION['permissions'] == 5) ? 'true' : 'false'; ?>) {
                alert("You do not have permission to edit a trainer.");
            }
        </script>
    </body>

</html>