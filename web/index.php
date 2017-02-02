<?php
require_once ('../vendor/autoload.php');
session_start();

$builder = new DI\ContainerBuilder();
$builder->addDefinitions('../src/php/config.php');
$container = $builder->build();

$container->get(\Newsy\Controllers\LandingPageController::class)->handleRequest();
?>

<?php

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
        'url_encoded' => base64_encode($url),
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