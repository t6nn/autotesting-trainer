<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 2.02.2017
 * Time: 23:57
 */

use function DI\factory;
use function DI\get;
use function DI\object;

$globalConfig = parse_ini_file(__DIR__ . '/../../conf/config.ini');

return [
    \mysqli::class => object()->constructor(
        $globalConfig['db.host'],
        $globalConfig['db.user'],
        $globalConfig['db.pass'],
        $globalConfig['db.name'],
        $globalConfig['db.port'],
        ini_get("mysqli.default_socket")
    )->scope(\DI\Scope::SINGLETON),

    \Newsy\Services\User::class => factory([\Newsy\Services\Impl\SessionUserResolver::class, 'resolve'])->scope(\DI\Scope::SINGLETON),

    \Newsy\Services\UserRatings::class => object(\Newsy\Services\Impl\DatabaseUserRatings::class)->scope(\DI\Scope::SINGLETON),

    \Newsy\Services\NewsSource::class => object(\Newsy\Services\Impl\RssNewsSource::class)->constructor(
        "http://www.pealinn.ee/rss.php?type=news",
        get(\Newsy\Services\User::class),
        get(\Newsy\Services\UserRatings::class)
    )->scope(\DI\Scope::SINGLETON),

    \Newsy\Controllers\LandingPageController::class => object()->scope(\DI\Scope::SINGLETON),

    Twig_Environment::class => factory(function() {
        $twigLoader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/../tpl');
        return new Twig_Environment($twigLoader, [
            //'cache' => dirname(__FILE__).'/../cache'
        ]);
    })->scope(\DI\Scope::SINGLETON)

];