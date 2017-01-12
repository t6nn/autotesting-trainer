<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="bootstrap/favicon.ico">

    <title>Newsy</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/newsy.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Newsy the Nosey News Aggregator</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <form class="navbar-form navbar-right">
                <div class="form-group">
                    <input type="text" placeholder="Email" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Sign in</button>
            </form>
        </div><!--/.navbar-collapse -->
    </div>
</nav>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <div class="container">
        <h1>News</h1>
        <p>Rate news and get on with your life.</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
    </div>
</div>

<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <?php
        $news = fetchRandomNews();
        foreach ($news as $item) { ?>
            <div class="col-md-4">
                <h2><?php echo $item['title'] ?></h2>
                <p><?php echo $item['description'] ?></p>
                <p>
                    <a class="btn btn-default" href="<?php echo $item['url'] ?>" role="button">View full
                        story &raquo;</a>
                <fieldset class="rating">
                    <?php
                    for ($num = 5; $num > 0; $num--) {
                        echo ratingInputWithLabel($item, $num);
                    }
                    ?>
                </fieldset>
                </p>
            </div>
        <?php } // endfor ?>
    </div>

    <hr>

    <footer>
        <p>&copy; 2017 t6nn.eu</p>
    </footer>
</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

<?php
function ratingInputWithLabel($item, $num)
{
    $id = "star${num}_${item['hash']}";
    $name = "rating_${item['hash']}";
    $label = $num . ' ' . $num == 1 ? 'star' : 'stars';
    return "<input type=\"radio\" id=\"$id\" name=\"$name\" value=\"$num\"/>
                    <label for=\"$id\">$label</label>";
}

function fetchRandomNews($count = 3)
{
    $url = "http://www.pealinn.ee/rss.php?type=news";
    $xmlDoc = new DOMDocument();
    $xmlDoc->load($url);

    $channel = $xmlDoc->getElementsByTagName('channel')->item(0);
    $news = $channel->getElementsByTagName('item');
    $randomNews = [];
    for ($i = 0; $i < $count; $i++) {
        $num = rand(0, $news->length);
        $item = $news->item($num);
        array_push($randomNews, rssNodeToNewsItem($item));
    }
    return $randomNews;
}

function rssNodeToNewsItem($rssNode)
{
    return [
        'title' => $rssNode->getElementsByTagName('title')->item(0)->textContent,
        'url' => $rssNode->getElementsByTagName('link')->item(0)->textContent,
        'hash' => md5($rssNode->getElementsByTagName('link')->item(0)->textContent),
        'description' => $rssNode->getElementsByTagName('description')->item(0)->textContent
    ];
}