<?php
declare(strict_types=1);

namespace App\Actions\Role;

use App\Actions\Action;
use Slim\Psr7\Response;

class GetAllRolesAction extends Action
{
    public function action(): Response
    {
        $roles = $this->connection->query('SELECT * FROM role')->fetchAll();
        return $this->respondWithData($roles);
    }
}