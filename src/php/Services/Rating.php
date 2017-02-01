<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 1.02.2017
 * Time: 23:45
 */

namespace Newsy\Services;


class Rating
{

    /**
     * @var float
     */
    private $exactValue;

    /**
     * Rating constructor.
     * @param float $exactValue
     */
    public function __construct($exactValue)
    {
        $this->exactValue = $exactValue;
    }

    /**
     * @return float
     */
    public function getExactValue()
    {
        return $this->exactValue;
    }

    /**
     * @return int
     */
    public function getRoundedValue() {
        return round($this->exactValue);
    }

    public static function none() {
        return new Rating(0);
    }
}