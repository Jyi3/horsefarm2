<?php
    include_once('session.php');
    function customErrorHandler($errno, $errstr, $errfile, $errline) {
        header("Location: index.php");
        exit;
    }

    set_error_handler("customErrorHandler");
?>

<?php
    include_once('database/dbinfo.php');
    
    $conn = connect();
    $greenCount = mysqli_query($conn, "SELECT COUNT(*) FROM horsedb WHERE colorRank = 'green' AND (archive IS NULL OR archive = 0)")->fetch_row()[0] ?? 0;
    $yellowCount = mysqli_query($conn, "SELECT COUNT(*) FROM horsedb WHERE colorRank = 'yellow' AND (archive IS NULL OR archive = 0)")->fetch_row()[0] ?? 0;
    $redCount = mysqli_query($conn, "SELECT COUNT(*) FROM horsedb WHERE colorRank = 'red' AND (archive IS NULL OR archive = 0)")->fetch_row()[0] ?? 0;
    $totalCount = $greenCount+$yellowCount+$redCount;
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="buttonStyle.css">

    <style>
        .intro {
        text-align: center;
        margin-bottom: 20px;
        }

        .intro2 {
        position: fixed;
        bottom: 0;
        left: 0;
        padding: 10px;
        z-index: 1000;
        }

        .intro img {
        margin-right: 10px;
        vertical-align: middle;
        }

        .intro h1 {
        display: inline;
        font-size: 24px;
        }

        .header-button {
        color: #4b6c9e;
        text-decoration: none;
        }

        .horse-number {
        display: inline;
        font-size: 2em;
        margin-right: 10px;
        border: none;
        }


        .green-horse-form, .yellow-horse-form, .red-horse-form, .total-horse-form {
        font-weight: bold;
        display: flex;
        border: none;
        }

        .horse-number button {
        align-items: center;
        border: none;
        font-size: 1em;
        color: inherit;
        cursor: pointer;
        }

        .header-button {
        border: none;
        }

        .horse-count {
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: center;
        font-size: 2em;
        }

        @media (max-width: 768px) {
        .intro h1 {
            font-size: 20px;
        }

        .horse-number {
            font-size: 1.5em;
        }
        }

    </style>
</head>
<body>
    <!-- Begin Header -->
    <div class="intro"> 
        <h1>
            <a href="https://centralvahorserescue.org/">
            <img src="https://i0.wp.com/centralvahorserescue.org/wp-content/uploads/2021/10/cropped-10441289_779793575378834_6338759994579667054_n.png?w=250&ssl=1" alt="CVHR Logo" class="img-fluid" style="width:72px;height:72px;"> 
            </a>
            CVHR Horse Training Management System
        </h1>
    </div>

    <header class="header container">
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Home</a>
            <button class="navbar-toggler" type="header-button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search.php">Search</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="header-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Database
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="viewHorses.php">View All Horses</a>
                    <a class="dropdown-item" href="viewTrainers.php">View All Trainers</a>
                    <a class="dropdown-item" href="search.php">Search</a>
                    </div>
                </li>
                <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] >= 3) { ?>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="header-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Behavior Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="createBehavior.php">Add Behaviors</a>
                        <a class="dropdown-item" href="createRanks.php">Create Ranks</a>
                        <a class="dropdown-item" href="deleteRanks.php">Delete Ranks</a>
                        <a class="dropdown-item" href="viewBehavior.php">View All Behaviors</a>
                    </div>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="header-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Horse Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="createHorse.php">Create Horse</a>
                        <a class="dropdown-item" href="editHorse.php">Edit Horses</a>
                        <a class="dropdown-item" href="archiveHorses.php">Archive Horse</a>
                    </div>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="header-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Trainer Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="createUser.php">Create Trainer</a>
                        <a class="dropdown-item" href="editTrainer.php">Edit Trainers</a>
                        <a class="dropdown-item" href="archiveTrainers.php">Archive Trainers</a>
                    </div>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </nav>

        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <form id="total-horse-form" action="search.php" method="post">
                            <input type="hidden" name="type" value="horse">
                            <input type="hidden" name="search5[]" value="All">
                            <a class="nav-link header-button horse-count" href="#" onclick="document.getElementById('total-horse-form').submit();" style="color: #4b6c9e;"><?php echo $totalCount; ?></a>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form id="green-horse-form" action="search.php" method="post">
                            <input type="hidden" name="type" value="horse">
                            <input type="hidden" name="search5[]" value="green">
                            <a class="nav-link header-button horse-count" href="#" onclick="document.getElementById('green-horse-form').submit();" style="color: green;"><?php echo $greenCount; ?></a>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form id="yellow-horse-form" action="search.php" method="post">
                            <input type="hidden" name="type" value="horse">
                            <input type="hidden" name="search5[]" value="yellow">
                            <a class="nav-link header-button horse-count" href="#" onclick="document.getElementById('yellow-horse-form').submit();" style="color: #F0C808;"><?php echo $yellowCount; ?></a>
                        </form>
                    </li>
                    <li class="nav-item">
                        <form id="red-horse-form" action="search.php" method="post">
                            <input type="hidden" name="type" value="horse">
                            <input type="hidden" name="search5[]" value="red">
                            <a class="nav-link header-button horse-count" href="#" onclick="document.getElementById('red-horse-form').submit();" style="color: red;"><?php echo $redCount; ?></a>
                        </form>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="editProfile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        


    </header>

    <!-- End Header -->
</html>
