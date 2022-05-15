<?php
declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use Slim\Psr7\Response;

class GetArticleForUpdateAction extends Action
{
    public function action(): Response
    {
        $articleUuid = $this->request->getAttribute('articleUUID');

        $article = $this->connection->query(
            'SELECT *
             FROM article
             WHERE uuid=?', $articleUuid
        )->fetch();

        return $this->respondWithData($article);
    }
}