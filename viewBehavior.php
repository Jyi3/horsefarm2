<?php
    include('session.php');
?>

<!DOCTYPE html>
<html>
<head>
        <title>
            CVHR Horse Training Management System
        </title>
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
            #appLink:visited {
                color: gray; 
            }
            #content {
                flex: 1 0 auto;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            #content-inner {
                text-align: center;
                max-width: 800px;
                width: 100%;
                min-height: 500px;
            }
            .remove-behavior-button {
                background-color: #4b6c9e;
                color: #fff;
                border: none;
                padding: 6px 14px;
                font-size: 16px;
                cursor: pointer;
                margin-left: 20px;
            }

            .remove-behavior-button:hover {
                background-color: #fff;
                color: #4b6c9e;
                border: 2px solid #4b6c9e;
            }

            .create-behavior-button {
                background-color: #4b6c9e;
                color: #fff;
                border: none;
                padding: 10px 26px;
                font-size: 20px;
                cursor: pointer;
                margin-left: 20px;
            }

            .create-behavior-button:hover {
                background-color: #fff;
                color: #4b6c9e;
                border: 2px solid #4b6c9e;
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
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                text-align: center;
                padding: 8px;

            }
            th {
                background-color: #4b6c9e;
                color: white;
                text-align: center;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            /* Footer */
            .footer {
                background-color: #f8f9fa;
                border-top: 1px solid #dee2e6;
                padding: 10px 0;
                text-align: center;
                margin-top: auto;
            }
            .footer p {
                margin: 0;
                font-size: 14px;
                color: #6c757d;
            }

            @media screen and (max-width: 768px) {
                #container {
                    max-width: 100%;
                    padding: 10px;
                }
                #content-inner {
                    max-width: 100%;
                }
                h1 {
                    font-size: 28px;
                }
                p {
                    font-size: 16px;
                }
                th, td {
                    font-size: 14px;
                }
                .footer p {
                    font-size: 12px;
                }
            }

        </style> 
</head>
<body>
    <div id="container">
        <?php include('header.php'); ?>
        <div id="content">
            <div id="content-inner">
                <?php
                include_once('database/dbinfo.php');
                include_once('domain/Behavior.php');
                include_once('database/behaviordb.php');

                // Retrieve all behaviors from the database
                $allBehaviors = getall_behaviordb();
                ?>

                <br>
                <br>
                <h2><strong>List of Behaviors</strong></h2>
                <br>
                <?php if (empty($allBehaviors)): ?>
                    <table>
                        <tr>
                            <td colspan='5' style='text-align:center'>There are no behaviors to display.</td>
                        </tr>
                    </table>
                <?php else: ?>
                    <table>
                        <tr>
                            <th style='text-align: center'>Name</th>
                            <th style='text-align: center'>Behavior Rank</th>
                            <th style='text-align: center'> Remove </th>
                        </tr>
                        <?php foreach ($allBehaviors as $behavior): ?>
                            <tr>
                                <td style='border-left: 1px solid black'><?php echo $behavior->get_title(); ?></td>
                                <td style='border-left: 1px solid black'><?php echo $behavior->get_behaviorLevel(); ?></td>
                                <td style='border-left: 1px solid black'>
                                    <button class='remove-behavior-button' onclick="removeBehavior('<?php echo $behavior->get_title(); ?>')">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>

                <br><br>
                <input type="button" class="create-behavior-button" onclick="window.location.href = 'createBehavior.php'" value="Create New Behavior">
                <hr style='clear:both;'>
                <hr style='clear:both;'>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <script>
        function removeBehavior(title) {
            console.log("removeBehavior called with title: " + title);
            const xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    location.reload(); // Reload the page after updating the person status
                }
            };
            xhttp.open("POST", "update_behavior_status.php");
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("title=" + title); // Pass 1 as the status to archive the person

        }
    </script>
</body>
</html>
