<?php

if(isset($_GET["db"]))
{
    $db = $_GET["db"];
}else{
    $db = "sql";
}

switch ($db) {
    case 'sql':
        include 'components/TwitterService_sql.php';
        $twitterReader = new TwitterService_sql();
        break;
    case 'mongodb':
        include 'components/TwitterService_mongodb.php';
        $twitterReader = new TwitterService_mongodb();
        break;
    case 'solr':
        include 'components/TwitterService_solr.php';
        $twitterReader = new TwitterService_solr();
        break;
    default:
        die("Wrong db");
}

echo '<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        
        <link href="./css/cirrus.min.css" type="text/css" rel="stylesheet" />
        <link href="./css/fontawesome.css" type="text/css" rel="stylesheet" />
        <link href="./css/app.css" type="text/css" rel="stylesheet" />

        <link href="./css/custom.css" type="text/css" rel="stylesheet" />

        <link rel="icon" type="image/vnd.microsoft.icon" href="./favicon.ico">
        <link rel="Shortcut Icon" href="./favicon.ico" type="image/x-icon" />

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,600,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
        
        <script src="./js/jquery-2.2.4.min.js"></script>
        <script src="./js/wordcloud2.js"></script>

        <title>Deep Twitter Analysis - Raúl Martínez</title>

        <style>
            @media screen and (max-width: 768px) {
                .divider {
                    display: none;
                }
            }
        </style>
        
    </head>

     <body>
        
        <div class="tree-nav-body">
            <div class="tree-nav-header">
                <a href="#sidebar" class="u-hide-tablet">
                    <span class="icon">
                        <i class="fa fa-bars"></i>
                    </span>
                </a>
            </div>

            <div class="tree-nav" style="width: 370px; background: #f7f7f7; padding: 2rem 1rem 2rem 1rem;" id="sidebar">
                <div class="content" style="width: 100%;">
                    <h6 class="title" style="text-align: center;">Deep <span class="tag tag--info text-light">Twitter</span> Analysis</h6>
                </div>
                <div class="tree-nav-container">

                <p class="u-text-center"><i>Powered by PostgreSQL, MongoDB and Solr databases.</i></p>

                <ul class="menu">

                    <li class="menu-item selected">
                        <div class="menu-addon"> <span class="icon"><i class="fa fa-wrapper fa-file"></i></span> </div>
                        <a href="./?db='.$db.'">Index</a>
                        <ul class="menu">
                            <li class="menu-item"> <a href="./fetch_tweets.php">Data adquisiton</a> </li>
                        </ul>
                    </li>

                    <li class="menu-item selected">
                        <div class="menu-addon"> <span class="icon"><i class="fa fa-wrapper fa-comments"></i></span> </div>
                        <a href="./tweets.php?db='.$db.'">Tweets</a>
                        <ul class="menu">
                            <li class="menu-item"> <a href="./searchTweets.php?db='.$db.'">Search Tweets</a> </li>
                            <li class="menu-item"> <a href="./searchTweetsBoolean.php?db='.$db.'">Boolean Search Tweets</a> </li>
                            <li class="menu-item"> <a href="./wordcloud.php?db='.$db.'&type=tweets">Tweets\' Cloud</a> </li>
                        </ul>
                    </li>

                    <li class="menu-item selected">
                        <div class="menu-addon"> <span class="icon"><i class="fa fa-wrapper fa-user"></i></span> </div>
                        <a href="./users.php?db='.$db.'">Users</a>
                        <ul class="menu">
                            <li class="menu-item"> <a href="./searchUsers.php?db='.$db.'">Search Users</a> </li>
                            <li class="menu-item"> <a href="./wordcloud.php?db='.$db.'&type=users">User\'s Cloud</a> </li>
                        </ul>
                    </li>

                </ul>


                <br/>
                <p><b>Select database:</b></p>

                <script>
                function updateURLParameter(url, param, paramVal){
				    var newAdditionalURL = "";
				    var tempArray = url.split("?");
				    var baseURL = tempArray[0];
				    var additionalURL = tempArray[1];
				    var temp = "";
				    if (additionalURL) {
				        tempArray = additionalURL.split("&");
				        for (var i=0; i<tempArray.length; i++){
				            if(tempArray[i].split(\'=\')[0] != param){
				                newAdditionalURL += temp + tempArray[i];
				                temp = "&";
				            }
				        }
				    }

				    var rows_txt = temp + "" + param + "=" + paramVal;
				    return baseURL + "?" + newAdditionalURL + rows_txt;
				}
                </script>

                <div class="form-ext-control form-ext-radio">
                    <input id="radio-1a" name="customRadio1" class="form-ext-input" type="radio" '.($db == 'sql' ? 'checked' : '').' onclick="location.replace( updateURLParameter(window.location.href, \'db\', \'sql\') );">
                    <label class="form-ext-label" for="radio-1a">PostgreSQL</label>

                </div>
                <div class="form-ext-control form-ext-radio">
                    <input id="radio-2a" name="customRadio1" class="form-ext-input" type="radio" '.($db == 'mongodb' ? 'checked' : '').' onclick="location.replace( updateURLParameter(window.location.href, \'db\', \'mongodb\') );">
                    <label class="form-ext-label" for="radio-2a">MongoDB</label>

                </div>
                <div class="form-ext-control form-ext-radio">
                    <input id="radio-3a" name="customRadio1" class="form-ext-input" type="radio" '.($db == 'solr' ? 'checked' : '').' onclick="location.replace( updateURLParameter(window.location.href, \'db\', \'solr\') );">
                    <label class="form-ext-label" for="radio-3a">Solr</label>

                </div>


                </div>
            </div>
            <a href="#sidebar-close" id="sidebar-close" class="tree-nav-close"></a>
';