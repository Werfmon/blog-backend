<?php
declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class LoginUserAction extends Action 
{
    public function action(): Response
    {
        $body = $this->request->getParsedBody();

        $passwd = $this->connection->query('SELECT password FROM user WHERE email=?', $body['email'])->fetchSingle();
        if($passwd === sha1($body['password'])) {
            session_start();
            $uuid = $this->connection->query('SELECT uuid FROM user WHERE email=?', $body['email'])->fetchSingle();

            //gen value + userUuid
            $rand = \Ramsey\Uuid\Uuid::uuid4();
            $token = $rand . $uuid;

            $_SESSION[(string)$rand] = $token;

            return $this->respondWithData($token);
        } else {
            return $this->respond(new ActionPayload(401));
        }
    }
}