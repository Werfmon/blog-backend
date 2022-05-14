<?php
declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class UnSaveArticleAction extends Action
{
    public function action(): Response
    {
        $userUuid = $this->request->getQueryParams()['user-uuid'];
        $articleUuid = $this->request->getQueryParams()['article-uuid'];

        if(!isset($userUuid) || !isset($articleUuid)) {
            return $this->respond(new ActionPayload(400));
        }

        $this->connection->query('DELETE FROM saved_article WHERE user_uuid=? AND article_uuid=?', $userUuid, $articleUuid);

        return $this->respond(new ActionPayload(200));
    }
}