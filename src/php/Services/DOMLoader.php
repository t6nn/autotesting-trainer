<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 2.02.2017
 * Time: 0:14
 */

namespace Newsy\Services;


interface DOMLoader
{
    /**
     * @param $url string
     * @return \DOMDocument
     */
    public function loadFromUrl($url);
}