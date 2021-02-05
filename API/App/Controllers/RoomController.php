<?php


namespace App\Controllers;

use App\DAO\RoomDAO;
use App\Models\RoomModel;
use App\Models\MessageModel;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class RoomController
{

    public function index(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();
        $result = $DAO->get();

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();

        $result = $DAO->show($request->getAttribute('id'));

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();

        $data = $request->getParsedBody();

        $error = array();
        foreach (RoomModel::getFields() as $field) {
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

    public function update(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();

        $data = $request->getParsedBody();
        $data['id'] = $request->getAttribute('id');

        $result = $DAO->update($data);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();

        $id = $request->getAttribute('id');

        $result = $DAO->delete($id);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function indexMember(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();
        $roomID = $request->getAttribute('roomID');

        $result = $DAO->getMembers($roomID);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function addMember(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();
        $roomID = $request->getAttribute('roomID');
        $userID = $request->getAttribute('userID');
        
        $result = $DAO->addMember($roomID, $userID);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function removeMember(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();
        $roomID = $request->getAttribute('roomID');
        $userID = $request->getAttribute('userID');
        
        $result = $DAO->removeMember($roomID, $userID);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function giveAdmin(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();
        $roomID = $request->getAttribute('roomID');
        $userID = $request->getAttribute('userID');
        
        $result = $DAO->giveAdmin($roomID, $userID);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function revokeAdmin(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();
        $roomID = $request->getAttribute('roomID');
        $userID = $request->getAttribute('userID');
        
        $result = $DAO->revokeAdmin($roomID, $userID);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function indexMessage(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();
        $roomID = $request->getAttribute('roomID');

        $result = $DAO->getMessages($roomID);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function addMessage(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();
        $roomID = $request->getAttribute('roomID');
        $data = $request->getParsedBody();

        $error = array();
        foreach (MessageModel::getFields() as $field) {
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


        $result = $DAO->insertMessage($roomID, $data);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function editMessage(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();
        $roomID = $request->getAttribute('roomID');
        $messageID = $request->getAttribute('messageID');
        
        $data = $request->getParsedBody();
        $data['id'] = $messageID;


        $error = array();
        foreach (MessageModel::getFields() as $field) {
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

        $result = $DAO->editMessage($roomID, $data);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function deleteMessage(Request $request, Response $response, array $args): Response
    {
        $DAO = new RoomDAO();
        $roomID = $request->getAttribute('roomID');
        $messageID = $request->getAttribute('messageID');


        $result = $DAO->deleteMessage($roomID, $messageID);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }
}
