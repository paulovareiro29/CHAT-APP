<?php

use App\Controllers\UserController;
use App\Controllers\PermissionController;
use App\Controllers\RoomController;


use App\Middlewares\AuthMiddleware;
use App\Middlewares\HeadersMiddleware;

use function src\slimConfiguration;

$app = new \Slim\App(slimConfiguration());

$app->add(HeadersMiddleware::class . ':addHeaders');

$app->group('', function () use ($app) {
    $app->post('/login', UserController::class . ':login');
    $app->post('/validateToken', UserController::class . ':validateToken');
    $app->post('/user/', UserController::class . ':create'); //registo utilizador
    
    $app->group('', function () use ($app) {

        $app->group('/user', function () use ($app) {
            $app->get('/', UserController::class . ':index');
            $app->get('/{id}', UserController::class . ':show');

            $app->put('/{id}', UserController::class . ':update');
            $app->delete('/{id}', UserController::class . ':delete');

            $app->group('/{userID}/permission', function () use ($app) {
                $app->get('/', UserController::class . ':indexPermission');
                $app->put('/{permID}', UserController::class . ':givePermission');
                $app->delete('/{permID}', UserController::class . ':revokePermission');
            });
        });

        $app->group('/permission', function () use ($app) {
            $app->get('/', PermissionController::class . ':index');
            $app->get('/{id}', PermissionController::class . ':show');
            $app->post('/', PermissionController::class . ':create');
            $app->put('/{id}', PermissionController::class . ':update');
            $app->delete('/{id}', PermissionController::class . ':delete');
        });

        $app->group('/room', function () use ($app) {
            $app->get('/', RoomController::class . ':index');
            $app->get('/{id}', RoomController::class . ':show');
            $app->post('/', RoomController::class . ':create');
            $app->put('/{id}', RoomController::class . ':update');
            $app->delete('/{id}', RoomController::class . ':delete');

            $app->group('/{roomID}/member', function () use ($app) {
                $app->get('/', RoomController::class . ':indexMember');
                $app->post('/{userID}', RoomController::class . ':addMember');
                $app->delete('/{userID}', RoomController::class . ':removeMember');

                $app->group('/{userID}/admin', function () use ($app) {
                    $app->put('/', RoomController::class . ':giveAdmin');       
                    $app->delete('/', RoomController::class . ':revokeAdmin');  
                });
            });

            $app->group('/{roomID}/message', function () use ($app) {
                $app->get('/', RoomController::class . ':indexMessage');
                $app->post('/', RoomController::class . ':addMessage');
                $app->put('/{messageID}', RoomController::class . ':editMessage');  
                $app->delete('/{messageID}', RoomController::class . ':deleteMessage');  
            });
        });
    })->add(AuthMiddleware::class . ':hasToken');
});


$app->run();
