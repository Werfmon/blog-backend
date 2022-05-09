<?php
declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Action;
use Slim\Psr7\Response;

class GetUserByUuidAction extends Action {

    protected function action(): Response {
        
        $data = $this->request->getParsedBody();
        
        return $this->respondWithData($data);
    }
}