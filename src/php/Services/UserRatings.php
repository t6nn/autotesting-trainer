<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 1.02.2017
 * Time: 22:42
 */

namespace Newsy\Services;

interface UserRatings
{
    /**
     * @param NewsItem $item
     * @return Rating
     */
    public function fetchAverageRating(NewsItem $item);

    /**
     * @param NewsItem $item
     * @param User $user
     * @return Rating
     */
    public function fetchUserRating(NewsItem $item, User $user);

    /**
     * @param NewsItem $item
     * @param User $user
     * @param Rating $rating
     * @return mixed
     */
    public function storeUserRating(NewsItem $item, User $user, Rating $rating);
}