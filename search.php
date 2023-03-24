<?php

session_start();


?>

<html>
    <head>
        <title>
            CVHR Horse Training Management System
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css" />
        <style>
        	#appLink:visited {
        		color: gray; 
        	}	
            form {
			display: flex;
			flex-wrap: wrap;
			justify-content: space-between;
			align-items: center;
			max-width: 800px;
			margin: 0 auto;
			padding: 20px;
			border: 1px solid #ddd;
			border-radius: 10px;
            }

            label {
                flex-basis: 20%;
                text-align: right;
                padding-right: 10px;
                margin-bottom: 10px;
            }

            input[type="text"], select {
                flex-basis: 75%;
                padding: 5px;
                border-radius: 5px;
                border: 1px solid #ddd;
            }

            .checkboxes-container {
			display: flex;
			flex-wrap: wrap;
			flex-basis: 75%;
			padding: 5px;
			border-radius: 5px;
			border: 1px solid #ddd;
			justify-content: center;
		    }

            input[type="checkbox"] {
                flex-basis: 20%;
                margin-right: 10px;
            }

            button[type="submit"] {
                flex-basis: 100%;
                margin-top: 10px;
                padding: 10px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            #content {
            position: fixed;
            top: 0;
            right: 0;
            width: 300px;
            height: 400px;
            padding: 10px;
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            box-shadow: 0 0 5px #ccc;
            overflow: auto;
            }

        </style>

    </head>
    <body>
        <div id="container">
            <?PHP include('header.php'); ?>
            <div id="content">
            <form action="search.php" method="post">
            <label for="type">Search Type:</label>
            <select id="type" name="type" onchange="updateSearchCriteria()">
                <option value="horse">Horse</option>
                <option value="trainer">Trainer</option>
            </select>
            <label for="search1" id="search1-label">Horse Name:</label>
            <input type="text" id="search1" name="search1">
            
            <label for="search2" id="search2-label">Horse Color:</label>
            <input type="text" id="search2" name="search2">
            
            <label for="search3" id="search3-label">Horse Breed:</label>
            <input type="text" id="search3" name="search3">
            
            <label for="search4" id="search4-label">Pasture Number:</label>
            <input type="text" id="search4" name="search4">
            
            <div2 id="search5-container">
                <label id="search5-label">Horse Color:</label>
                <input type="checkbox" id="green-checkbox" name="search5[]" value="green">
                <label for="green-checkbox">Green</label>
                <input type="checkbox" id="yellow-checkbox" name="search5[]" value="yellow">
                <label for="yellow-checkbox">Yellow</label>
                <input type="checkbox" id="red-checkbox" name="search5[]" value="red">
                <label for="red-checkbox">Red</label>
                <input type="checkbox" id="none-checkbox" name="search5[]" value="All">
                <label for="none-checkbox">None</label>
		    </div2>
		
            <button type="submit">Search</button>
        </form>
        
        <script>
            function updateSearchCriteria() 
            {
                var type = document.getElementById("type").value;
                var search1Label = document.getElementById("search1-label");
                var search2Label = document.getElementById("search2-label");
                var search3Label = document.getElementById("search3-label");
                var search4Label = document.getElementById("search4-label");
                var search5Label = document.getElementById("search5-label");

                if (type === "horse") {
                    search1Label.innerText = "Horse Name:";
                    search2Label.innerText = "Horse Color:";
                    search3Label.innerText = "Horse Breed:";
                    search4Label.innerText = "Pasture Number:";
                    search5Label.innerText = "Color Rank:";
                    document.getElementById("search5-container").style.display = "block";
                } else {
                    search1Label.innerText = "Trainer Name:";
                    search2Label.innerText = "Trainer :";
                    search3Label.innerText = "Trainer :";
                    search4Label.innerText = "Trainer Rank:";
                    search5Label.innerText = "";
                    document.getElementById("search5-container").style.display = "none";
                }
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') 
            {
                // Get the search parameters from the form
                $type = $_POST['type'];
                $search1 = $_POST['search1'];
                $search2 = $_POST['search2'];
                $search3 = $_POST['search3'];
                $search4 = $_POST['search4'];
                $search5 = isset($_POST['search5']) ? $_POST['search5'] : [];

                //Include MySQL connection file, horse database functions, and horse class.
                include_once('database/horsedb.php');
                include_once('database/dbinfo.php');
                include_once('domain/Horse.php');
                $host = "localhost"; 
                $username = "homebasedb";
                $password = "homebasedb";
                $database = "horsefarm2";
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Construct the SQL query based on the search parameters
                $sql = "SELECT * FROM " . $type;
                $conditions = [];
                

                if (!empty($search1)) {
                    $conditions[] = "name LIKE '%" . $search1 . "%'";
                }
                if (!empty($search2)) {
                    $conditions[] = "color = '" . $search2 . "'";
                }
                if (!empty($search3)) {
                    $conditions[] = "breed = '" . $search3 . "'";
                }
                if (!empty($search4)) {
                    $conditions[] = "pasture_number = " . $search4;
                }
                if (!empty($search5)) {
                    $conditions[] = "color_rank IN ('" . implode("','", $search5) . "')";
                }

                if (!empty($conditions)) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                }

                // Execute the query
                $result = $conn->query($sql);

                // Create the windows
                echo "<div>";
                while ($row = $result->fetch_assoc()) {
                    echo "<div style='border: 1px solid black; padding: 10px; margin-bottom: 10px;'>";

                    if ($type == "horse") {
                        echo "<p>Name: " . $row['name'] . "</p>";
                        echo "<p>Color: " . $row['color'] . "</p>";
                        echo "<p>Breed: " . $row['breed'] . "</p>";
                        echo "<p>Pasture Number: " . $row['pasture_number'] . "</p>";
                        echo "<p>Color Rank: " . $row['color_rank'] . "</p>";
                    } else {
                        echo "<p>Name: " . $row['name'] . "</p>";
                        echo "<p>Rank: " . $row['rank'] . "</p>";
                    }

                    echo "</div>";
                }
                echo "</div>";

                // Close the database connection
                $conn->close();
            }
            </script>
            <?PHP //include('footer.inc'); ?>
        </div>
    </body>
</html>