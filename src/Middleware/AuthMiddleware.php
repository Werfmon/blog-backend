<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\Token;
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
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): Response
    {
        $token_uuid = isset($request->getHeader('Authorization')[0]) ? $request->getHeader('Authorization')[0] : null;
        $response = new Response();

        if ($token_uuid !== null && strlen($token_uuid) === Token::TOKEN_LENGTH) {
            /**
             * vezme token poslany v pozadavku, rozdeli jej na userUuid a token,
             * pomoci token vyhleda v session jestli uzivatel existuje a z tokenu si vezme userUui
             */  
            session_start();

            $userUuid = substr($token_uuid, 36, 36);
            $token = substr($token_uuid, 0, 36);
            $sessionUserUuid = isset($_SESSION[$token]) ? substr($_SESSION[$token], 36, 36) : null;
            
            $response->getBody()->write(json_encode($_SESSION, JSON_PRETTY_PRINT));
            session_write_close();
            
            if ($sessionUserUuid === $userUuid) {
                $response = $handler->handle($request);
                $response->withStatus(200);
            } else {
                $response->getBody()->write(json_encode(['data' => ['message' => 'Uživatel není přihlášen']], JSON_PRETTY_PRINT));
                $response->withStatus(401);
            }
        }
        else {
            $response->getBody()->write(json_encode(['data' => ['message' => 'Invalid token']], JSON_PRETTY_PRINT));
            $response->withStatus(401);
        }
        return $response;
    }
}
