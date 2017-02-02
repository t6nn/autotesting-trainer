<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 1.02.2017
 * Time: 23:51
 */

namespace Newsy\Services\Impl;


use Newsy\Services\NewsItem;
use Newsy\Services\Rating;
use Newsy\Services\User;
use Newsy\Services\UserRatings;

class DatabaseUserRatings implements UserRatings
{

    /**
     * @var \mysqli
     */
    private $conn;

    /**
     * DatabaseUserRatings constructor.
     * @param \mysqli $conn
     */
    public function __construct(\mysqli $conn)
    {
        $this->conn = $conn;
    }


    /**
     * @param NewsItem $item
     * @return Rating
     */
    public function fetchAverageRating(NewsItem $item)
    {
        $average = 0;
        if ($stmt = $this->conn->prepare('SELECT AVG(rating) FROM ratings WHERE url = ?')) {
            $stmt->bind_param('s', $item->getUrl());
            $stmt->execute();
            $stmt->bind_result($average);
            $stmt->fetch();
            $stmt->close();
        }
        return new Rating($average);
    }

    /**
     * @param NewsItem $item
     * @param User $user
     * @return Rating
     */
    public function fetchUserRating(NewsItem $item, User $user)
    {
        $rating = -1;
        if ($stmt = $this->conn->prepare('SELECT rating FROM ratings WHERE url = ? AND user = ?')) {
            $stmt->bind_param('ss', $item->getUrl(), $user->getId());
            $stmt->execute();
            $stmt->bind_result($rating);
            $stmt->fetch();
            $stmt->close();
        }
        return new Rating($rating);
    }

    /**
     * @param string $url
     * @param User $user
     * @param Rating $rating
     * @return mixed
     */
    public function storeUserRating($url, User $user, Rating $rating)
    {
        if ($stmt = $this->conn->prepare('INSERT INTO ratings (rating, url, user) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating), rated = CURRENT_TIMESTAMP')) {
            $stmt->bind_param('iss', $rating->getExactValue(), $url, $user->getId());
            $stmt->execute();
            $stmt->close();
        }

    }
}