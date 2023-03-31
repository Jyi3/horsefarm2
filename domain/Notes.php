<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Notes {
    private $horseID; //string
    private $noteDate; //date format
    private $noteTimestamp; //timestamp
    private $note; //string
    private $trainerID; //string
    
    function __construct($horseID, $date, $timestamp, $note, $trainerID) {
            $this->horseID = $horseID;
            $this->date = $date;
            $this->timestamp = $timestamp;
            $this->note = $note;
            $this->trainerID = $trainerID;
    }
    function get_horseID() {
        return $this->horseID;
    }
    function get_date() {
        return $this->date;
    }
    function get_timestamp() {
        return $this->timestamp;
    }
    function get_note() {
        return $this->note;
    }
    function get_trainerID() {
        return $this->trainerID;
    }
}