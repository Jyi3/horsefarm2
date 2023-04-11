<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Note {

    private $noteID;
    private $horseID;
    private $noteDate;
    private $noteTimestamp;
    private $note;
    private $username;
    private $archive;
    private $archiveDate;
    
    function __construct($noteID,$horseID,$noteDate,$noteTimestamp,$note,$username,$archive,$archiveDate) {
        $this->noteID = $noteID;
        $this->horseID = $horseID;
        $this->noteDate = $noteDate;
        $this->noteTimestamp = $noteTimestamp;
        $this->note = $note;
        $this->username = $username;
        $this->archive = $archive;
        $this->archiveDate = $archiveDate;
    }

    function get_noteID(){
        return $this->noteID;
    }
    function get_horseID() {
        return $this->horseID;
    }
    function get_noteDate() {
        return $this->noteDate;
    }
    function get_noteTimestamp() {
        return $this->noteTimestamp;
    }
    function get_note() {
        return $this->note;
    }
    function get_username() {
        return $this->username;
    }
    function get_archive() {
        return $this->archive;
    }
    function get_archiveDate() {
        return $this->archiveDate;
    }
    
}