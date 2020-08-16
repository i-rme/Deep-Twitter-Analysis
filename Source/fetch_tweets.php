<?php

ini_set('memory_limit', '-1');

require_once("./components/Tweet.php");
require_once("./components/TwitterService_api.php");
require_once("./components/TwitterService_json.php");
require_once("./components/TwitterService_sql.php");
require_once("./components/TwitterService_mongodb.php");
require_once("./components/TwitterService_solr.php");

/*
    How to use:
    0. Optional: Create JSON file on \TwitterFinalTask\data\tweets.json
    0. Configure Twitter API key on \TwitterFinalTask\components\TwitterService_api.php
    0. Configure PostgreSQL database login on \TwitterFinalTask\components\config\sql_config.php
    0. Configure MongoDB database string on \TwitterFinalTask\components\TwitterService_mongodb.php
    0. Configure Solr database string on \TwitterFinalTask\components\TwitterService_solr.php

    1. Set $twitterReader to the INPUT method: API or JSON

    2. Set $twitterWriter to the OUTPUT method: SQL, MONGODB or SOLR

    3. Read tweets & users or statuses
        3.1 For SQL use $tweets = $tweetReader->getTweets() and $users  = $tweetReader->getUsers();
        3.2 For MONGODB or SOLR use $statuses = $twitterService_json->getStatuses();

    4. Write tweets & users or statuses
        4.1 For SQL use $tweetWriter->updateTweets($tweets); and $tweetWriter->updateUsers($users);
        4.2 For MONGODB or SOLR use $tweetWriter->updateStatuses($statuses);

    5. Finished, now you can check on the database and use the application.

*/

$twitterService_api       = new TwitterService_api();
$twitterService_json      = new TwitterService_json();
$twitterService_sql       = new TwitterService_sql();
$twitterService_mongodb   = new TwitterService_mongodb();
$twitterService_solr      = new TwitterService_solr();


//$twitterReader  = $twitterService_api;
$twitterReader    = $twitterService_json;


//$twitterWriter  = $TwitterService_sql;
$twitterWriter    = $TwitterService_mongodb;
//$twitterWriter  = $TwitterService_solr;



$tweets = $tweetReader->getTweets();                // Used in postgresql
$users  = $tweetReader->getUsers();                 // Used in postgresql
$statuses = $twitterService_json->getStatuses();    //Used in mongodb and solr


//$tweetWriter->updateTweets($tweets);              // Used in postgresql
//$tweetWriter->updateUsers($users);                // Used in postgresql
$tweetWriter->updateStatuses($statuses);          //Used in mongodb and solr


echo '<h1>Read comments in the PHP file to know how to use it</h1>';

// Print Tweets in a nice table
echo '<table border="1">';
echo '
    <tr>
    <td>Date</td>
    <td>Id</td>
    <td>Text</td>
    <td>Hashtags</td>
    <td>User</td>
    <td>Retweet count</td>
    <td>Favorite count</td>
    <td>Language</td>
    </tr>';
foreach ($tweets as $tweet) {
	echo $tweet->toRow();
}
echo '</table>';


// Print Users in a nice table
echo '<table border="1">';
echo '
    <tr>
    <td>Id</td>
    <td>Name</td>
    <td>Screen Name</td>
    <td>Location</td>
    <td>Description</td>
    </tr>';
foreach ($users as $user) {
	echo $user->toRow();
}
echo '</table>';

