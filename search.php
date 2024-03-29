<?php
    include('session.php');
?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the search parameters from the form
    $type = $_POST['type'];
    $search1 = isset($_POST['search1']) ? $_POST['search1'] : '';
    $search2 = isset($_POST['search2']) ? $_POST['search2'] : '';
    $search3 = isset($_POST['search3']) ? $_POST['search3'] : '';
    $search4 = isset($_POST['search4']) ? $_POST['search4'] : '';
    $search5 = isset($_POST['search5']) ? $_POST['search5'] : [];

    //Include MySQL connection file, horse database functions, and horse class.
    include_once('database/horsedb.php');
    include_once('database/dbinfo.php');
    include_once('domain/Horse.php');
    include_once('database/persondb.php');
    include_once('domain/Person.php');
    $conn = connect();

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($type == "horse")
    {
        $type = "horsedb ";
    }
    elseif ($type == "trainer") 
    {
        $type = "persondb ";
    }

    // Construct the SQL query based on the search parameters
    $sql = "SELECT * FROM " . $type;
    $conditions = [];


    if ($type == "horsedb ") 
    {
        if (!empty($search1)) {
            $conditions[] = "horseName LIKE '%" . $search1 . "%'";
        }
        if (!empty($search2)) {
            $conditions[] = "color LIKE '%" . $search2 . "%'";
        }
        if (!empty($search3)) {
            $conditions[] = "breed LIKE '%" . $search3 . "%'";
        }
        if (!empty($search4)) {
            $conditions[] = "pastureNum LIKE '%" . $search4 . "%'";
        }
        if (!empty($search5)) {
            if (in_array("All", $search5)) {
                // Do nothing (all colors are included by default)
            } else {
                $conditions[] = "colorRank IN ('" . implode("','", $search5) . "')";
            }
        }
        $conditions[] = "(archive = 0 OR archive IS NULL)"; // Add archive condition for horse search
    }
    elseif ($type == "persondb ") 
    {
        // Trainer search conditions
        if (!empty($search1)) {
            $conditions[] = "fullName LIKE '%" . $search1 . "%'";
        }
        if (!empty($search2)) {
            $conditions[] = "phone LIKE '%" . $search2 . "%'";
        }
        if (!empty($search3)) {
            $conditions[] = "email LIKE '%" . $search3 . "%'";
        }
        if (!empty($search4)) {
            $conditions[] = "userType LIKE '%" . $search4 . "%'";
        }
        $conditions[] = "userType NOT IN ('Viewer', 'Admin')"; // Exclude Viewer and Admin from trainer search
    }


    if (!empty($conditions)) {
        $sql .= " WHERE (" . implode(" AND ", $conditions) . ")";
    }

    // Execute the query
    $result = $conn->query($sql);

    // Close the database connection
    $conn->close();
}

?>

<html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" type="text/css" />

        <title>
            Search
        </title>
    <link rel="stylesheet" href="search-style.css">
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
            
            #contentSearches {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            label {
                flex-basis: 20%;
                text-align: right;
                padding-right: 10px;
                margin-bottom: 10px;
                color: #333;
            }

            input[type="text"], select {
                flex-basis: 75%;
                padding: 5px;
                border-radius: 5px;
                border: 1px solid #ddd;
                background-color: #f3f3f3;
            }

            .checkboxes-container {
                display: flex;
                flex-wrap: wrap;
                flex-basis: 75%;
                padding: 5px;
                border-radius: 5px;
                border: 1px solid #ddd;
                justify-content: center;
                background-color: #f3f3f3;
            }

            input[type="checkbox"] {
                flex-basis: 20%;
                margin-right: 10px;
            }

            .form-submit {
                flex-basis: 100%;
                margin-top: 10px;
                padding: 10px;
                background-color: #4b6c9e;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            #content-search {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 30%;
                max-width: 1400px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 10px;
                position: fixed;
                bottom: 0;
                right: 0;
                height: 400px;
                background-color: #f7f7f7;
                box-shadow: 0 0 5px #ccc;
                overflow: auto;
                z-index: 1;
                transform: scale(0.8);
                transform-origin: bottom right;
                }


            #minimizeButton {
                position: absolute;
                top: 10px;
                right: 10px;
                background-color: #4b6c9e;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                padding: 5px 10px;
            }

            #maximizeWindow {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 120px;
                height: 30px;
                padding: 5px;
                background-color: #4b6c9e;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1;
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


            #content {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            #content h3 {
                text-align: center;
            }

            #content table {
                margin: 0 auto;
            }

            @media (max-width: 768px) {
                #container {
                    max-width: 100%;
                    padding: 10px;
                }

                label {
                    text-align: left;
                    margin-bottom: 5px;
                }

                input[type="text"], select {
                    width: 100%;
                    margin-bottom: 10px;
                }

                .checkboxes-container {
                    flex-direction: column;
                }

                input[type="checkbox"] {
                    margin-right: 0;
                    margin-bottom: 5px;
                }

                #content-search {
                    width: 100%;
                    max-width: 100%;
                    height: auto;
                    bottom: unset;
                    right: unset;
                    top: 0;
                    left: 0;
                    border-radius: 0;
                    transform: none;
                }
            }

        </style>

    </head>
    <body>
        <div id="container">
            <?PHP include('header.php'); ?>
            
            <div id="content-search">
                <button id="minimizeButton" onclick="toggleSearch()" class="form-submit">Minimize</button>

                <form action="search.php" method="post" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #fff;">
                <label for="type">Type:</label>
                <select id="type" name="type" onchange="updateSearchCriteria()">
                    <option value="horse">Horse</option>
                    <option value="trainer">Trainer</option>
                </select>
                <label for="search1" id="search1-label">Name:</label>
                <input type="text" id="search1" name="search1">
                
                <label for="search2" id="search2-label">Color:</label>
                <input type="text" id="search2" name="search2">
                
                <label for="search3" id="search3-label">Breed:</label>
                <input type="text" id="search3" name="search3">
                
                <label for="search4" id="search4-label">Pasture:</label>
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
                    <label for="none-checkbox">All</label>
                </div2>
            
                <button type="submit" class="form-submit" class="form-submit">Search</button>
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
                            search1Label.innerText = "Name:";
                            search2Label.innerText = "Color:";
                            search3Label.innerText = "Breed:";
                            search4Label.innerText = "Pasture Number:";
                            search5Label.innerText = "Rank:";
                            document.getElementById("search5-container").style.display = "block";
                        } else {
                            search1Label.innerText = "Name:";
                            search2Label.innerText = "Phone:";
                            search3Label.innerText = "Email:";
                            search4Label.innerText = "Role:";
                            search5Label.innerText = "";
                            document.getElementById("search5-container").style.display = "none";
                        }
                    }

                    function toggleSearch() {
                        var searchForm = document.getElementById("content-search");
                        var minimizeButton = document.getElementById("minimizeButton");
                        var maximizeWindow = document.getElementById("maximizeWindow");

                        if (searchForm.style.display === "none") {
                            searchForm.style.display = "block";
                            minimizeButton.innerText = "Minimize";
                            maximizeWindow.style.display = "none";
                        } else {
                            searchForm.style.display = "none";
                            minimizeButton.innerText = "Maximize";
                            maximizeWindow.style.display = "flex";
                        }
                    }

                    function resizeContentSearches() {
                        var windowHeight = window.innerHeight;
                        var contentSearches = document.getElementById("contentSearches");
                        var newHeight = windowHeight / 8 * 7 - 200;
                        contentSearches.style.height = newHeight + "px";
                    }

                    resizeContentSearches();

                    window.addEventListener('resize', function () {
                        resizeContentSearches();
                    });

                </script>

        </div>

<!-- Add the maximizeWindow div -->
<div id="maximizeWindow" onclick="toggleSearch()" style="display:none;">
    <span>Maximize</span>
</div>

<!-- Add this new div below the form to display search results -->
<div id="content">
    <div id="contentSearches">
        <h3>How to search for active horses or trainers:</h3>
        <p>To search for a horse or trainer, select the appropriate search type from the dropdown menu above.</br>Enter your search criteria in the fields provided and click the "Search" button to retrieve matching results.</p>
        <p>For horse searches, you can search by name, color, breed, and pasture number.</br>You can also filter results by color rank by selecting one or more options from the checkboxes.</p>
        <p>For trainer searches, you can search by name, phone number, email, and role.</p>
        <p style="font-style: italic; color: #777;">Tip: Using more specific search criteria will result in more accurate and relevant search results.</p> 
        <p style="font-style: italic; color: #777;">Note: Archived horses are only viewable through the view horses page.</p>
        </br>
    </div>
    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($result) && $result->num_rows > 0): ?>
        <div id="contentSearches">
            <h3>Search Results:</h3>
            <table>
                <thead>
                    <?php if ($type == "horsedb "): ?>
                        <tr>
                            <th>Horse Name</th>
                            <th>Color</th>
                            <th>Breed</th>
                            <th>Pasture Number</th>
                            <th>Color Rank</th>
                        </tr>
                    <?php elseif ($type == "persondb "): ?>
                        <tr>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    <?php endif; ?>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php if ($type == "horsedb "): ?>
                            <tr>
                                <td><a href='horseprofile.php?horseID=<?php echo htmlspecialchars($row['horseID']); ?>' style='color: blue;'><?php echo htmlspecialchars($row['horseName']); ?></a></td>
                                <td><?php echo htmlspecialchars($row['color']); ?></td>
                                <td><?php echo htmlspecialchars($row['breed']); ?></td>
                                <td><?php echo htmlspecialchars($row['pastureNum']); ?></td>
                                <td><?php echo htmlspecialchars($row['colorRank']); ?></td>
                            </tr>
                        <?php elseif ($type == "persondb "): ?>
                            <tr>
                                <td><a href="trainerprofile.php?userName=<?php echo urlencode($row['username']); ?>" style="color: blue; text-decoration: underline;"><?php echo htmlspecialchars($row['fullName']); ?></a></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['userType']); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
            </br>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <div id="contentSearches">
            <h3>Search Results:</h3>
            <p>No results found for your search criteria.</p>
        </br>
        </div>
    <?php endif; ?>
</div>
<?php include('footer.php'); ?>
</div>
</body>
</html>

