<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Notes {
    private $horseName; //string
    private $noteDate; //date format
    private $noteTimestamp; //timestamp
    private $noteText; //string
    private $trainerFirstName; //string
    private $trainerLastName; //string
    private $noteID; //integer between -2,147,483,648 and 2,147,483,647.
    
    function __construct($horseName, $date, $timestamp, $noteText, $trainerFirstName, $trainerLastName,$noteID) {
            $this->horseName = $horseName;
            $this->date = $date;
            $this->timestamp = $timestamp;
            $this->noteText = $noteText;
            $this->trainerFirstName = $trainerFirstName;
            $this->trainerLastName = $trainerLastName;
            $this->noteID = $noteID;
    }
    function get_horseName() {
        return $this->horseName;
    }
    function get_date() {
        return $this->date;
    }
    function get_timestamp() {
        return $this->timestamp;
    }
    function get_noteText() {
        return $this->noteText;
    }
    function get_trainerFirstName() {
        return $this->trainerFirstName;
    }
    function get_trainerLastName() {
        return $this->trainerLastName;
    }
    function get_noteID(){
        return $this->noteID;
    }
}