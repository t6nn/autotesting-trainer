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
use Newsy\Services\Rating;
use Newsy\Services\User;
use Newsy\Services\UserRatings;
use PHPUnit\Framework\TestCase;

class PealinnNewsSourceTest extends TestCase
{

    private static $input;

    private $userRatings;
    private $domLoader;
    private $source;

    /**
     * @before
     */
    public function createMocksAndSource() {
        $this->userRatings = \Phake::mock(UserRatings::class);
        $this->domLoader = \Phake::mock(DOMLoader::class);
        $this->source = new PealinnNewsSource(new User("123"), $this->userRatings, $this->domLoader);
    }

    /**
     * @beforeClass
     */
    public static function createTestInputDocument() {
        self::$input = new DOMDocument();
        self::$input->load(dirname(__FILE__).'/pealinn_test_input.xml');
    }

    public function testFetchReturnsRequestedNumberOfNewsItems() {
        \Phake::when($this->domLoader)->loadFromUrl("http://www.pealinn.ee/rss.php?type=news")->thenReturn(self::$input);
        \Phake::when($this->userRatings)->fetchAverageRating(\Phake::anyParameters())->thenReturn(new Rating(1.0));
        \Phake::when($this->userRatings)->fetchUserRating(\Phake::anyParameters())->thenReturn(new Rating(2.0));

        $this->assertCount(2, $this->source->fetchRandomNews(2));
    }

}