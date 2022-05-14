<?php
declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class UpdateUserAction extends Action
{
    private array $keys = ['name', 'surname', 'email', 'role', 'sex'];

    public function action(): Response
    {
        $uuid = $this->request->getAttribute('userUUID');
        $body = $this->request->getParsedBody();

        if(!$this->checkData($body)) {
            return $this->respond(new ActionPayload(400));
        }

        $name = $body['name'];
        $surname = $body['surname'];
        $email = $body['email'];
        $role = (int)$body['role'];
        $sex = $body['sex'];

        $this->connection->query(
            'UPDATE user 
             SET name=?, surname=?, email=?, role_id=?, sex=? 
             WHERE uuid=?', $name, $surname, $email, (int)$role, $sex, $uuid
        );
        return $this->respond(new ActionPayload(200));
    }
    private function checkData(array $data): bool {
        foreach($this->keys as $key) {
            if(!key_exists($key, $data)) {
                return false;
            }
        }
        return true;
    }
}