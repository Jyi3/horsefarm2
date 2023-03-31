<?php

/* 
 * Horse.php: a PHP file acting as a class file for Horse objects.
 */

class Horse {
    private $horseName; //string
    private $color; //string
    private $breed; //string - can be null if not known
    private $pastureNum; //int
    private $colorRank; //string
    private $archived; //boolean
    private $dateArchived; //string that is in SQL Datetime format. Default = 00-00-0000 00:00:00
    private $trainer; //string   -   name of trainer assigned to horse

    function __construct($horseName, $color, $breed, $pastureNum, $colorRank, $archived, $dateArchived, $trainer) {
        $this->horseName = $horseName;
        $this->color = $color;
        $this->breed = $breed;
        $this->pastureNum = $pastureNum;
        $this->colorRank = $colorRank;
        $this->archived = $archived;
        $this->dateArchived = $dateArchived;
        $this->trainer = $trainer;
    }
    function get_horseName() {
        return $this->horseName;
    }
    function get_color() {
        return $this->color;
    }
    function get_breed() {
        return $this->breed;
    }
    function get_pastureNum() {
        return $this->pastureNum;
    }
    function get_colorRank() {
        return $this->colorRank;
    }
    function get_horseArchived(){
        return $this->archived;
    }
    function get_horseArchiveDate(){
        return $this->dateArchived;
    }
    function get_assignedTrainer(){
        return $this->trainer;
    }
}

