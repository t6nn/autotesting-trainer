<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 1.02.2017
 * Time: 22:40
 */

namespace Newsy\Services\Impl;

use Newsy\Services\DOMLoader;
use Newsy\Services\NewsItem;
use Newsy\Services\NewsSource;
use Newsy\Services\User;
use Newsy\Services\UserRatings;

class RssNewsSource implements NewsSource
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var User
     */
    private $currentUser;

    /**
     * @var UserRatings
     */
    private $userRatings;

    /**
     * PealinnNewsSource constructor.
     * @param string $url
     * @param User $currentUser
     * @param UserRatings $userRatings
     */
    public function __construct($url, User $currentUser, UserRatings $userRatings)
    {
        $this->url = $url;
        $this->currentUser = $currentUser;
        $this->userRatings = $userRatings;
    }

    public function fetchRandomNews($count = 3)
    {
        $xmlDoc = new \DOMDocument();
        $xmlDoc->load($this->url);
        $channel = $xmlDoc->getElementsByTagName('channel')->item(0);
        $news = $channel->getElementsByTagName('item');
        $randomNews = [];
        for ($i = 0; $i < $count; $i++) {
            $num = rand(0, $news->length - 1);
            $num = $i;
            $item = $news->item($num);
            array_push($randomNews, $this->rssNodeToNewsItem($item));
        }
        return $randomNews;
    }

    private function rssNodeToNewsItem($rssNode)
    {
        $url = $rssNode->getElementsByTagName('link')->item(0)->textContent;

        $newsItem = new NewsItem(
            $rssNode->getElementsByTagName('title')->item(0)->textContent,
            $url,
            $rssNode->getElementsByTagName('description')->item(0)->textContent
        );
        $newsItem->setAverageRating($this->userRatings->fetchAverageRating($newsItem));
        $newsItem->setUserRating($this->userRatings->fetchUserRating($newsItem, $this->currentUser));

        return $newsItem;
    }
}