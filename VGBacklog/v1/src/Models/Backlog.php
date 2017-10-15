<?php
/**
 * Created by PhpStorm.
 * User: Robbie
 * Date: 11/29/2016
 * Time: 9:02 AM
 */

namespace VGBacklog\Models;


class Backlog
{
    public $backlogId;
    public $userId;
    public $name;
    public $dateModified;

    public function __construct($backlogId, $userId, $gameTitle, $dateModified)
    {
        $this->backlogId = $backlogId;
        $this->userId = $userId;
        $this->gameTitle = $gameTitle;
        $this->dateModified = $dateModified;
    }

    public function getBacklogId()
    {
        return $this->backlogId;
    }

    public function getGameTitle()
    {
        return $this->gameTitle;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getDateModified()
    {
        return $this->dateModified;
    }

    public function setBacklogId($backlogId)
    {
        $this->backlogId = $backlogId;
    }

    public function setGameTitle($gameTitle)
    {
        $this->gameTitle = $gameTitle;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;
    }
}