<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 2.02.2017
 * Time: 21:21
 */

namespace Newsy\Tests\Integration\Services\Impl;


use Newsy\Services\Impl\DatabaseUserRatings;
use Newsy\Services\NewsItem;
use Newsy\Services\Rating;
use Newsy\Services\User;
use Newsy\Tests\Integration\AbstractDatabaseTestCase;

class DatabaseUserRatingsTest extends AbstractDatabaseTestCase
{

    /**
     * @var DatabaseUserRatings
     */
    private $ratings;

    /**
     * @before
     */
    public function setUpRatingsService() {
        $this->ratings = new DatabaseUserRatings(new \mysqli(
            $GLOBALS['DB_HOST'],
            $GLOBALS['DB_USER'],
            $GLOBALS['DB_PASSWD'],
            $GLOBALS['DB_DBNAME'],
            $GLOBALS['DB_PORT']
        ));
    }

    public function testAverageRatingIsCalculatedOverAllRatingsForNewsItem() {
        $item = new NewsItem('title', 'http://test.url', 'description');
        $rating = $this->ratings->fetchAverageRating($item);
        $this->assertEquals(4.5, $rating->getExactValue());
    }

    public function testUserRatingCanBeFetchedForNewsItem() {
        $item = new NewsItem('title', 'http://test.url', 'description');
        $rating = $this->ratings->fetchUserRating($item, new User("2"));
        $this->assertEquals(4, $rating->getExactValue());
    }

    public function testUserRatingCanBeStored() {
        $this->ratings->storeUserRating('http://another.url', new User("5"), new Rating(2));

        $queryTable = $this->getConnection()->createQueryTable(
            'ratings', 'SELECT url,user,rating FROM ratings'
        );

        $this->assertTableContains([
            'url' => 'http://another.url',
            'user' => '5',
            'rating' => 2
        ], $queryTable);
    }

}