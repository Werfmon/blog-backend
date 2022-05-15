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

        if (!isset($body['email'])) {
            return $this->respond(new ActionPayload(400));
        }

        $auth = $this->connection->query('SELECT 1 FROM user WHERE email=? AND password=?', $body['email'], hash('sha256', $body['password']))->fetchSingle();

        if ($auth) {
            $uuid = $this->connection->query('SELECT uuid FROM user WHERE email=? AND password=?', $body['email'], hash('sha256', $body['password']))->fetchSingle();
            $token = \Ramsey\Uuid\Uuid::uuid4();
            $this->connection->query('UPDATE user SET `api-token`=? WHERE uuid=?', (string)$token, $uuid)->fetchSingle();

            return $this->respondWithData($token . $uuid);
        }
        return $this->respond(new ActionPayload(401));
    }
}
