<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Action;
use App\Actions\ActionPayload;
use PharIo\Manifest\Email;
use Slim\Psr7\Response;

class UpdateUserPasswordAction extends Action
{

    public function action(): Response
    {
        $params = $this->request->getQueryParams();

        if (!(isset($params['password']) || isset($params['current']) || isset($params['token']))) {
            return $this->respond(new ActionPayload(400));
        }
        $currentPassword = $params['current'];
        $token = $params['token'];
        $newPassword = $params['password'];

        $apiToken = substr($token, 0, 36);
        $userUuid = substr($token, 36, 36);
        $auth = $this->connection->query('SELECT 1 FROM user WHERE `api-token`=? AND uuid=?', $apiToken, $userUuid)->fetchSingle();

        if (!$auth) {
            return $this->respond(new ActionPayload(401));
        }
        $isCurrentOk = $this->connection->query('SELECT 1 FROM user WHERE password=? AND uuid=?', hash('sha256', $currentPassword), $userUuid)->fetchSingle();
        if(!$isCurrentOk) {
            return $this->respond(new ActionPayload(400));
        }
        $this->connection->query(
            'UPDATE user 
             SET password=?
             WHERE uuid=?',
            hash('sha256', $newPassword),
            $userUuid
        );
        return $this->respond(new ActionPayload(200));
    }
}
