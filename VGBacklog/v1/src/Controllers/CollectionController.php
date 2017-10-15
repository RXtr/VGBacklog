<?php
/**
 * Created by PhpStorm.
 * User: Robbie
 * Date: 11/29/2016
 * Time: 9:01 AM
 */

namespace VGBacklog\Controllers;

use VGBacklog\Utilities\DatabaseConnection;
use VGBacklog\Http\StatusCodes;
use VGBacklog\Models\Collection;

class CollectionController
{
    //POST methods
    function addGameToCollection()
    {
        $dbh = DatabaseConnection::getInstance();
        $input = json_decode(file_get_contents('php://input'), true);

        //check userId
        if (isset($input['userId']))
        {
            $userId = strip_tags($input['userId']);

            if (!filter_var($userId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("'userId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("'userId' not found in post JSON body");
        }

        //check gameTitle
        if (isset($input['gameTitle']))
        {
            $gameTitle = strip_tags($input['gameTitle']);
            if(empty($gameTitle))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'gameTitle' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'gameTitle' not found in post JSON body");
        }

        //check genre
        if (isset($input['genre']))
        {
            $genre = strip_tags($input['genre']);
            if(empty($genre))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'genre' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'genre' not found in post JSON body");
        }

        //check platform
        if (isset($input['platform']))
        {
            $platform = strip_tags($input['platform']);
            if(empty($platform))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'platform' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'genre' not found in post JSON body");
        }

        //check publisher
        if (isset($input['publisher']))
        {
            $publisher = strip_tags($input['publisher']);
            if(empty($publisher))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'publisher' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'publisher' not found in post JSON body");
        }

        try
        {
            $Insert = $dbh->prepare("INSERT INTO Collection (userId, gameTitle, genre, platform, publisher) VALUES (:userId, :gameTitle, :genre, :platform, :publisher);");
            $Insert->bindValue(":userId", $userId);
            $Insert->bindValue(":gameTitle", $gameTitle);
            $Insert->bindValue(":genre", $genre);
            $Insert->bindValue(":platform", $platform);
            $Insert->bindValue(":publisher", $publisher);

            $success = $Insert->execute();

            if (!$success)
            {
                throw new PDOException("Could not add new game");
            }
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }
        http_response_code(StatusCodes::CREATED);
    }

    //PUT methods
    function putGameFromCollection()
    {
        $dbh = DatabaseConnection::getInstance();
        $input = json_decode(file_get_contents('php://input'), true);

        //check collectionId
        if (isset($input['collectionId']))
        {
            $collectionId = strip_tags($input['collectionId']);

            if (!filter_var($collectionId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("'collectionId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("'collectionId' not found in post JSON body");
        }

        //check userId
        if (isset($input['userId']))
        {
            $userId = strip_tags($input['userId']);

            if (!filter_var($userId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("'userId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("'userId' not found in post JSON body");
        }

        //check gameTitle
        if (isset($input['gameTitle']))
        {
            $gameTitle = strip_tags($input['gameTitle']);
            if(empty($gameTitle))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'gameTitle' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'gameTitle' not found in post JSON body");
        }

        //check genre
        if (isset($input['genre']))
        {
            $genre = strip_tags($input['genre']);
            if(empty($genre))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'genre' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'genre' not found in post JSON body");
        }

        //check platform
        if (isset($input['platform']))
        {
            $platform = strip_tags($input['platform']);
            if(empty($platform))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'platform' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'platform' not found in post JSON body");
        }

        //check publisher
        if (isset($input['publisher']))
        {
            $publisher = strip_tags($input['publisher']);
            if(empty($publisher))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'publisher' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'publisher' not found in post JSON body");
        }

        try
        {
            $Update = $dbh->prepare("UPDATE Collection SET gameTitle = :gameTitle, genre = :genre, platform = :platform, publisher = :publisher WHERE userId = :userId AND id = :collectionId;");
            $Update->bindValue(":userId", $userId);
            $Update->bindValue(":gameTitle", $gameTitle);
            $Update->bindValue(":genre", $genre);
            $Update->bindValue(":platform", $platform);
            $Update->bindValue(":publisher", $publisher);
            $Update->bindValue(":collectionId",$collectionId);

            $success = $Update->execute();

            if (!$success)
            {
                throw new PDOException("Could not add new game");
            }
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }
        http_response_code(StatusCodes::CREATED);
    }

    //DELETE methods
    function deleteGameFromCollection($args)
    {
        $dbh = DatabaseConnection::getInstance();
        if(isset($args['id']))
        {
            $collectionId = $args['id'];
            if (!filter_var($collectionId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'collectionId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'collectionId' cannot be empty.");
        }

        $delete = $dbh->prepare("DELETE FROM Collection WHERE id = :id");
        $delete->bindParam(':id', $collectionId);

        try
        {
            $delete->execute();
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }
        http_response_code(StatusCodes::OK);
        return "No game matching that ID";
    }

    //GET methods
    function getGameById($args)
    {
        $dbh = DatabaseConnection::getInstance();

        if (!filter_var($args['id'], FILTER_VALIDATE_INT))
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'collectionId' needs to be an int.");
        }
        $collectionId = $args['id'];

        $query = $dbh->prepare("SELECT * FROM Collection WHERE id = :collectionId");
        $query->bindParam(':collectionId', $collectionId);

        try
        {
            $query->execute();
        }
        catch(PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }

        $data = $query->fetch();
        if($data)
        {
            return new Collection($data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            return "No game matching that ID";
        }
    }

    function getCollection($args)
    {
        $dbh = DatabaseConnection::getInstance();
        if(isset($args['id']))
        {
            $userId = $args['id'];
            if (!filter_var($userId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'userId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'userId' cannot be empty");
        }

        $getCollection = $dbh->prepare("SELECT * FROM Collection WHERE userId = :userId;");
        $getCollection->bindParam(':userId', $userId);

        try
        {
            $getCollection->execute();
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }

        $collection = array();

        while($result = $getCollection->fetch())
        {
            $collection[] = new Collection($result[0], $result[1], $result[2], $result[3], $result[4], $result[5]);
        }

        http_response_code(StatusCodes::OK);
        return $collection;
    }

    function getByPlatform($args)
    {
        $dbh = DatabaseConnection::getInstance();

        //check userId
        if(isset($args['id']))
        {
            $userId = $args['id'];
            if (!filter_var($userId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'userId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'userId' cannot be empty");
        }

        //check platform
        if(isset($args['platform']))
        {
            $platform = strip_tags($args['platform']);
            if(empty($platform))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'platform' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'platform' cannot be empty");
        }

        $getCollection = $dbh->prepare("SELECT * FROM Collection WHERE userId = :userId AND platform = :platform;");
        $getCollection->bindParam(':userId', $userId);
        $getCollection->bindParam(':platform', $platform);

        try
        {
            $getCollection->execute();
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }

        $collection = array();

        while($result = $getCollection->fetch())
        {
            $collection[] = new Collection($result[0], $result[1], $result[2], $result[3], $result[4], $result[5]);
        }

        http_response_code(StatusCodes::OK);
        return $collection;
    }

    function getByPublisher($args)
    {
        $dbh = DatabaseConnection::getInstance();

        //check userId
        if(isset($args['id']))
        {
            $userId = $args['id'];
            if (!filter_var($userId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'userId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'userId' cannot be empty");
        }

        //check publisher
        if(isset($args['publisher']))
        {
            $publisher = strip_tags($args['publisher']);
            if(empty($publisher))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'publisher' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'publisher' cannot be empty");
        }

        $getCollection = $dbh->prepare("SELECT * FROM Collection WHERE userId = :userId AND publisher = :publisher;");
        $getCollection->bindParam(':userId', $userId);
        $getCollection->bindParam(':publisher', $publisher);

        try
        {
            $getCollection->execute();
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }

        $collection = array();

        while($result = $getCollection->fetch())
        {
            $collection[] = new Collection($result[0], $result[1], $result[2], $result[3], $result[4], $result[5]);
        }

        http_response_code(StatusCodes::OK);
        return $collection;
    }

    function getByGenre($args)
    {
        $dbh = DatabaseConnection::getInstance();

        //check userId
        if(isset($args['id']))
        {
            $userId = $args['id'];
            if (!filter_var($userId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'userId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'userId' cannot be empty");
        }

        //check genre
        if(isset($args['genre']))
        {
            $genre = strip_tags($args['genre']);
            if(empty($genre))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'genre' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'genre' cannot be empty");
        }


        $getCollection = $dbh->prepare("SELECT * FROM Collection WHERE userId = :userId AND genre = :genre;");
        $getCollection->bindParam(':userId', $userId);
        $getCollection->bindParam(':genre', $genre);

        try
        {
            $getCollection->execute();
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }

        $collection = array();

        while($result = $getCollection->fetch())
        {
            $collection[] = new Collection($result[0], $result[1], $result[2], $result[3], $result[4], $result[5]);
        }

        http_response_code(StatusCodes::OK);
        return $collection;
    }


}