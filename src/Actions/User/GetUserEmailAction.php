<?php
declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Action;
use Slim\Psr7\Response;

class GetUserEmailAction extends Action 
{
    public function action(): Response
    {
        $userUuid = $this->request->getAttribute('userUUID');
        
        $userEmail = $this->connection->query(
            'SELECT email
             FROM user
             WHERE uuid=?', $userUuid
        )->fetch();

        return $this->respondWithData($userEmail);
    }
}