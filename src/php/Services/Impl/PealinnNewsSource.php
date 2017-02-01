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

class PealinnNewsSource implements NewsSource
{

    /**
     * @var User
     */
    private $currentUser;

    /**
     * @var UserRatings
     */
    private $userRatings;

    /**
     * @var DOMLoader
     */
    private $domLoader;

    /**
     * PealinnNewsSource constructor.
     * @param User $currentUser
     * @param UserRatings $userRatings
     * @param DOMLoader $domLoader
     */
    public function __construct(User $currentUser, UserRatings $userRatings, DOMLoader $domLoader)
    {
        $this->currentUser = $currentUser;
        $this->userRatings = $userRatings;
        $this->domLoader = $domLoader;
    }

    public function fetchRandomNews($count = 3)
    {
        $url = "http://www.pealinn.ee/rss.php?type=news";

        $xmlDoc = $this->domLoader->loadFromUrl($url);
        $channel = $xmlDoc->getElementsByTagName('channel')->item(0);
        $news = $channel->getElementsByTagName('item');
        $randomNews = [];
        for ($i = 0; $i < $count; $i++) {
            $num = rand(0, $news->length - 1);
            //$num = $i;
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