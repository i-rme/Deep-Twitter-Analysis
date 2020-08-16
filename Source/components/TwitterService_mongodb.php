<?php
require_once("model/iTwitterService.php");
require_once("model/Tweet.php");
require_once("model/User.php");

class TwitterService_mongodb implements iTwitterService
{
    private $manager;

    public function __construct()
    {
        $this->manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    }

    public function getTweets($limit = null, $order = null): array
    {
        return $this->queryTweets();
    }

    private function queryTweets($filter = [], $options = []): array
    {
        $query = new MongoDB\Driver\Query($filter, $options);
        $rows = $this->manager->executeQuery('twitter.statuses', $query);
        //$rows = $this->manager->executeQuery('twitter.tweets', $query);
    
        $tweets = array();

        foreach ($rows as $row) {
            $tweet = Tweet::construct(
                $row->created_at,
                $row->id_str,
                $row->text,
                $row->hashtags,
                $row->screen_name,
                $row->retweet_count,
                $row->favorite_count,
                $row->lang
            );
    
          $tweets[] = $tweet;
        }
    
        return $tweets;
    }

    public function getTweetsByUser($limit = 100, $order = null, $user = null): array
    {

        $options =
        [
            'limit' => $limit,
            'sort' => [$order => 1]
        ];

        $filter =
        [
            'user' => $string
        ];
    
        return $this->queryTweets($filter, $options);
    }

    public function getTweetsBySearch($limit = 100, $order = null, $search = null): array
    {
        $options =
        [
            'limit' => $limit,
            'sort' => [$order => 1]
        ];
    
        $filter = 
        [
            'text' => 
            [
                '$regex' => $search
            ]
        ];
        return $this->queryTweets($filter, $options);
    }

    public function updateTweets($tweets)
    {
        $bulk = new MongoDB\Driver\BulkWrite;

        foreach ($tweets as &$tweet)
        {
          $bulk->insert($tweet);
        }
          
        $this->manager->executeBulkWrite('twitter.tweets', $bulk);
    }

    public function updateTweet($tweet)
    {
        return $this->updateTweets($tweet);
    }
    
    public function getListPopularUsers($limit = null, $order = null): string
    {
        $command = new MongoDB\Driver\Command(
        [
            'aggregate' => 'tweets',
            'pipeline' => 
            [
                ['$group' => ['_id' => '$user', 'count' => ['$sum' => 1]]],
                ['$match' => ['count' => ['$gt' => 1] ] ],
            ],
            'cursor' => new stdClass,
        ]);
        $cursor = $this->manager->executeCommand('twitter', $command);
    
        $result = "var list = [ \n";
        foreach ($cursor as $document)
        {
          $result .= " ['$document->_id', $document->count], \n";
            //var_dump($document);
        }
        $result .= "];";
    
        return $result;
    }
 
    public function getListPopularTweets($limit = null, $order = null): string
    {
        return "";
    }

    public function getUsers($limit = null, $order = null): array
    {
        return $this->queryUsers();
    }

    private function queryUsers($filter = [], $options = []): array
    {
        $query = new MongoDB\Driver\Query($filter, $options);
        $rows = $this->manager->executeQuery('twitter.statuses', $query);
        //$rows = $this->manager->executeQuery('twitter.users', $query);

        $users = array();

        foreach ($rows as $row)
        {
            $user = User::construct(
                $row->user->id_str,
                $row->user->name,
                $row->user->screen_name,
                $row->user->location,
                $row->user->description
            );
    
          $users[] = $user;
        }
    
        return $users;
    }
    
    public function getUsersBySearch($limit = 100, $order = 'id', $search = '.*'): array
    {
        $options =
        [
            'limit' => $limit,
            'sort' => [$order => 1],
        ];
    
      $filter = 
        [
            'user.screen_name' => 
            [
                '$regex' => $search
            ]
        ];

         /* $filter = 
        [
            '$text' => 
            [
                '$search' => $search
            ]
        ];*/
        /*return $this->queryUsers($filter, $options);*/
        return $this->queryUsers($filter, $options);
    }

    public function updateUsers($users = null)
    {
        $bulk = new MongoDB\Driver\BulkWrite;

        foreach ($users as &$user)
        {
          $bulk->insert($user);
        }
          
        $this->manager->executeBulkWrite('twitter.users', $bulk);
    }
    
    public function updateUser($user = null)
    {
        return $this->updateUsers($user);
    }
    
    public function updateStatuses($statuses)
    {
        $bulk = new MongoDB\Driver\BulkWrite;

        foreach ($statuses as &$status)
        {
          $bulk->insert($status);
        }
          
        $this->manager->executeBulkWrite('twitter.statuses', $bulk);
    }

    public function demoMapReduce($statuses)
    {
        $map = new MongoCode('function() {
            var total = 0;
            for (count in this.news) {
            total +=  this.tweet[count];
            }
            emit(this._id, {id: this.id, total: total});
        }');

        $reduce = new MongoCode('function(key, values) {
                var result = {id: null, total: 0};
                values.forEach(function(v) {
                result.id = v.id;
                result.total = v.total;
                });
                return result;
        }');
    
        $totals = $this->manager->$db->command(array(
            'mapreduce' => 'statuses', // collection name
            'map' => $map,
            'reduce' => $reduce,
            'query' => array('tweet' => 'user'),
            "out" => "totals" // new collection name
        ));
    }

}
