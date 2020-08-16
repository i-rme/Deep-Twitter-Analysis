<?php
require 'includes/header.php';

if(isset($_GET["type"]))
{
    $type = $_GET["type"];
}else{
    $type = "users";
}

if (isset($_GET['type'])) {
    switch ($_GET['type']) {
        case 'tweets':
            $result = $twitterReader->getListPopularTweets();
            break;
        case 'users':
        default:
            $result = $twitterReader->getListPopularUsers();
            break;
    }
}else{
    $result = $twitterReader->getListPopularUsers();
}



 echo '
                <div class="tree-nav-content">
                    <h3>Wordcloud</h3>
                    <div class="divider"></div>
                    <p>Displayed below:</p>



                <div class="span12" id="canvas-container">
                    <canvas id="canvas" class="canvas" width="900" height="400"></canvas>
                </div>

                <script>
                    '.$result.'
                    var total = 0;
                    list.forEach(element => (total += element[1]));
                    var weight = 1200 / total;
                    WordCloud(document.getElementById(\'canvas\'), { list: list, backgroundColor: \'#f7f7f7\', weightFactor: weight, shrinkToFit: true} );
                </script>



                </div>
';

require 'includes/footer.php';