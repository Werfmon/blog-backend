<?php
declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class GetTopArticleAction extends Action
{
    public function action(): Response
    {
        $topCount= $this->request->getQueryParams()['top'];
        if(!isset($topCount)) {
            $this->respond(new ActionPayload(400));
        }
        $topArticles = $this->connection->query(
            'SELECT a.title AS article_title, a.description AS article_description, a.uuid AS article_uuid,
                    u.name AS user_name, u.surname AS user_surname, r.name AS role_name
             FROM article a 
             INNER JOIN user u ON a.user_uuid = u.uuid 
             INNER JOIN role r ON r.id = u.role_id
             WHERE top=1 
             ORDER BY top, read_time
             LIMIT ?', (int)$topCount
        )->fetchAll();
        return $this->respondWithData($topArticles);
    }
}