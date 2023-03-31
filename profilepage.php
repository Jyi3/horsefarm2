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

        /* Container for profile picture and details */
        .profile-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        /* Container for profile details */
        .profile-details {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            font-weight: bold;
        }

        /* Style profile detail labels */
        .profile-details p {
            margin: 5px 0;
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
        $dbname = "homebasedb";

        // Create database connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check if connection is successful
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Retrieve person information from database based on username 
        $username = "Sally7037993827"; // replace with actual username from session or URL parameter
        $sql = "SELECT * FROM dbPersons WHERE id = '$username'";
        $result = mysqli_query($conn, $sql);

        // Check if there is exactly one person with the given username
        if (mysqli_num_rows($result) != 1) {
            die("Error: Invalid username");
        }

        // Retrieve person data from query result
        $row = mysqli_fetch_assoc($result);
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
        $id = $row["id"];
        $email = $row["email"];
        $phone = $row["phone1"];
        $userType = $row["type"];
        // Close database connection
        mysqli_close($conn);
        ?>
        <title><?php echo $id; ?>'s Profile</title>

        <?PHP include('header.php'); ?>
        <div id="content">
            <div class="profile-container">
                <div class="profile-pic">
                    <img src="images/cvhrIMG.png" alt="Profile Picture">
                </div>
                <div class="profile-info">
                    <h1><?php echo $id; ?>'s Profile</h1>
                </div>
                <div class="profile-details">
                    <p>Username: <?php echo $username; ?></p>
                    <p>Email: <?php echo $email; ?></p>
                    <p>Phone: <?php echo $phone; ?></p>
                    <p>User Type: <?php echo $userType; ?></p>
                </div>
            </div>
        </div>
        <?PHP //include('footer.inc'); ?>
    </div>
</body
