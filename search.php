<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    }


    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
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
        <title>
            Search
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
                min-height: 100vh;
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

            #content-search {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 25%;
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
            }


            #minimizeButton {
                position: absolute;
                top: 10px;
                right: 10px;
                background-color: #4CAF50;
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
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }

            th {
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

        </style>

    </head>
    <body>
        <div id="container">
            <?PHP include('header.php'); ?>
            
            <div id="content-search">
                <button id="minimizeButton" onclick="toggleSearch()">Minimize</button>

                <form action="search.php" method="post">
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
        <h3>How to search for a horse or trainer:</h3>
        <p>To search for a horse or trainer, select the appropriate search type from the dropdown menu above.</br>Enter your search criteria in the fields provided and click the "Search" button to retrieve matching results.</p>
        <p>For horse searches, you can search by name, color, breed, and pasture number.</br>You can also filter results by color rank by selecting one or more options from the checkboxes.</p>
        <p>For trainer searches, you can search by name, phone number, email, and role.</p>
        <p style="font-style: italic; color: #777;">Tip: Using more specific search criteria will result in more accurate and relevant search results.</p> 
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
                                <td><?php echo htmlspecialchars($row['horseName']); ?></td>
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
<?php include('footer.php'); ?>
</div>
</body>

</html>

