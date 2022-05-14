<?php
declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Action;
use Slim\Psr7\Response;

class GetAllUserInformationAction extends Action
{
    public function action(): Response
    {
        $uuid = $this->request->getAttribute('userUUID');
        $user = $this->connection->query(
            'SELECT u.name AS user_name, u.surname AS user_surname, 
                    u.email AS user_email, u.sex as user_sex, 
                    r.name AS role_name
             FROM user u 
             INNER JOIN role r ON u.role_id = r.id
             WHERE u.uuid=?', $uuid
        )->fetch();
        return $this->respondWithData($user);
    }
}