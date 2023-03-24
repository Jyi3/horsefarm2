<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css" type="text/css" />
    <style>
        /* Circular frame for the image */
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
        }

        /* Style the image to fit within the circular frame */
        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Center the name in the middle of the page */
        .profile-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .profile-info h1 {
            font-size: 2em;
            margin-top: 20px;
        }

        /* Container for profile picture and name */
        .profile-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

    </style>

</head>
<body>
    <div id="container">
    
        <?php
        // Database connection information 
        $servername = "localhost";
        $username = "homebasedb";
        $password = "homebasedb";
        $dbname = "horsefarm2";

        // Create database connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check if connection is successful
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Retrieve person information from database based on username 
        $username = "homebasedb"; // replace with actual username from session or URL parameter
        $sql = "SELECT * FROM persondb WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        // Check if there is exactly one person with the given username
        if (mysqli_num_rows($result) != 1) {
            die("Error: Invalid username");
        }

        // Retrieve person data from query result
        $row = mysqli_fetch_assoc($result);
        $name = $row["username"];
        $email = $row["email"];
        $phone = $row["phone"];
        $userType = $row["userType"];
        // Close database connection
        mysqli_close($conn);
        ?>
        <title><?php echo $username; ?>'s Profile</title>

        <?PHP include('header.php'); ?>
        <div id="content">
            <div class="profile-container">
                <div class="profile-pic">
                    <img src="images/cvhrIMG.png" alt="Profile Picture">
                </div>
                <div class="profile-info">
                    <h1><?php echo $username; ?>'s Profile</h1>
                </div>
            </div>
            <p>Username: <?php echo $username; ?></p>
            <p>Email: <?php echo $email; ?></p>
            <p>Phone: <?php echo $phone; ?></p>
            <p>User Type: <?php echo $userType; ?></p>
        </div>
        <?PHP //include('footer.inc'); ?>
    </div>
</body>
</html>
