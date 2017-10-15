<?php
/**
 * Created by PhpStorm.
 * User: Robbie
 * Date: 12/4/2016
 * Time: 2:43 PM
 */

namespace VGBacklog\Models;


class Collection
{
    public $gameId;
    public $userId;
    public $gameTitle;
    public $genre;
    public $platform;
    public $publisher;

    public function __construct($gameId, $userId, $gameTitle, $genre, $platform, $publisher)
    {
        $this->gameId = $gameId;
        $this->userId = $userId;
        $this->gameTitle = $gameTitle;
        $this->genre = $genre;
        $this->platform = $platform;
        $this->publisher = $publisher;
    }

    public function getGameId()
    {
        return $this->gameId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getGameTitle()
    {
        return $this->gameTitle;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function getPlatform()
    {
        return $this->gameTitle;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }

    public function setGameId($gameId)
    {
        $this->gameId = $gameId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setGameTitle()
    {
        return $this->gameTitle;
    }

    public function setGenre()
    {
        return $this->genre;
    }

    public function setPlatform()
    {
        return $this->platform;
    }

    public function setPublisher()
    {
        return $this->publisher;
    }
}