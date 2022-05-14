<?php
declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class GetAllUserLikedArticlesAction extends Action
{
    public function action(): Response
    {
        $userUuid = $this->request->getQueryParams()['uuid'];
        if(!isset($userUuid)) {
            $this->respond(new ActionPayload(400));
        }
        $savedArticles = $this->connection->query(
            'SELECT a.title AS article_title, a.description AS article_description, a.uuid AS article_uuid,
                    u.name AS user_name, u.surname AS user_surname, r.name AS role_name
             FROM liked_article la
             INNER JOIN article a ON la.article_uuid = a.uuid 
             INNER JOIN user u ON u.uuid = a.user_uuid
             INNER JOIN role r ON r.id = u.role_id
             WHERE la.user_uuid=? ', $userUuid
        )->fetchAll();
        return $this->respondWithData($savedArticles);
    }
}