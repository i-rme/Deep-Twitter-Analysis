<?php
require_once("model/iTwitterService.php");
require_once("model/Tweet.php");
require_once("model/User.php");

class TwitterService_solr implements iTwitterService
{
    private $endpoint;
    private $collection;

    public function __construct()
    {
        // Server Settings
        $hostname = 'localhost';
        $port = '8983';
        $this->endpoint = 'http://'.$hostname.':'.$port;

        // Query Settings
        $this->collection = 'twitter';
    }

    public function getTweets($limit = null, $order = null): array
    {
        // Query Settings
        $action = 'select';
        $q = '*:*';				//Query
        $wt = 'json';			//writer type, ouput format
        $rows = 1000;

        // Building query
        $q = urlencode($q);
        $url = $this->endpoint . '/solr/' . $this->collection . '/' . $action . '?' . 'q=' . $q . '&wt=' . $wt . '&rows=' . $rows ;

        // Call the API
        $json = file_get_contents($url);
        $response = json_decode($json, true);
        $rows = $response['response']['docs'];

        // Print
        //print_r($response);
        //Picking different fields from the query result
        $tweets = array();

        foreach ($rows as $row) {
            $tweet = Tweet::construct(
                $row['created_at'][0],
                $row['id'],
                $row['text'][0],
                $row['hashtags'][0],
                $row['screen_name'][0],
                $row['retweet_count'][0],
                $row['favorite_count'][0],
                $row['lang'][0]
            );
    
          $tweets[] = $tweet;
        }
    
        return $tweets;
    }

    public function getTweetsByUser($limit = null, $order = null, $user = null): array
    {
        return [];  // Not supported
    }

    public function getTweetsBySearch($limit = null, $order = null, $search = ''): array
    {
        // Query Settings
        $action = 'select';
        $q = 'text:'.$search.'*';	//Query
        $wt = 'json';			//writer type, ouput format
        $rows = 1000;

        // Building query
        $q = urlencode($q);
        $url = $this->endpoint . '/solr/' . $this->collection . '/' . $action . '?' . 'q=' . $q . '&wt=' . $wt . '&rows=' . $rows ;

        // Call the API
        $json = file_get_contents($url);
        $response = json_decode($json, true);
        $rows = $response['response']['docs'];

        // Print
        //print_r($response);

        $tweets = array();

        foreach ($rows as $row) {
            $tweet = Tweet::construct(
                $row['created_at'][0],
                $row['id'],
                $row['text'][0],
                $row['hashtags'][0],
                $row['screen_name'][0],
                $row['retweet_count'][0],
                $row['favorite_count'][0],
                $row['lang'][0]
            );
    
          $tweets[] = $tweet;
        }
    
        return $tweets;
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
        // Query Settings
        $action = 'select';
        $q = '*:*';				//Query
        $wt = 'json';			//writer type, ouput format
        $rows = 1000;

        // Building query
        $q = urlencode($q);
        $url = $this->endpoint . '/solr/' . $this->collection . '/' . $action . '?' . 'q=' . $q . '&wt=' . $wt . '&rows=' . $rows ;

        // Call the API
        $json = file_get_contents($url);
        $response = json_decode($json, true);
        $rows = $response['response']['docs'];

        // Print
        //print_r($response);

        $users = array();

        foreach ($rows as $row) {

            if(!isset($row['user_location'][0]))
            {
                $row['user_location'][0] = "null";
            }
            if(!isset($row['user_description'][0]))
            {
                $row['user_description'][0] = "null";
            }

            $user = User::construct(
                $row['id'],
                $row['user_name'][0],
                $row['user_screen_name'][0],
                $row['user_location'][0],
                $row['user_description'][0]
            );
    
          $users[] = $user;
        }
    
        return $users;
    }
    
    public function getUsersBySearch($limit = null, $order = null, $search = ''): array
    {
        // Query Settings
        $action = 'select';
        $q = 'user_description:'.$search.'*';	//Query
        $wt = 'json';			//writer type, ouput format
        $rows = 1000;

        // Building query
        $q = urlencode($q);
        $url = $this->endpoint . '/solr/' . $this->collection . '/' . $action . '?' . 'q=' . $q . '&wt=' . $wt . '&rows=' . $rows ;

        // Call the API
        $json = file_get_contents($url);
        $response = json_decode($json, true);
        $rows = $response['response']['docs'];

        // Print
        //print_r($response);

        $users = array();

        foreach ($rows as $row) {

            if(!isset($row['user_location'][0]))
            {
                $row['user_location'][0] = "null";
            }
            if(!isset($row['user_description'][0]))
            {
                $row['user_description'][0] = "null";
            }

            $user = User::construct(
                $row['id'],
                $row['user_name'][0],
                $row['user_screen_name'][0],
                $row['user_location'][0],
                $row['user_description'][0]
            );
    
          $users[] = $user;
        }
    
        return $users;
    }

    public function updateUsers($users = null)
    {
        return null;  // Not supported
    }
    
    public function updateUser($user = null)
    {
        return null;  // Not supported
    }

    public function getListPopularUsers($limit = null, $order = null): string
    {
    // Query Settings
    $action = 'select';
    $q = '*:*';	    //Query
    $wt = 'json';			//writer type, ouput format
    $rows = 1000;
    $facet_field = 'screen_name';

    // Building query
    $q = urlencode($q);
    $url = $this->endpoint . '/solr/' . $this->collection . '/' . $action . '?' .'facet.field= ' . $facet_field . '&facet=on'. '&q=' . $q . '&wt=' . $wt . '&rows=' . $rows ;

    // Call the API
    $json = file_get_contents($url);
    $response = json_decode($json, true);
    $rows = $response['response']['docs'];

    // Print
    //print_r($response);

    $tweets = array();

    foreach ($rows as $row) {
        $tweet = Tweet::construct(
            $row['created_at'][0],
            $row['id'],
            $row['text'][0],
            $row['hashtags'][0],
            $row['screen_name'][0],
            $row['retweet_count'][0],
            $row['favorite_count'][0],
            $row['lang'][0]
        );

    $tweets[] = $tweet;
    }

    return $tweets;
    }
    
    public function getListPopularTweets($limit = null, $order = null): string
    {
        return null;  // Not supported
    }
    
    public function updateStatuses($statuses)
    {
        $xml = '<add>
        ';

        foreach ($statuses as &$status)
        {
        	$xml .= '<doc>
        	';
			foreach ($status as $clave => $valor)
			{
				if( isset( $valor ) )
				{
					$content = $valor;
				}
                else
                {
					$content = 'null';
				}

                if( is_array($content) )
                {
                    foreach ($content as $uclave => $uvalor)
                    {
                        $xml .= '<field name="user_'.$uclave.'">'.htmlspecialchars($uvalor).'</field>
                        ';
                    }
                }
                else
                {
                    $xml .= '<field name="'.$clave.'">'.htmlspecialchars($content).'</field>
                    ';
                }

			}
			$xml .= '</doc>
			';
        }

        $xml .= '</add>
        ';

        //echo $xml;

        // Query Settings
        $action = 'update';

        // Building query
        $url = $this->endpoint . '/solr/' . $this->collection . '/' . $action ;


        // POSTing to SOLR API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $xml ); 
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml')); 

        $result=curl_exec ($ch);

        //print_r($result);
        
        

    }
}
