<?php

/* 
 * Person.php: a PHP file acting as a class file for Person objects.
 */

class Person {
    private $firstName; //string
    private $lastName; //string
	private $fullName; //string
    private $phone; //int
    private $email; //string
	private $username; //string
	private $pass; //strng
    private $userType; //string
    private $archived; //boolean
    private $dateArchived;//string  SQL Datetime Format
 //Not going to have anassigned horse variable. Instead, we will query all horses whos trainer is equal to a persons fullName
    
    function __construct($firstName, $lastName, $fullName, $phone, $email, $username, $pass, $userType, $archived, $dateArchived) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
		$this->fullName = $fullName;
        $this->phone = $phone;
        $this->email = $email;
		$this->username = $username;
		$this->pass = $pass;
        $this->userType = $userType;
        $this->archived = $archived;
        $this->dateArchived = $dateArchived;

    }

    function get_firstName() {
        return $this->firstName;
    }

    function get_lastName() {
        return $this->lastName;
    }

	function get_fullName() {
        return $this->fullName;
    }

	function get_phone() {
        return $this->phone;
    }

    function get_email() {
        return $this->email;
    }

	function get_username() {
        return $this->username;
    }

	function get_pass() {
        return $this->pass;
    }

    function get_userType() {
        return $this->userType;
    }

    function get_personArchived(){
        return $this->archived;
    }

    function get_personArchivedDate(){
        return $this->dateArchived;
    }
}
