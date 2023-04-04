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
                bottom: 0;
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
                <form action="search.php" method="GET">
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
                echo "TEST";
                var type = document.getElementById("type").value;
                var search1Label = document.getElementById("search1-label");
                var search2Label = document.getElementById("search2-label");
                var search3Label = document.getElementById("search3-label");
                var search4Label = document.getElementById("search4-label");
                var search5Label = document.getElementById("search5-label");

                if (type == "horse") {
                    // search1Label.innerText = "Horse Name:";
                    // search2Label.innerText = "Horse Color:";
                    // search3Label.innerText = "Horse Breed:";
                    // search4Label.innerText = "Pasture Number:";
                    // search5Label.innerText = "Color Rank:";
                    // document.getElementById("search5-container").style.display = "block";
                } else {
                    search1Label.innerText = "Trainer Name:";
                    search2Label.innerText = "Trainer :";
                    search3Label.innerText = "Trainer :";
                    search4Label.innerText = "Trainer Rank:";
                    search5Label.innerText = "";
                    document.getElementById("search5-container").style.display = "none";
                }
            }

            if($_GET["type"] == "horse") {
                echo "TEST";
                $servername = "localhost";
                $username = "homebasedb";
                $password = "homebasedb";
                $dbname = "homebasedb";
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }


                $horsename = $_GET["search1"];
                $horsecolor = $_GET["search2"];
                $horseBreed = $_GET["search3"];
                $horsePastNum = $_GET["search4"];
                if(isset($_GET['search5'])) 
                    foreach($_GET['search5'] as $each_check)
                        if($each_check == "all") {
                            $each_check = "*";
                            return $each_check;
                            break;
                        }
                        else {
                            echo $each_check;
                        }
                $$query = "SELECT * FROM horseDB WHERE horseName='" . $horsename . "';";
                $results = mysqli_query($conn,$query);
                while($row = mysqli_fetch_assoc($results))
                {
                    echo $row['horseName']." ";
                    echo $row['color']." ";
                    echo $row['breed']." ";
                    echo $row['pastureNum']." ";
                    echo $row['colorRank']." ";


                }
                

                
                        //echo $each_check;
            }
        
            
            if ($_GET["type"] == "trainer") {
                $servername = "localhost";
                $username = "homebasedb";
                $password = "homebasedb";
                $dbname = "homebasedb";
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $personname = $_GET["search1"];
                $personnumber = $_GET["search2"];
                $personEmail = $_GET["search3"];
                $personRank = $_GET["search4"];
                //$query = "SELECT * FROM persondb WHERE username='" . $username . "';";

                $query = "SELECT * FROM persondb WHERE firstName='" . $personname ."'AND userType='" . $personRank . "'AND phone='". $personnumber ."'AND email='" . $personEmail . "';" ;
                $results = mysqli_query($conn,$query);
                //$row = mysqli_fetch_assoc($results);
                while($row = mysqli_fetch_assoc($results))
                {
                    echo $row['firstName']." ";
                    echo $row['lastName']." ";
                    echo $row['phone']." ";
                    echo $row['userType']." ";

                }
                //echo $row['firstName'];
                //echo $row['lastName'];



                // echo $personname;
                // echo $personnumber;
                // echo $personEmail;
                // echo $personRank;

            }

            </script>
            <?PHP //include('footer.inc'); ?>
        </div>
    </body>
</html>