<?php
require 'includes/header.php';
$tweets = $twitterReader->getTweets();
//$tweets = $tweetReader->user("binance");

echo '
<style>
th,td
{
    overflow: hidden;
}
</style>
';


echo '
    <div class="tree-nav-content">
            
    <h3>Tweets</h3>
    <div class="divider"></div>
    <p>Showing rows of tweets.</p>
';

// Print Users in a nice table
echo '<table class="table small striped">';
echo '
    <thead><tr>
    <th>Date</th>
    <th>Id</th>
    <th>Text</th>
    <th>Hashtags</th>
    <th>User</th>
    <th>Retweets</th>
    <th>Favorites</th>
    <th>Lang.</th>
    </tr></thead>

    <tbody style="font-size: 9pt;">';
foreach ($tweets as $tweet) {
    echo $tweet->toRow();
}
echo '
    </tbody>
    </table>';

echo '
                </div>
';
       
require 'includes/footer.php';