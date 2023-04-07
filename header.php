<?php
/*
 * Copyright 2013 by Allen Tucker. 
 * This program is part of RMHP-Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */
?>
<!-- Begin Header -->
<style type="text/css">
    h1 {padding-left: 0px; padding-right: 165px;}
    #navigationLinks {font-size: 100%;}
    #mainNavLinks {font-size: 150%;}
    #navigationLinks a:first-child, #navigationLinks a:nth-child(2) {font-size: 130%;}
</style>
<div class="intro"> 
      <h1>
        <a href="https://centralvahorserescue.org/">
        <img src="https://i0.wp.com/centralvahorserescue.org/wp-content/uploads/2021/10/cropped-10441289_779793575378834_6338759994579667054_n.png?w=250&ssl=1" alt="CVHR Logo" style="float:left;width:72px;height:72px;"> 
        </a>
         CVHR Horse Training Management System</h1>
    </div>

<!--<br><br><img src="images/rmhHeader.gif" align="center"><br>
<h1><br><br>Homebase <br></h1>-->

<header class="header">
  <a href="index.php" class="logo">Home</a>
  <a href="search.php" class="logo">Search</a>
  <a href="about.php" class="logo">About</a>
  <nav class="menu-options">
    <div class = "dropdown">
      <p> &emsp; Database</p>
      <div class = "dropdown-content">
        <a href="horseActions.php?formAction=searchHorse">View All Horses</a>
        <br>
        <a href="personActions.php?formAction=searchPeople">View All Trainers</a>
        <br>
        <a href="search.php">Search</a>
      </div>
    </div>
    <div class = "dropdown">
      <p>&emsp; Training</p>
      <div class = "dropdown-content">
        <a href="behaviorActions.php?formAction=addBehavior">Create Behavior</a>
        <br>
        <a href="behaviorActions.php?formAction=selectBehavior">Edit Behavior</a>
      </div>
    </div>
    <div class = "dropdown">
      <p>&emsp; Administration</p>
      <div class = "dropdown-content">
        <a href="personActions.php?formAction=selectPerson">Edit Trainers</a>
        <br>
        <a href="personActions.php?formAction=searchPeople">Archive Trainer</a>
        <br>
        <a href="personActions.php?formAction=searchPeople">Archive Horse</a>
      </div>
    </div>
    <a href="logout.php">&emsp; Logout</a>
    <br>
  </nav>
</header>



<div align="center" id="navigationLinks">
    <?PHP    
        
        echo('<br><b style="font-size:200%;">'.'CVHR Horse Training Management System'.'</b> ');
        echo('<br><br>');

        //echo('<a href="index.php" style="font-size:150%;">HOME</a>');
       // echo('<a style="font-size:200%;"> | </a>');
       // echo('<a href="search.php" style="font-size:150%;">SEARCH</a>');
       // echo('<a style="font-size:200%;"> | </a>');
       // echo('<a href="about.php" style="font-size:150%;">ABOUT</a>');
        
       
        echo('</br>');
        echo('<br>');
        echo('<strong>Horse Actions | </strong>
                        <a href="horseActions.php?formAction=searchHorse"><u>View Horses</u></a>,
                        <a href="horseActions.php?formAction=addHorse"><u>Add Horse</u></a>, 
                        <a href="horseActions.php?formAction=selectHorse"><u>Edit Horse</u></a>,
                        <a href="horseActions.php?formAction=removeHorse"><u>Remove Horse</u></a>');
        echo('<br><br>');
        echo('<strong>Behavior Actions</strong> | 
                        <a href="behaviorActions.php?formAction=searchBehavior"><u>Search Behaviors</u></a>, 
                        <a href="behaviorActions.php?formAction=addBehavior"><u>Add Behavior</u></a>, 
                        <a href="behaviorActions.php?formAction=selectBehavior"><u>Edit Behavior</u></a>,
                        <a href="behaviorActions.php?formAction=removeBehavior"><u>Remove Behavior</u></a>');
        echo('<br><br>');
        echo('<strong>Trainer Actions</strong> | 
                        <a href="personActions.php?formAction=searchPeople"><u>Search Trainers</u></a>, 
                        <a href="personActions.php?formAction=addPerson"><u>Add Trainer</u></a>, 
                        <a href="personActions.php?formAction=selectPerson"><u>Edit Trainer</u></a>,
                        <a href="personActions.php?formAction=removePerson"><u>Remove Trainer</u></a>');
        echo("<br><br>");

        echo("_______________________________________________________________________________________________________________________________________");
        echo("<br><br>");
        echo('<strong>Notes Actions</strong> (links later!) | 
                        <a href="noteActions.php?formAction=searchNote"><u>Search Training Notes</u></a>, 
                        <a href="noteActions.php?formAction=addNote"><u>Add Training Notes</u></a>, 
                        <a href="noteActions.php?formAction=editNote"><u>Edit Training Notes</u></a>,
                        <a href="noteActions.php?formAction=removeNote"><u>Remove Training Notes</u></a>');
        echo("<br><br>");
        echo('<strong>Management Actions</strong> (links later!) | 
                        <a><u>Assign Trainer</u></a>, 
                        <a><u>Unassign Trainer</u></a>, 
                        <a><u>Assign Behavior</u></a>,
                        <a><u>Unassign Behavior</u></a>');
        echo("<br>");
        echo("_______________________________________________________________________________________________________________________________________");
        echo("<br><br><br><br>");
    ?>
</div>
<!-- End Header -->
