<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 2.02.2017
 * Time: 0:16
 */

namespace Newsy\Services\Impl;


use Newsy\Services\DOMLoader;

class StandardLibraryDOMLoader implements DOMLoader
{

    /**
     * @param $url string
     * @return \DOMDocument
     */
    public function loadFromUrl($url)
    {
        $doc = new \DOMDocument();
        $doc->load($url);
        return $doc;
    }
}