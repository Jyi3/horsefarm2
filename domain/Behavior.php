<?php

/* 
 * Behavior.php: a PHP file acting as a class file for Behavior objects.
 */
class Behavior {
   private $title; //string
   private $behaviorLevel; //string
   private $completed; //Boolean (0 = Completed, 1 = Incomplete)
   
   function __construct($title, $behaviorLevel, $completed) {
       $this->title = $title;
       $this->behaviorLevel = $behaviorLevel;
       $this->completed = $completed;
   }
   function get_title() {
       return $this->title;
   }
   function get_behaviorLevel() {
       return $this->behaviorLevel;
   }
   function get_completion(){
        return $this->behaviorLevel;
   }
}
