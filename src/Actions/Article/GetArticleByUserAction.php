<?php
declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use Slim\Psr7\Response;

class GetArticleByUserAction extends Action
{
    public function action(): Response
    {
        $userUuid = $this->request->getAttribute('userUUID');

        $articles = $this->connection->query(
            'SELECT title AS name, uuid
             FROM article 
             WHERE user_uuid=?', $userUuid
        )->fetchAll();

        return $this->respondWithData($articles);
    }
}