<?php
    /* 
     * about.php: a PHP file for information about CVHR.
     * Will hopefully contains pictures and links later.
     */

    session_start();
    //session_cache_expire(30);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>About</title>
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
                min-height: 100vh;
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
            }

            p {
                font-size: 18px;
                line-height: 1.6;
                margin: 0 auto;
            }

            a {
                color: #4b6c9e;
                text-decoration: underline;
            }

            a:visited {
                color: #4b6c9e;
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
        </style>
    </head>
    <body>
        <div id="container">
            <?php include('header.php'); ?>
            <div id="content">
                <div id="content-inner">
                    <h1>About</h1>
                    <p><strong>Background</strong></p>
                    <p>Central Virginia Horse Rescue, Inc. is a 501(c)3 small rescue founded in south central Virginia and dedicated to the care and rehabilitation of needy horses. In 2020, the Horse Adoption Central moved to Fredericksburg, VA. The rescue serves the state of Virginia.</p>
                    <p>Our mission is to save, protect, and rehabilitate equines in need. We rescue unwanted, abused, neglected, or abandoned equines; provide them with care and rehabilitation; and finally find them a compatible, loving home. We believe that education is the long-term solution to improving the lives of equines.</p>
                    <p>For more information, please visit our <a href="https://centralvahorserescue.org/" target="_blank"><strong><u>website</u></strong></a>.</p>
                </div>
            </div>
            <?php //include('footer.inc'); ?>
        </div>
    </body>
</html>
