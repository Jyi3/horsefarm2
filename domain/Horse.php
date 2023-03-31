<?php

/* 
 * Horse.php: a PHP file acting as a class file for Horse objects.
 */

class Horse {
    private $horseID; //string
    private $horseName; //string
    private $color; //string
    private $breed; //string - can be null if not known
    private $pastureNum; //int
    private $colorRank; //string
    private $archive; //string
    private $archiveDate; //string
    
    function __construct($horseName, $color, $breed, $pastureNum, $colorRank) {
        $this->horseName = $horseName;
        $this->color = $color;
        $this->breed = $breed;
        $this->pastureNum = $pastureNum;
        $this->colorRank = $colorRank;
        $this->archive = false;
        $this->archiveDate = NULL;
    }
    function get_horseID() {
        return $this->horseID;
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
    function get_archive() {
        return $this->archive;
    }
    function get_archiveDate() {
        return $this->archiveDate;
    }
}

