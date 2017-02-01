<?php
/**
 * Created by IntelliJ IDEA.
 * User: t6nn
 * Date: 1.02.2017
 * Time: 22:43
 */

namespace Newsy\Services;


class User
{
    /**
     * @var string
     */
    private $id;

    /**
     * User constructor.
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}