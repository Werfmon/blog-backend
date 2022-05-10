<?php
declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Action;
use Slim\Psr7\Response;

class GetUserByUuid extends Action
{
    public function action(): Response
    {
        $uuid = $this->request->getAttribute('userUUID');
        $user = $this->connection->query('SELECT * FROM user WHERE uuid=?', $uuid)->fetch();
        return $this->respondWithData($user);
    }
}