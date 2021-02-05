<?php


namespace App\Controllers;

use App\DAO\PermissionDAO;
use App\Models\PermissionModel;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class PermissionController
{

    public function index(Request $request, Response $response, array $args): Response
    {
        $DAO = new PermissionDAO();
        $result = $DAO->get();

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $DAO = new PermissionDAO();

        $result = $DAO->show($request->getAttribute('id'));

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $DAO = new PermissionDAO();

        $data = $request->getParsedBody();

        $error = array();
        foreach (PermissionModel::getFields() as $field) {
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
        $DAO = new PermissionDAO();

        $data = $request->getParsedBody();
        $data['id'] = $request->getAttribute('id');

        $result = $DAO->update($data);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $DAO = new PermissionDAO();

        $id = $request->getAttribute('id');

        $result = $DAO->delete($id);

        $response->getBody()->write(json_encode($result), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($result['status']);
    }
}
