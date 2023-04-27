<?php
session_start();
include_once('database/dbinfo.php'); // Include your database connection file
$error = '';

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $conn = connect();

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $stmt = $conn->prepare("SELECT username, pass, userType, archive FROM persondb WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($username, $hashed_password, $userType, $archive);
            $stmt->fetch();

            if ($archive == 1) {
                echo "<script>alert('Your account has been inactivated.\nPlease contact an administrator for assistance.')</script>";
                echo "<meta http-equiv='refresh' content='0'>";
                exit;
            }

            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $username;

                $permissions = 1; // Default permissions for Tier 1 (Recruit)
                if ($userType == 'Trainer') {
                    $permissions = 2; // Tier 2
                } elseif ($userType == 'Head Trainer') {
                    $permissions = 3; // Tier 3
                }elseif ($userType == 'Viewer') {
                    $permissions = 4; // Tier 3
                }elseif ($userType == 'Admin') {
                    $permissions = 5; // Tier 3
                }
                $_SESSION['permissions'] = $permissions;

                header("Location: index.php"); // Redirect to the main page
                exit;
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }

        $stmt->close();
        $conn->close();
    } else {
        $error = "Please enter both username and password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        #content {
            max-width: 500px;
            width: 90%;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .error {
            color: #ff0000;
            font-weight: bold;
        }

        .create-account {
            margin-top: 10px;
            text-align: center;
        }

        .create-account a {
            text-decoration: none;
            color: #4b6c9e;
        }

        .create-account a:hover {
            text-decoration: underline;
        }

        h3 {
            color: #4b6c9e;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            margin: 0 auto;
        }

        .btn {
            background-color: #4b6c9e;
            border: none;
            color: #ffffff;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #3a5483;
        }
        
        @media only screen and (max-width: 768px) {
            body {
                padding: 20px;
            }

            #content {
                max-width: 100%;
                margin: 0;
            }

            h3 {
                font-size: 20px;
            }

            p {
                font-size: 16px;
            }
        }

        </style>
</head>
<body>
<div id="content">
    <div class="login-form">
        <h3>Login</h3>
        <p style="text-align: center;">Access to CVHR System requires a<br>Username and a Password.</p>
        <ul>
                <li>If you accidentally came to this site instead of the Central Virginia Horse Rescue website, please <a href="https://centralvahorserescue.org/" target="_blank">click here</a> to visit their site.</li>
                <li>If you are applying for a volunteer position, create an account down below.</li>
                <li>If you are having difficulty logging in or have forgotten your Password, please contact your administrator/head-trainer. </li>
        </ul>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
                <input type="submit" name="submit" value="Login" class="btn btn-primary">
            </div>
        </form>
        <div class="create-account">
            <p>Don't have an account? <a href="guestCreate.php">Create one</a></p>
        </div>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
