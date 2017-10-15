<?php
/**
 * Created by PhpStorm.
 * User: Robbie
 * Date: 12/8/2016
 * Time: 10:27 PM
 */

namespace VGBacklog\Models;


class User
{
    public $userId;
    public $userName;

    public function __construct($userId, $userName)
    {
        $this->userId = $userId;
        $this->userName = $userName;
    }

    function getUserId()
    {
        return $this->userId;
    }

    function getUserName()
    {
        return $this->userName;
    }

    function setUserId($userId)
    {
        $this->userId = $userId;
    }

    function setUserName($userName)
    {
        $this->userName = $userName;
    }
}