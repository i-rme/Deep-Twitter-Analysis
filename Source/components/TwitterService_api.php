<?php
require_once('dependencies/TwitterAPIExchange.php');
require_once("model/iTwitterService.php");
require_once("model/Tweet.php");
require_once("model/User.php");

class TwitterService_api implements iTwitterService
{
    private $statuses;

    public function __construct()
    {
        $settings = array(
            'oauth_access_token'        => "[REDACTED]",
            'oauth_access_token_secret' => "[REDACTED]",
            'consumer_key'              => "[REDACTED]",
            'consumer_secret'           => "[REDACTED]"
        );

        try
        {
            $url = 'https://api.twitter.com/1.1/search/tweets.json';
            $getfield = 'q=bitcoin&lang=en&result_type=popular&count=2000';  //since_id & max_id to iterate
            $requestMethod = 'GET';
            $twitter = new TwitterAPIExchange($settings);
            $data_raw = $twitter->setGetfield($getfield)
                         ->buildOauth($url, $requestMethod)
                         ->performRequest();
      
            $data_json = json_decode($data_raw, true);
            $data_json = array_reverse($data_json);     // Chronological order
            $this->statuses = $data_json["statuses"];   // Storing final array
        }
        catch (Exception $exception)
        {
            echo $exception->getMessage();
        }
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
    
}
