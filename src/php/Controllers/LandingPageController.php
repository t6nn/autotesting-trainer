<?php

/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 2.02.2017
 * Time: 23:46
 */
namespace Newsy\Controllers;

use Newsy\Services\NewsSource;
use Newsy\Services\Rating;
use Newsy\Services\User;
use Newsy\Services\UserRatings;
use Twig_Environment;
use Twig_Loader_Filesystem;

class LandingPageController
{

    /**
     * @var UserRatings
     */
    private $userRatings;

    /**
     * @var NewsSource
     */
    private $newsSource;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Twig_Environment
     */
    private $twigEnvironment;

    /**
     * LandingPageController constructor.
     * @param UserRatings $userRatings
     * @param NewsSource $newsSource
     * @param User $user
     * @param Twig_Environment $twigEnvironment
     */
    public function __construct(UserRatings $userRatings, NewsSource $newsSource, User $user, Twig_Environment $twigEnvironment)
    {
        $this->userRatings = $userRatings;
        $this->newsSource = $newsSource;
        $this->user = $user;
        $this->twigEnvironment = $twigEnvironment;
    }


    public function handleRequest() {
        if (isset($_POST['rating']) && isset($_POST['url'])) {
            $this->handlePost();
        } else {
            $this->handleGet();
        }
    }

    private function handlePost()
    {
        $this->userRatings->storeUserRating(base64_decode($_POST['url']), $this->user, new Rating($_POST['rating']));
        echo "OK";
    }

    private function handleGet()
    {
        echo $this->twigEnvironment->render('index.twig', [
            'news' => $this->newsSource->fetchRandomNews()
        ]);
    }
}