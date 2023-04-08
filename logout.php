<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged out of RMH Homebase</title>
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
            min-height: 100vh;
        }
        #appLink:visited {
            color: gray; 
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
        }

        /* Popup styles */
        .overlay {
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .popup {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        .popup button {
            background-color: #4b6c9e;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="container">
        <div class="overlay">
            <div class="popup">
                <?php
                session_unset();
                session_write_close();
                ?>
                <p>You are now logged out of the CVHR System.</p>
                <button onclick="window.location.href = 'login_form.php'">Okay</button>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>
