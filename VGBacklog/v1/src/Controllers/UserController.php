<?php
/**
 * Created by PhpStorm.
 * User: Robbie
 * Date: 12/8/2016
 * Time: 10:21 PM
 */

namespace VGBacklog\Controllers;

use VGBacklog\Utilities\DatabaseConnection;
use VGBacklog\Http\StatusCodes;
use VGBacklog\Models\User;

class UserController
{

    function postNewUser()
    {
        $dbh = DatabaseConnection::getInstance();
        $input = json_decode(file_get_contents('php://input'), true);

        //check userName
        if (isset($input['userName']))
        {
            $userName = strip_tags($input['userName']);
            if(empty($userName))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'userName' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'userName' not found in post JSON body");
        }

        try
        {
            $Insert = $dbh->prepare("INSERT INTO User (userName) VALUES (:userName);)");
            $Insert->bindValue(":userName", $userName);

            $success = $Insert->execute();

            if (!$success)
            {
                throw new PDOException("Could not add new user");
            }
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }
        http_response_code(StatusCodes::CREATED);
    }

    function putUser()
    {
        $dbh = DatabaseConnection::getInstance();
        $input = json_decode(file_get_contents('php://input'), true);

        //check userId
        if (isset($input['userId']))
        {
            $userId = $input['userId'];
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

        //check userName
        if (isset($input['userName']))
        {
            $userName = strip_tags($input['userName']);
            if(empty($userName))
            {
                http_response_code(StatusCodes::BAD_REQUEST);
                die("Field 'userName' cannot be empty.");
            }
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'userName' not found in post JSON body");
        }

        try
        {
            $Update = $dbh->prepare("UPDATE User SET userName = :userName WHERE id = :userId;)");
            $Update->bindValue(":userName", $userName);
            $Update->bindValue(":userId", $userId);

            $success = $Update->execute();

            if (!$success)
            {
                throw new PDOException("Could not update new user");
            }
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }
        http_response_code(StatusCodes::CREATED);
    }

    function deleteUser($args)
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
            die("Field 'userId' cannot be empty.");
        }

        $delete = $dbh->prepare("DELETE FROM User WHERE id = :userId");
        $delete->bindParam(':userId', $userId);

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

    function getUserById($args)
    {
        $dbh = DatabaseConnection::getInstance();

        if (!filter_var($args['id'], FILTER_VALIDATE_INT))
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            die("Field 'userId' needs to be an int.");
        }
        $userId = $args['id'];

        $query = $dbh->prepare("SELECT * FROM User WHERE id = :userId");
        $query->bindParam(':userId', $userId);

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
            return new User($data[0], $data[1]);
        }
        else
        {
            http_response_code(StatusCodes::BAD_REQUEST);
            return "No user matching that ID";
        }
    }

    function getAllUsers()
    {
        $dbh = DatabaseConnection::getInstance();
        $getCollection = $dbh->prepare("SELECT * FROM User");

        try
        {
            $getCollection->execute();
        }
        catch (PDOException $ex)
        {
            http_response_code(StatusCodes::INTERNAL_SERVER_ERROR);
            die($ex);
        }

        $users = array();
        while($result = $getCollection->fetch())
        {
            $users[] = new User($result[0], $result[1]);
        }

        http_response_code(StatusCodes::OK);
        return $users;
    }
}