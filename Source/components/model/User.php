<?php

class User {
  public $id;
  public $name;
  public $screen_name;
  public $location;
  public $description;

  //We need a custom constructor because overloading is not allowed and PDO need the default one
  public static function construct($id = "", $name = "", $screen_name = "", $location = "", $description = "") {
    
    $user = new User();
    $user->id 		      = $id;
    $user->name 		    = $name;
    $user->screen_name  = $screen_name;
    $user->location     = $location;
    $user->description  = $description;

    return $user;
  }

  public function __toString() {
    return "USER: {$this->name}\n";
  }

  public function toRow() {

    $html = "
    <tr>
    <td>$this->id</td>
    <td>$this->name</td>
    <td>$this->screen_name</td>
    <td>$this->location</td>
    <td>$this->description</td>
    </tr>
    ";

    return $html;

  }

}
