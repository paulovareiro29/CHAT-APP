<?php


namespace App\Controllers;

use App\DAO\UserDAO;
use App\Models\UserModel;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class UserController
{

    public function login(Request $request, Response $response, array $args): Response
    {
        $DAO = new UserDAO();

        $data = $request->getParsedBody();

        $myfile = fopen("loginLog.txt", "a");
        $log = time() . ": " . json_encode($data) . "\n";
        fwrite($myfile, $log);
        fclose($myfile);

        $result = $DAO->login($data);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function validateToken(Request $request, Response $response, array $args): Response
    {
        $DAO = new UserDAO();

        $data = $request->getParsedBody();

        if (!isset($data['token'])) {
            $response->getBody()->write(json_encode(
                [
                    "status" => 401,
                    "message" => "Unauthorized",
                    "body" => [
                        "Token was not provided"
                ]
            ]), JSON_UNESCAPED_UNICODE);
            return $response->withStatus(401);
        }

        $myfile = fopen("validateLog.txt", "a");
        $log = time() . ": " . json_encode($data) . "\n";
        fwrite($myfile, $log);
        fclose($myfile);

        $result = $DAO->validateToken($data);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    //CRUD USER

    public function index(Request $request, Response $response, array $args): Response
    {
        $DAO = new UserDAO();
        $result = $DAO->get();

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $DAO = new UserDAO();

        $result = $DAO->show($request->getAttribute('id'));

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $DAO = new UserDAO();

        $data = $request->getParsedBody();

        $error = array();
        foreach (UserModel::getFields() as $field) {
            if (!isset($data[$field]) || $data[$field] == "") {
                array_push($error, ucfirst($field) . " cannot be NULL");
            }
        }

        if (count($error)) {
            $response->getBody()->write(json_encode(
                [
                    "status" => 400,
                    "message" => "Error creating",
                    "body" => $error
                ]
            ), JSON_UNESCAPED_UNICODE);
            return $response->withStatus(400);
        }


        $result = $DAO->insert($data);


        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function update(Request $request, Response $response, array $args): Response {
        $DAO = new UserDAO();

        $data = $request->getParsedBody(); 
        $data['id'] = $request->getAttribute('id');

        $result = $DAO->update($data); 
     
        $response->getBody()->write(json_encode($result) , JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
          
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $userDAO = new UserDAO();

        $id = $request->getAttribute('id');

        $result = $userDAO->delete($id);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    //CRUD USER-PERMISSION
    public function indexPermission(Request $request, Response $response, array $args): Response
    {
        $DAO = new UserDAO();
        $userID = $request->getAttribute('userID');
        $result = $DAO->getPermission($userID);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function givePermission(Request $request, Response $response, array $args): Response
    {
        $DAO = new UserDAO();
        $userID = $request->getAttribute('userID');
        $permissionID = $request->getAttribute('permID');

        $result = $DAO->givePermission($userID,$permissionID);


        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function revokePermission(Request $request, Response $response, array $args): Response
    {
        $DAO = new UserDAO();
        $userID = $request->getAttribute('userID');
        $permissionID = $request->getAttribute('permID');

        $result = $DAO->revokePermission($userID,$permissionID);


        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }


}
