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
            display: flex;
            flex-direction: column;
            min-height: 500px;
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
        @media screen and (max-width: 768px) {
            /* Reduce font sizes */
            h1 {
                font-size: 24px;
            }
            p {
                font-size: 16px;
            }

            /* Adjust container width and padding */
            #container {
                max-width: 90%;
                padding: 10px;
            }

            /* Change layout of profile-container */
            .profile-container {
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin-bottom: 10px;
            }

            /* Adjust layout of trainer-list-container */
            .trainer-list-container {
                margin: 0;
            }
            
            /* Adjust the layout of the popup */
            .popup {
                max-width: 90%;
            }
        }

    </style>
</head>
<body>
    <?php
    session_start();
    session_unset();
    session_destroy();
    ?>
    <div id="container">
        <div class="overlay">
            <div class="popup">
                <p>You are now logged out of the CVHR System.</p>
                <button onclick="window.location.href = 'login_form.php'">Okay</button>
            </div>
        </div>
    </div>
</body>
</html>
