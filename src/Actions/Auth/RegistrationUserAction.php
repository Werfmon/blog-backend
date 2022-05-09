<?php
declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Action;
use Slim\Psr7\Response;

class RegistrationUserAction extends Action
{
    public function action(): Response
    {
        return $this->respondWithData();
    }
}