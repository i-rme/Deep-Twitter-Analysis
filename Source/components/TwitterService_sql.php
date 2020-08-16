<?php
require_once("model/iTwitterService.php");
require_once("model/Tweet.php");
require_once("model/User.php");

class TwitterService_sql implements iTwitterService
{
    private $db;

    public function __construct()
    {
        // Read configuration
        $config = require("config/sql_config.php");
        $server = $config["db"]["server"];
        $database = $config["db"]["database"];
        $username = $config["db"]["username"];
        $password = $config["db"]["password"];

        //$connectionString = "mysql:host=$server;dbname=$database";
        $connectionString = "pgsql:host=$server;dbname=$database";
        $this->db = new PDO($connectionString, $username, $password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function query($collumn, $string, $table, $class): array
    {
        $collumn = htmlentities($collumn);
        try
        {
            
            $statement = $this->db->prepare("SELECT * FROM $table WHERE $collumn LIKE :string");
            $statement->execute([ ":string" => "%".$string."%" ]);
            $rows = $statement->fetchAll(PDO::FETCH_CLASS, $class);    // Load rows in array
            $db = null;     // Close connection

            return $rows;
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    private function queryNaturalLanguage($string, $table, $class): array
    {

        if($table == 'tweets')
        {
            $match = 'text,hashtags,user';
        }else if($table == 'users')
        {
            $match = 'name,screen_name,location,description';
        }

        try
        {
            if($string == ''){
                $statement = $this->db->prepare("SELECT * FROM $table");
            }else{
                $statement = $this->db->prepare("SELECT * FROM $table WHERE MATCH ($match) AGAINST (:string IN NATURAL LANGUAGE MODE)");
            }
            $statement->execute([ ":string" => "%".$string."%" ]);
            $rows = $statement->fetchAll(PDO::FETCH_CLASS, $class);    // Load rows in array
            $db = null;     // Close connection

            //print_r($statement);

            return $rows;
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    private function queryTweets($collumn, $string = ''): array
    {
        return $this->query($collumn, $string, "tweets", "Tweet");
    }

    private function queryUsers($collumn, $string = ''): array
    {
        return $this->query($collumn, $string, "users", "User");
    }

    public function getTweets($limit = null, $order = null): array
    {
        return $this->queryTweets("text", "");
    }

    public function getTweetsByUser($limit = null, $order = null, $user = null): array
    {
        return $this->queryTweets("username", $username);
    }

    public function getTweetsBySearch($limit = null, $order = null, $search = ""): array
    {
        //return $this->queryTweets("text", $search);
        return $this->queryNaturalLanguage($search, "tweets", "Tweet");
    }

    public function updateTweets($tweets)
    {
        foreach ($tweets as &$tweet)
        {
            $this->updateTweet($tweet);
        }
    }

    public function updateTweet($tweet)
    {
        try
        {
            $statement = $this->db->prepare("REPLACE INTO tweets (created_at, id, text, hashtags, user, retweet_count, favorite_count, lang)
                VALUES (:created_at, :id, :text, :hashtags, :user, :retweet_count, :favorite_count, :lang)");
            $statement->execute([
                ":created_at" => $tweet->created_at, ":id" => $tweet->id, ":text" => $tweet->text, ":hashtags" => $tweet->hashtags, ":user" => $tweet->user, ":retweet_count" => $tweet->retweet_count, ":favorite_count" => $tweet->favorite_count, ":lang" => $tweet->lang
            ]);
            $db = null; // Close connection
            return true;
          }
          catch (PDOException $exception)
          {
            echo $exception->getMessage();
          }
    }
    
    public function getListPopularUsers($limit = null, $order = null): string
    {
        $table = "twitter";
        $collumn = "tweets";
        try
        {
            $statement = $this->db->prepare("SELECT users.screen_name, count(*) FROM users RIGHT JOIN tweets ON users.screen_name = tweets.user GROUP BY users.screen_name");
            $statement->execute();
            $rows = $statement->fetchAll();    // Load rows in array
            $db = null;     // Close connection

            $result = "var list = [ \n";
            foreach ($rows as $row)
            {
                $result .= " ['".$row[0]."', ".$row[1]."], \n";
            }
            $result .= "];";
        
            return $result;
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function getListPopularTweets($limit = null, $order = null): string
    {
        $table = "twitter";
        $collumn = "tweets";
        try
        {
            $statement = $this->db->prepare("SELECT user, (retweet_count+favorite_count) FROM tweets");
            $statement->execute();
            $rows = $statement->fetchAll();    // Load rows in array
            $db = null;     // Close connection

            $result = "var list = [ \n";
            foreach ($rows as $row)
            {
                $result .= " ['".$row[0]."', ".$row[1]."], \n";
            }
            $result .= "];";
        
            return $result;
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }
 
    public function getUsers($limit = null, $order = null): array
    {
        return $this->queryUsers("id", "");
    }
    
    public function getUsersBySearch($limit = null, $order = null, $search = ""): array
    {
        //return $this->queryUsers("screen_name", $search);
        return $this->queryNaturalLanguage($search, "users", "User");
    }

    public function getTweetsByBooleanSearch($field1 = '', $q1 = '', $operator = '', $field2 = '', $q2 = ''): array
    {
        try
        {
            if($field1 == '')
            {
                $statement = $this->db->prepare("SELECT * FROM tweets");
            }
            else
            {
                $statement = $this->db->prepare("SELECT * FROM tweets WHERE $field1 LIKE :q1 $operator $field2 LIKE :q2");
            }
            $statement->execute([
                ":q1" => '%'.$q1.'%', ":q2" => '%'.$q2.'%'
            ]);

            $rows = $statement->fetchAll(PDO::FETCH_CLASS, 'Tweet');    // Load rows in array
            $db = null;     // Close connection

            return $rows;
        }
        catch (PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function updateUsers($users = null)
    {
        foreach ($users as &$user)
        {
            $this->updateUser($user);
        }
    }
    
    public function updateUser($user)
    {
        try
        {
            $statement = $this->db->prepare("REPLACE INTO users (id, name, screen_name, location, description)
                VALUES (:id, :name, :screen_name, :location, :description)");
            $statement->execute([
                ":id" => $user->id, ":name" => $user->name, ":screen_name" => $user->screen_name, ":location" => $user->location, ":description" => $user->description
            ]);
    
            $db = null; // Close connection
            return true;
          }
          catch (PDOException $exception)
          {
            echo $exception->getMessage();
          }
    }
    
}
