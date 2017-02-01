<?php
namespace Newsy\Services;
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 1.02.2017
 * Time: 22:38
 */
interface NewsSource
{
    /**
     * @param int $count
     * @return array
     */
    public function fetchRandomNews($count = 3);
}