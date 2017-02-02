<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 2.02.2017
 * Time: 0:19
 */

namespace News\Tests\Unit\Services\Impl;


use DOMDocument;
use Newsy\Services\DOMLoader;
use Newsy\Services\Impl\PealinnNewsSource;
use Newsy\Services\Impl\RssNewsSource;
use Newsy\Services\Rating;
use Newsy\Services\User;
use Newsy\Services\UserRatings;
use PHPUnit\Framework\TestCase;

class RssNewsSourceTest extends TestCase
{

    private $userRatings;
    private $domLoader;
    private $source;

    /**
     * @before
     */
    public function createMocksAndSource() {
        $this->userRatings = \Phake::mock(UserRatings::class);
        $this->source = new RssNewsSource(dirname(__FILE__).'/rss_test_input.xml', new User("123"), $this->userRatings, $this->domLoader);
    }

    public function testFetchReturnsRequestedNumberOfNewsItems() {
        \Phake::when($this->userRatings)->fetchAverageRating(\Phake::anyParameters())->thenReturn(new Rating(1.0));
        \Phake::when($this->userRatings)->fetchUserRating(\Phake::anyParameters())->thenReturn(new Rating(2.0));

        $this->assertCount(2, $this->source->fetchRandomNews(2));
    }

}