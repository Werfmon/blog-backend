<?php
declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class DeleteUserAccountAction extends Action
{
    public function action(): Response
    {
        $deleteArticles = (bool)$this->request->getQueryParams('delete-articles');
        $userUuid = $this->request->getAttribute('userUUID');
        $body = $this->request->getParsedBody();
        if(!isset($body['password'])) {
            return $this->respond(new ActionPayload(401));
        }

        if(!isset($deleteArticles)) {
            return $this->respond(new ActionPayload(400));
        }
        $password = $body['password'];
        $auth = $this->connection->query(
            'SELECT 1
             FROM user 
             WHERE uuid=? AND password=?',
                $userUuid, hash('sha256', $password)
        )->fetch();
        var_dump($auth);
        if($auth) {
            if($deleteArticles) {
                $this->connection->query('DELETE FROM liked_article WHERE user_uuid=?', $userUuid);
                $this->connection->query('DELETE FROM saved_article WHERE user_uuid=?', $userUuid);
                $this->connection->query('DELETE FROM article WHERE user_uuid=?', $userUuid);
            }
            
            $this->connection->query('DELETE FROM user WHERE uuid=?', $userUuid);
            return $this->respondWithData();
        }
        return $this->respond(new ActionPayload(400));
    }
}