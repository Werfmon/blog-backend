<?php

declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use Slim\Psr7\Response;

class GetArticleByUuidAction extends Action
{
    public function action(): Response
    {
        $articleUuid = $this->request->getQueryParams()['uuid'];
        $article = $this->connection->query(
            'SELECT a.title AS article_name, a.description AS article_description, a.text AS article_text, 
                    a.read_time AS article_read_time, u.name AS user_name, 
                    u.surname AS user_surname, r.name AS user_role, c.name AS category_name,
                    u.uuid AS user_uuid, (
                        SELECT count(*)
                        FROM liked_article 
                        WHERE article_uuid=? 
                        GROUP BY article_uuid
                    ) AS likes
             FROM article a 
             INNER JOIN category c ON a.category_id = c.id 
             INNER JOIN user u ON u.uuid = a.user_uuid
             INNER JOIN role r ON r.id = u.role_id
             WHERE a.uuid=?', $articleUuid, $articleUuid
        )->fetch();

        return $this->respondWithData($article);
    }
}
