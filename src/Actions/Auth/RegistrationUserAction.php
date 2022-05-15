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

        if (!(isset($body['email']) && isset($body['name']) &&
             isset($body['surname']) && isset($body['role']) && 
             isset($body['sex']))) {
            return $this->respond(new ActionPayload(400));
        }

        $emailExists = $this->connection->query('SELECT 1 FROM user WHERE email=?', $body['email'])->fetchSingle();

        if($emailExists) {
            return $this->respondWithData('Email already exists', 400);
        }
        
        if(isset($body['password']) && isset($body['passwordAgain'])) 
        {
            $password = $body['password'];
            $passwordAgain = $body['passwordAgain'];
        } else {
            return $this->respond(new ActionPayload(400));
        }
        
        if(!strcmp($password, $passwordAgain))
        {
            try{
                $this->connection->query(
                    'INSERT INTO user (uuid, name, surname, email, role_id, sex, password)
                     VALUES (?, ?, ?, ?, ?, ?, ?)',
                     (string)\Ramsey\Uuid\Uuid::uuid4(), $body['name'], $body['surname'], 
                     $body['email'], (int)$body['role'],  $body['sex'], hash('sha256', $password));
            } 
            catch(\Exception $e) {
                return $this->respond(new ActionPayload(400));
            }
        } else {
            return $this->respond(new ActionPayload(400));
        }
        return $this->respond(new ActionPayload(200));
    }
  
}