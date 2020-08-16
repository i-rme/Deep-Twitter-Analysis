<?php
require_once("model/iTwitterService.php");
require_once("model/Tweet.php");
require_once("model/User.php");

class TwitterService_json implements iTwitterService
{
    private $statuses;

    public function __construct()
    {
        $data_raw = file_get_contents('./data/tweets.json', true);
        $data_json = json_decode($data_raw, true);
        $data_json = array_reverse($data_json);     // Chronological order
        $this->statuses = $data_json["statuses"];   // Storing final array
    }

    public function getStatuses(): array
    {
        foreach ($this->statuses as &$status)
        {
            $element["created_at"] = $status["created_at"];
            $element["id_str"] = $status["id_str"];
            $element["text"] = str_replace(array("\r", "\n"), '', $status["text"]);
            $element["hashtags"] = json_encode($status["entities"]["hashtags"]);
            $element["screen_name"] = $status["user"]["screen_name"];
            $element["retweet_count"] = $status["retweet_count"];
            $element["favorite_count"] = $status["favorite_count"];
            $element["lang"] = $status["lang"];

            $user["id_str"] = $status["user"]["id_str"];
            $user["name"] = $status["user"]["name"];
            $user["screen_name"] = $status["user"]["screen_name"];
            $user["location"] = $status["user"]["location"];
            $user["description"] = $status["user"]["description"];

            $element["user"] = $user;

            $elements[] = $element;
        }

        return $elements;
    }

    public function getTweets($limit = null, $order = null): array
    {
        foreach ($this->statuses as &$status)
        {
            $tweet = Tweet::construct(
                $status["created_at"],
                $status["id_str"],
                str_replace(array("\r", "\n"), '', $status["text"]),
                json_encode($status["entities"]["hashtags"]),
                $status["user"]["screen_name"],
                $status["retweet_count"],
                $status["favorite_count"],
                $status["lang"]
            );
            $tweets[] = $tweet;
        }
  
        return $tweets;
    }

    public function getTweetsByUser($limit = null, $order = null, $user = null): array
    {
        return [];  // Not supported
    }

    public function getTweetsBySearch($limit = null, $order = null, $search = null): array
    {
        return [];  // Not supported
    }

    public function updateTweets($tweets)
    {
        return null;  // Not supported
    }

    public function updateTweet($tweet)
    {
        return null;  // Not supported
    }
    
    public function getTweetsAggregatedByUser($limit = null, $order = null): string
    {
        return "";  // Not supported
    }
 
    public function getUsers($limit = null, $order = null): array
    {
        foreach ($this->statuses as &$status)
        {
            $user = User::construct(
                $status["user"]["id_str"],
                $status["user"]["name"],
                $status["user"]["screen_name"],
                $status["user"]["location"],
                $status["user"]["description"]
            );
            $users[] = $user;
        }
  
        return $users;
    }
    
    public function getUsersBySearch($limit = null, $order = null, $search = null): array
    {
        return [];  // Not supported
    }

    public function updateUsers($users = null)
    {
        return null;  // Not supported
    }
    
    public function updateUser($user = null)
    {
        return null;  // Not supported
    }

    public function getListPopularUsers($limit = NULL, $order = NULL): string
    {
        return null;  // Not supported
    }

    public function getListPopularTweets($limit = NULL, $order = NULL): string
    {
        return null;  // Not supported
    }
    
}
