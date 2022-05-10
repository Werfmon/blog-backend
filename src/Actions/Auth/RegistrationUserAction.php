<?php
declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class RegistrationUserAction extends Action
{
    public function action(): Response
    {
        $body = $this->request->getParsedBody();

        $password = '';
        $passwordAgain = '';
        if(isset($body['password']) && isset($body['passwordAgain'])) 
        {
            $password = $body['password'];
            $passwordAgain = $body['passwordAgain'];
        } else {
            return $this->respond(new ActionPayload(500));
        }

        if(!strcmp($password, $passwordAgain))
        {
            try{
                $this->connection->query('INSERT INTO user (uuid, name, surname, email, role_id, sex, password) VALUES (?, ?, ?, ?, ?, ?, ?)',
                                          (string)\Ramsey\Uuid\Uuid::uuid4(), $body['name'], $body['surname'], $body['email'], (int)$body['role'],  $body['sex'], hash('sha256', $password));
            } catch(\Exception $e)
            {
                return $this->respond(new ActionPayload(500));
            }
        } else {
            return $this->respond(new ActionPayload(500));
        }
        return $this->respond(new ActionPayload(200));
    }
  
}