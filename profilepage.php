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
        .profile-name {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
            align-items: center;
            text-align: center;
        }
        
        .profile-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            width: 80%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
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
        <?PHP include('header.php'); ?>
    
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
        $userName = "finalTest2finalTest21231234565"; // replace with actual username from session or URL parameter
        $sql = "SELECT * FROM persondb WHERE username = '$userName'";
        $notes = "SELECT * FROM notesdb WHERE username = '$userName'";
        $result = mysqli_query($conn, $sql);
        $result2 = mysqli_query($conn, $notes);

        // Check if there is exactly one person with the given username
        if (mysqli_num_rows($result) != 1) {
            die("Error: Invalid username");
        }

        // Retrieve person data from query result
        $row = mysqli_fetch_assoc($result);
        $firstName = $row["firstName"];
        $lastName = $row["lastName"];
        $userName = $row["username"];
        $email = $row["email"];
        $phone = $row["phone"];
        $userType = $row["userType"];
        // Close database connection
        mysqli_close($conn);
        ?>
        <title><?php echo $userName; ?>'s Profile</title>

        <div id="content">
            <div class="profile-container">
                <div class="profile-pic">
                    <img src="images/cvhrIMG.png" alt="Profile Picture">
                </div>
                <div class="profile-name">
                    <h1><?php echo $firstName . " " . $lastName; ?>'s Profile</h1>
                </div>
                <div class="profile-details">
                    <p><?php echo $userName; ?> : Username</p>
                    <p><?php echo $firstName . " " . $lastName; ?> : Fullname</p>
                    <p><?php echo $email; ?> : Email</p>
                    <p><?php echo $phone; ?> : Phone</p>
                    <p><?php echo $userType; ?> : User Type</p>
                    <?php
                    // Determine the label and action for the button based on the archived status
                    $buttonLabel = is_null($row['archive']) || $row['archive'] == 0 ? 'Archive' : 'Activate';
                    $buttonAction = is_null($row['archive']) || $row['archive'] == 0 ? 'archive' : 'activate';

                    ?>
                    <?php
                    // Check if the form was submitted
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Check which button was clicked
                        if (isset($_POST["archive"])) {
                            // Perform archive action
                            $conn = mysqli_connect($servername, $username, $password, $dbname);
                            $sql = "UPDATE persondb SET archive = 1 WHERE username = '$userName'";
                            mysqli_query($conn, $sql);
                            mysqli_close($conn);
                        } elseif (isset($_POST["activate"])) {
                            // Perform activate action
                            $conn = mysqli_connect($servername, $username, $password, $dbname);
                            $sql = "UPDATE persondb SET archive = 0 WHERE username = '$userName'";
                            mysqli_query($conn, $sql);
                            mysqli_close($conn);
                        }
                        // Redirect to current page to avoid form resubmission
                        header("Location: " . $_SERVER["PHP_SELF"]);
                        exit();
                    }
                    ?>

                <form method="POST">
                    <input type="hidden" name="username" value="<?php echo $userName; ?>">
                    <input type="submit" name="<?php echo $buttonAction; ?>" value="<?php echo $buttonLabel; ?>" />
                </form>
                                        
                </div>

            </div>
            <div class="notes" style="text-align: center;">
                <h2>Notes</h2>
                <?php if (mysqli_num_rows($result2) == 0): ?>
                    <p>No notes available by user</p>
                <?php else: ?>
                    <ul style="text-align: left;">
                        <?php while ($row = mysqli_fetch_assoc($result2)): ?>
                            <li><?php echo $row['noteText']; ?></li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <?PHP //include('footer.inc'); ?>
    </div>
</body
