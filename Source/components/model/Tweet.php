<?php

class Tweet {
  public $created_at;
  public $id;
  public $text;
  public $hashtags;
  public $user;
  public $retweet_count;
  public $favorite_count;
  public $lang;

  //We need a custom constructor because overloading is not allowed and PDO need the default one
  public static function construct($created_at, $id, $text, $hashtags, $user, $retweet_count, $favorite_count, $lang) {
    
    $tweet = new Tweet();
    $tweet->created_at 		= $created_at;
    $tweet->id 				= $id;
    $tweet->text 			= $text;
    $tweet->hashtags 		= $hashtags;
    $tweet->user 			= $user;
    $tweet->retweet_count 	= $retweet_count;
    $tweet->favorite_count 	= $favorite_count;
    $tweet->lang 			= $lang;

    return $tweet;
  }

  public function __toString() {
    return "TWEET: {$this->text}\n";
  }

  public function toRow() {

    $hashtagsArray = json_decode($this->hashtags, true);
    $hashtags = '';

    foreach( $hashtagsArray as $key => $value ){
        $hashtags .= $value["text"].", ";
    }

    $hashtags = substr($hashtags, 0, -2);

    $date = strtotime($this->created_at); 
    $date = date('d/M/Y h:i', $date); 

    $html = "
    <tr>
    <td>$date</td>
    <td>$this->id</td>
    <td>$this->text</td>
    <td>$hashtags</td>
    <td>$this->user</td>
    <td>$this->retweet_count</td>
    <td>$this->favorite_count</td>
    <td>$this->lang</td>
    </tr>
    ";

    return $html;

  }

}
