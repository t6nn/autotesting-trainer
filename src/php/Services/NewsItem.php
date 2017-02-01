<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 1.02.2017
 * Time: 22:43
 */

namespace Newsy\Services;


class NewsItem
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * @var Rating
     */
    private $averageRating;

    /**
     * @var Rating
     */
    private $userRating;

    /**
     * @var string
     */
    private $description;

    /**
     * NewsItem constructor.
     * @param string $title
     * @param string $url
     * @param string $description
     */
    public function __construct($title, $url, $description)
    {
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function getHash() {
        return sha1($this->url);
    }

    public function getEncodedUrl() {
        return base64_encode($this->url);
    }

    /**
     * @return Rating
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * @return Rating
     */
    public function getUserRating()
    {
        return $this->userRating;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param Rating $averageRating
     */
    public function setAverageRating($averageRating)
    {
        $this->averageRating = $averageRating;
    }

    /**
     * @param Rating $userRating
     */
    public function setUserRating($userRating)
    {
        $this->userRating = $userRating;
    }

}