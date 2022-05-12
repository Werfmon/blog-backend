<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\Token;
use Dibi\Connection;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    private Connection $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): Response
    {
        // ini_set('session.cookie_secure', '0');
        $token_uuid = isset($request->getHeader('Authorization')[0]) ? $request->getHeader('Authorization')[0] : null;
        $response = new Response();
   
        if($token_uuid !== null && strlen($token_uuid) === Token::TOKEN_LENGTH) {

            $token = substr($token_uuid, 0, 36);
            $uuid = substr($token_uuid, 36, 36);
            $auth = $this->connection->query('SELECT 1 FROM user WHERE `api-token`=? AND uuid=?', $token, $uuid);
            
            if($auth) {
                $response = $handler->handle($request);
                $response->withHeader('Content-Type', 'application/json');
                $response->withStatus(200);
            } else {
                $response->getBody()->write(json_encode(['data' => ['message' => 'Uživatel není přihlášen']], JSON_PRETTY_PRINT));
                $response->withHeader('Content-Type', 'application/json');
                $response->withStatus(401);
            }
        }
        else {
            $response->getBody()->write(json_encode(['data' => ['message' => 'Invalid token']], JSON_PRETTY_PRINT));
            $response->withHeader('Content-Type', 'application/json');
            $response->withStatus(401);
        }
        return $response;
    }
}
