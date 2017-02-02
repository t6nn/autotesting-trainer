<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 3.02.2017
 * Time: 0:05
 */

namespace Newsy\Services\Impl;


use Newsy\Services\User;

class SessionUserResolver
{

    public static function resolve() {
        if (!isset($_SESSION['userid'])) {
            $_SESSION['userid'] = uniqid(true) . uniqid(true);
        }
        return new User($_SESSION['userid']);
    }
}