<?php
/**
 * Created by PhpStorm.
 * User: Robbie
 * Date: 11/29/2016
 * Time: 8:59 AM
 */

namespace VGBacklog\Controllers;

use VGBacklog\Utilities\DatabaseConnection;
use VGBacklog\Http\StatusCodes;
use VGBacklog\Models\Backlog;

class BacklogController
{
    //POST methods
    function addGameToBacklog()
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

        try
        {
            $dateModified = date("Y-m-d H:i:s");
            $Insert = $dbh->prepare("INSERT INTO Backlog (userId, gameTitle, dateModified) VALUES (:userId, :gameTitle, :dateModified);)");
            $Insert->bindValue(":userId", $userId);
            $Insert->bindValue(":gameTitle", $gameTitle);
            $Insert->bindValue(":dateModified",  $dateModified);

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
    function putGameFromBacklog()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $dbh = DatabaseConnection::getInstance();

        if (isset($input['backlogId']))
        {
            $backlogId = strip_tags($input['backlogId']);
            if (!filter_var($backlogId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'backlogId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'backlogId' not found in put JSON body");
        }

        if (isset($input['userId']))
        {
            $userId = strip_tags($input['userId']);
            if (!filter_var($userId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'userId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'userId' not found in put JSON body");
        }

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
            die("Field 'gameTitle' not found in put JSON body");
        }

        try
        {
            $dateModified = date("Y-m-d H:i:s");
            $Update = $dbh->prepare("UPDATE Backlog SET userId = :userId, gameTitle = :gameTitle, dateModified = :dateModified WHERE id = :backlogId;");
            $Update->bindValue(":userId", $userId);
            $Update->bindValue(":gameTitle", $gameTitle);
            $Update->bindValue(":dateModified", $dateModified);
            $Update->bindValue(":backlogId", $backlogId);

            $success = $Update->execute();
            if (!$success)
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die();
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
    function deleteGameFromBacklog($args)
    {
        $dbh = DatabaseConnection::getInstance();
        if(isset($args['id']))
        {
            $backlogId = $args['id'];
            if (!filter_var($backlogId, FILTER_VALIDATE_INT))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'backlogId' needs to be an int.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'backlogId' cannot be empty.");
        }

        $delete = $dbh->prepare("DELETE FROM Backlog WHERE id = :id");
        $delete->bindParam(':id', $backlogId);

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
    }

    //GET methods
    function getEntireBacklog($id)
    {
        $dbh = DatabaseConnection::getInstance();
        $userId = $id['id'];

        if(isset($userId))
        {
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

        $getBacklog = $dbh->prepare("SELECT * FROM Backlog WHERE userId = :userId;");
        $getBacklog->bindParam(':userId', $userId);

        try
        {
            $getBacklog->execute();
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }

        $backlog = array();

        while($result = $getBacklog->fetch())
        {
            $backlog[] = new Backlog($result[0], $result[1], $result[2], $result[3]);
        }

        http_response_code(StatusCodes::OK);
        return $backlog;
    }

    function getLastGameAdded($args)
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

        $maxId = $dbh->prepare("SELECT MAX(id) FROM Backlog WHERE userId = :userId");
        $maxId->bindParam(':userId', $userId);

        try
        {
            $maxId->execute();
        }
        catch(PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }

        $backlogId = $maxId->fetch();
        $backlogId = $backlogId[0];

        if(!$backlogId)
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            return "no records found";
        }

        $query = $dbh->prepare("SELECT * FROM Backlog WHERE userId = :userId AND id = :backlogId");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':backlogId', $backlogId);

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
            return new Backlog($data[0], $data[1], $data[2], $data[3]);
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            return "No user matching that ID";
        }
    }

    function getTopOfBacklog($args)
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

        $maxId = $dbh->prepare("SELECT MIN(id) FROM Backlog WHERE userId = :userId");
        $maxId->bindParam(':userId', $userId);

        try
        {
            $maxId->execute();
        }
        catch(PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }

        $backlogId = $maxId->fetch();
        $backlogId = $backlogId[0];

        if(!$backlogId)
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            return "no records found";
        }

        $query = $dbh->prepare("SELECT * FROM Backlog WHERE userId = :userId AND id = :backlogId");
        $query->bindParam(':userId', $userId);
        $query->bindParam(':backlogId', $backlogId);

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
            return new Backlog($data[0], $data[1], $data[2], $data[3]);
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            return "No user matching that ID";
        }
    }

    function getGameById($args)
    {
        $dbh = DatabaseConnection::getInstance();

        $backlogId = $args['id'];
        if (!filter_var($backlogId, FILTER_VALIDATE_INT))
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'backlogId' needs to be an int.");
        }

        $query = $dbh->prepare("SELECT * FROM Backlog WHERE id = :backlogId");
        $query->bindParam(':backlogId', $backlogId);

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
            return new Backlog($data[0], $data[1], $data[2], $data[3]);
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            return "No user matching that ID";
        }
    }
}