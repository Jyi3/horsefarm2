<?php
/*
 * Copyright 2015 by Allen Tucker. This program is part of RMHP-Homebase, which is free 
 * software.  It comes with absolutely no warranty. You can redistribute and/or 
 * modify it under the terms of the GNU General Public License as published by the 
 * Free Software Foundation (see <http://www.gnu.org/licenses/ for more information).
 */

session_start();
//session_cache_expire(3000);

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
                <center>
                <label id="search5-label">Horse Difficulty:</label>
                <input type="checkbox" id="green-checkbox" name="search5[]" value="green">
                <label for="green-checkbox">Green</label>
                <input type="checkbox" id="yellow-checkbox" name="search5[]" value="yellow">
                <label for="yellow-checkbox">Yellow</label>
                <input type="checkbox" id="red-checkbox" name="search5[]" value="red">
                <label for="red-checkbox">Red</label>
                <input type="checkbox" id="none-checkbox" name="search5[]" value="none">
                <label for="none-checkbox">All</label>
                </center>
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
                    search2Label.innerText = "Trainer Phone#:";
                    search3Label.innerText = "Trainer :";
                    search4Label.innerText = "Trainer Rank:";
                    search5Label.innerText = "";
                    document.getElementById("search5-container").style.display = "none";
                }
            }
            </script>
            <?PHP //include('footer.inc'); ?>
        </div>
    </body>
</html>