<?php
declare(strict_types=1);
use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegistrationUserAction;
use App\Actions\User\GetUserByUuidAction;
use App\Middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });
    $app->group('/auth', function(Group $auth) {
        $auth->post('/login', LoginUserAction::class);
        $auth->post('/registration', RegistrationUserAction::class);
    });
    $app->group('/app', function(Group $app) {
        $app->get('/users', GetUserByUuidAction::class);
    })->add(AuthMiddleware::class);
};
