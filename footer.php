<?php
/*
 * Copyright 2023 by Joon Yi. 
 * This program is part of CVHR Project, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html,
        body {
            height: 100%;
        }
        .wrapper {
            min-height: 100%;
            margin-bottom: -50px; /* equal to footer height */
        }
        .footer,
        .push {
            height: 50px;
        }
        .footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 10px 0;
            width: 100%;
            text-align: center;
        }
        .footer p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Begin content here -->
    </div>
    <div class="push"></div>
    <!-- Begin Footer -->
    <footer class="footer">
        <p>&copy; <?php echo date('Y'); ?> Central Virginia Horse Rescue. All rights reserved.</p>
    </footer>
    <!-- End Footer -->
</body>
</html>
