<?php
require_once ('../vendor/autoload.php');
session_start();
if (isset($_POST['rating']) && isset($_POST['url'])) {
    saveRating(base64_decode($_POST['url']), $_POST['rating']);
    echo "OK";
    exit();
}
?>
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
            <!--/.navbar-collapse -->
        </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
        <div class="container">
            <h1>News</h1>
            <p>Rate news and get on with your life.</p>
        </div>
    </div>

    <div class="container">
        <!-- Example row of columns -->
        <div class="row">
            <?php
            $news = fetchRandomNews();
            foreach ($news as $item) {
                $ratingClass = "rated-".round($item['avg_rating']); ?>
                <div class="col-md-4 news-item <?php echo($ratingClass); ?>">
                    <h2 class=""><?php echo $item['title'] ?></h2>
                    <p><?php echo $item['description'] ?></p>
                    <p>
                        <a class="btn btn-default" href="<?php echo $item['url'] ?>" role="button">View full
                            story &raquo;</a>
                    </p>
                    <div>
                        <fieldset class="rating">
                            <?php
                            for ($num = 5; $num > 0; $num--) {
                                echo ratingInputWithLabel($item, $num);
                            }
                            ?>
                            <input type="hidden" name="url" value="<?php echo base64_encode($item['url']); ?>" ?>
                        </fieldset>
                    </div>
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
    <script src="js/newsy.js"></script>
    </body>
    </html>

<?php
function ratingInputWithLabel($item, $rating)
{
    $id = "star${rating}_${item['hash']}";
    $name = "rating_${item['hash']}";
    $label = $rating . ' ' . $rating == 1 ? 'star' : 'stars';
    $selected = ($rating == $item['user_rating']) ? 'checked="checked"' : '';
    return "<input type=\"radio\" id=\"$id\" name=\"$name\" value=\"$rating\" $selected/>
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
        //$num = $i;
        $item = $news->item($num);
        array_push($randomNews, rssNodeToNewsItem($item));
    }
    return $randomNews;
}

function identifyUser()
{
    if (!isset($_SESSION['userid'])) {
        $_SESSION['userid'] = uniqid(true) . uniqid(true);
    }
    return $_SESSION['userid'];
}

function rssNodeToNewsItem($rssNode)
{
    $url = $rssNode->getElementsByTagName('link')->item(0)->textContent;
    return [
        'title' => $rssNode->getElementsByTagName('title')->item(0)->textContent,
        'url' => $url,
        'avg_rating' => fetchAverageRating($url),
        'user_rating' => fetchUserRating($url, identifyUser()),
        'hash' => sha1($url),
        'description' => $rssNode->getElementsByTagName('description')->item(0)->textContent
    ];
}

function fetchUserRating($url, $user)
{
    $conn = createConnection();
    $rating = -1;

    if ($stmt = $conn->prepare('SELECT rating FROM ratings WHERE url = ? AND user = ?')) {
        $stmt->bind_param('ss', $url, $user);
        $stmt->execute();
        $stmt->bind_result($rating);
        $stmt->fetch();
        $stmt->close();
    }
    $conn->close();
    return $rating;
}

function fetchAverageRating($url)
{
    $conn = createConnection();
    $average = 0;

    if ($stmt = $conn->prepare('SELECT AVG(rating) FROM ratings WHERE url = ?')) {
        $stmt->bind_param('s', $url);
        $stmt->execute();
        $stmt->bind_result($average);
        $stmt->fetch();
        $stmt->close();
    }
    $conn->close();
    return $average;
}

function createConnection()
{
    $config = parse_ini_file('../conf/config.ini');
    $conn = new mysqli($config['db.host'], $config['db.user'], $config['db.pass'], $config['db.name']);
    return $conn;
}

function saveRating($url, $rating)
{
    $conn = createConnection();
    if ($stmt = $conn->prepare('INSERT INTO ratings (rating, url, user) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating), rated = CURRENT_TIMESTAMP')) {
        $stmt->bind_param('iss', $rating, $url, identifyUser());
        $stmt->execute();
        $stmt->close();
    }
    $conn->close();
}