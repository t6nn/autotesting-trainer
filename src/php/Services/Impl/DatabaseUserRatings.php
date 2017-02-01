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
     * @param NewsItem $item
     * @return Rating
     */
    public function fetchAverageRating(NewsItem $item)
    {

    }

    /**
     * @param NewsItem $item
     * @param User $user
     * @return Rating
     */
    public function fetchUserRating(NewsItem $item, User $user)
    {
        // TODO: Implement fetchUserRating() method.
    }

    /**
     * @param NewsItem $item
     * @param User $user
     * @param Rating $rating
     * @return mixed
     */
    public function storeUserRating(NewsItem $item, User $user, Rating $rating)
    {
        // TODO: Implement storeUserRating() method.
    }
}