
<?php
session_start();
?>

<html>
    <head>
        <style>
            #container {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                position: relative;
            }

            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .content {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 20px;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
                width: 80%;
                max-width: 500px;
            }
        </style>
        <meta HTTP-EQUIV="REFRESH" content="10; url=login_form.php">
        <title>
            Logged out of RMH Homebase
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css" />
        <script>
            setTimeout(function() {
                window.location.href = "login_form.php";
            }, 10000);
        </script>
    </head>
    <body>
        <div id="container">
        <?PHP include('header.php'); ?>
            <div class="overlay">
                <div class="content">
                    <?php
                    session_unset();
                    session_write_close();
                    ?>
                    <p>You are now logged out of the CVHR System.</p>
                    <button onclick="window.location.href = 'login_form.php'">Okay</button>
                </div>
            </div>
        </div>
        <?PHP //include('footer.inc'); ?>
    </body>
</html>
