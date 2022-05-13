<?php
declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class LikeArticleByUserAction extends Action
{
    public function action(): Response
    {
        $articleUuid = $this->request->getQueryParams()['article-uuid'];
        $userUuid = $this->request->getQueryParams()['user-uuid'];

        if(!isset($articleUuid) || !isset($userUuid)) {
            return $this->respond(new ActionPayload(400));
        }

        $userLiked = $this->connection->query('SELECT 1 FROM liked_articles WHERE user_uuid=? AND article_uuid=?', $userUuid, $articleUuid);
        if($userLiked) {
            return $this->respond(new ActionPayload(400));
        }

        $this->connection->query('INSERT INTO liked_article VALUES (?, ?)', $userUuid, $articleUuid);

        return $this->respond(new ActionPayload(200));
    }
}