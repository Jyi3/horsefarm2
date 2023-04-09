<?php
    include('session.php');
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
    <style>
        /* Custom styles */
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

<header class="header">
    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <a class="navbar-brand" href="index.php">Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Database
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="viewHorses.php">View All Horses</a>
                    <a class="dropdown-item" href="viewTrainers.php">View All Trainers</a>
                    <a class="dropdown-item" href="search.php">Search</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Horse Admin
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="horseActions.php?formAction=addHorse">Create Horse</a>
                    <a class="dropdown-item" href="horseActions.php?formAction=selectHorse">Edit Horses</a>
                    <a class="dropdown-item" href="archiveHorses.php">Archive Horse</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Trainer Admin
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="createUser.php">Create Trainer</a>
                    <a class="dropdown-item" href="personActions.php?formAction=selectPerson">Edit Trainers</a>
                    <a class="dropdown-item" href="archiveTrainers.php">Archive Trainers</a>
                </div>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>

    </div>

    </nav>
    </header>
    <!-- End Header -->
</html>
