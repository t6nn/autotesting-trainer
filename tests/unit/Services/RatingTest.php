<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 2.02.2017
 * Time: 0:08
 */

namespace Newsy\Tests\Unit\Services;


use Newsy\Services\Rating;
use PHPUnit\Framework\TestCase;

class RatingTest extends TestCase
{

    public function testEmptyRatingIsEqualToZero() {
        $this->assertEquals(0.0, Rating::none()->getExactValue());
        $this->assertEquals(0, Rating::none()->getRoundedValue());
    }

    public function testRatingIsRoundedUpToNearestInteger() {
        $this->assertEquals(4, (new Rating(4.4))->getRoundedValue());
        $this->assertEquals(5, (new Rating(4.5))->getRoundedValue());
    }
}