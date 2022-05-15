<?php
declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class DeleteArticleByUuidAction extends Action 
{
    public function action(): Response
    {
        $articleUuid = $this->request->getAttribute('articleUUID');

        $this->connection->query(
            'DELETE FROM liked_article 
             WHERE article_uuid=?', $articleUuid
        );
        $this->connection->query(
            'DELETE FROM saved_article 
             WHERE article_uuid=?', $articleUuid
        );
        $this->connection->query(
            'DELETE FROM article 
             WHERE uuid=?', $articleUuid
        );

        return $this->respond(new ActionPayload(200));
    }
}