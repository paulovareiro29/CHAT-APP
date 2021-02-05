<?php

namespace App\Middlewares;

use App\DAO\UserDAO;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class AuthMiddleware
{

    public function hasToken(Request $request, Response $response, $next)
    {
        $userDAO = new UserDAO();

        $token = $request->getHeader('TOKEN');
        $valid = $userDAO->validateToken($token);

        if ($valid['status'] == 200) {
            $response = $next($request, $response);
            return $response;
        }

        $response->getBody()->write(json_encode($valid), JSON_UNESCAPED_UNICODE);
        return $response->withStatus($valid['status']);
    }
}
