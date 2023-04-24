
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

        .navbar {
            border: 1px solid #e5e5e5;
        }
        
        @media (max-width: 768px) {
            .intro h1 {
                font-size: 20px; 
            }
            .intro img {
                display: block; 
                margin: 0 auto 10px; 
            }
            .intro2 {
                position: static;
                padding: 10px 0; 
            }
            .navbar {
                border: none;
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

<header class="header">
    <nav class="navbar navbar-expand-md navbar-light bg-light navbar-lg">
        <!-- content of the navbar goes here -->
    </nav>
</header>

    <!-- End Header -->
</html>
