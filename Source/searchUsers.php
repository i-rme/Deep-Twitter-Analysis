<?php
require 'includes/header.php';

if(isset($_GET["q"])){
    $searchTerm = htmlentities( $_GET["q"] );
}else{
    $searchTerm = '';
}

if(isset($_GET["order"])){

    switch ($_GET["order"]) {
        case "Id":
            $orderBy = 'id';
            break;
        case "Name":
            $orderBy = 'name';
            break;
        case "Screen Name":
            $orderBy = 'screen_name';
            break;
        case "Location":
            $orderBy = 'location';
            break;
        case "Description":
            $orderBy = 'description';
            break;
        default:
           $orderBy = 'id';
    }

}else{

    $orderBy = 'id';

}

$time_start = microtime(true);

$users = $twitterReader->getUsersBySearch(100, $orderBy, $searchTerm);
//$tweets = $tweetReader->user("binance");

$time_elapsed = round( (microtime(true) - $time_start) * 1000 );


if($searchTerm == ''){
    $inputClass = '';
}elseif(sizeof($users) == 0){
    $inputClass = 'text-danger input-error';
}else{
    $inputClass = 'text-success input-success';
}


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
                    
                    <h3>Search Users</h3>
                    <div class="divider"></div>

                    <form action="" method="get">

                    <div class="row">
                        <div class="col-5">
                            <input style="width: 80%;" name="q" type="search" class="form-group-input '.$inputClass.'" placeholder="Search" value="'.$searchTerm.'"></input>
                            <button style="display:inline;" class="form-group-btn">Go</button>
                        </div>
                        <div class="col-5">
                            <div class="col-2"><p>Order by:</p></div>
                            <div class="col-4">
                                <select name="order" class="select" placeholder="Choose one">
                                    <option>Id</option>
                                    <option>Name</option>
                                    <option>Screen Name</option>
                                    <option>Location</option>
                                    <option>Description</option>
                                </select>
                            </div>
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
    <th>Id</th>
    <th>Name</th>
    <th>S. Name</th>
    <th>Location</th>
    <th>Description</th>
    </tr></thead>

    <tbody style="font-size: 9pt;">';
foreach ($users as $user) {
    echo $user->toRow();
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
instance.mark("'.$searchTerm.'");
</script>

';

require 'includes/footer.php';