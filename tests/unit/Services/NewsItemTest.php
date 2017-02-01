<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 1.02.2017
 * Time: 23:58
 */

namespace Newsy\Tests\Unit\Services;


use Newsy\Services\NewsItem;
use Newsy\Services\Rating;
use PHPUnit\Framework\TestCase;

class NewsItemTest extends TestCase
{

    public function testHashesUrlWithSha1() {
        $newsItem = new NewsItem("title", "http://test.foo.bar", "");
        $this->assertEquals(sha1("http://test.foo.bar"),
            $newsItem->getHash());
    }

    public function testEncodesUrlWithBase64() {
        $newsItem = new NewsItem("title", "http://test.foo.bar", "");
        $this->assertEquals(base64_encode("http://test.foo.bar"),
            $newsItem->getEncodedUrl());
    }
}