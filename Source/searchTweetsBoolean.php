<?php
require 'includes/header.php';

if(isset($_GET["q1"])){
    $field1 = htmlentities( $_GET["field1"] );
    $q1 = htmlentities( $_GET["q1"] );
    $operator = htmlentities( $_GET["operator"] );
    $field2 = htmlentities( $_GET["field2"] );
    $q2 = htmlentities( $_GET["q2"] );
}else{
    $field1 = '';
    $q1 = '';
    $operator = '';
    $field2 = '';
    $q2 = '';
}

$time_start = microtime(true);

$tweets = $twitterReader->getTweetsByBooleanSearch($field1, $q1, $operator, $field2, $q2);

$time_elapsed = round( (microtime(true) - $time_start) * 1000 );

$q1 = '';
$q2 = '';

echo '
<style>
th,
td {
  overflow: hidden;
}
</style>
';



 echo '
                <div class="tree-nav-content">
                    
                    <h3>Search Tweets Boolean</h3>
                    <div class="divider"></div>

                    <form action="" method="get">

                    <div class="row">

                        <div class="col-1">
                            <select name="field1" class="select" placeholder="Choose one">
                                <option>created_at</option>
                                <option>id</option>
                                <option>text</option>
                                <option>hashtags</option>
                                <option>user</option>
                                <option>retweet_count</option>
                                <option>favorite_count</option>
                                <option>lang</option>
                            </select>
                        </div>

                        <div class="col-2">                        
                            <input style="width: 80%;" name="q1" type="search" class="form-group-input" placeholder="q1" value="'.$q1.'"></input>
                        </div>

                        <div class="col-2">
                            <select name="operator" class="select" placeholder="Choose one">
                                <option>AND</option>
                                <option>OR</option> 
                                <option>NOT</option>
                            </select>
                        </div>

                        <div class="col-1">
                            <select name="field2" class="select" placeholder="Choose one">
                                <option>created_at</option>
                                <option>id</option>
                                <option>text</option>
                                <option>hashtags</option>
                                <option>user</option>
                                <option>retweet_count</option>
                                <option>favorite_count</option>
                                <option>lang</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <input style="width: 80%;" name="q2" type="search" class="form-group-input" placeholder="q2" value="'.$q2.'"></input>
                        </div>

                        <div class="col-2">
                            <button style="display:inline;" class="form-group-btn">Boolean Search</button>           
                        </div>

                        <div class="col-2">
                            <input type="hidden" name="db" value="'.$db.'"></input>
                            <div class="tag tag--info tag--large">Query time: '.$time_elapsed.'ms</div>
                        </div>
                    </div>
                    </form>

';


// Print Tweets in a nice table
echo '<table class="table small striped" id="tableTweets">';
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



echo '
<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>

<script>
var context = document.querySelector("#tableTweets");
var instance = new Mark(context);
instance.mark("'.$q1.'");
</script>

';

require 'includes/footer.php';