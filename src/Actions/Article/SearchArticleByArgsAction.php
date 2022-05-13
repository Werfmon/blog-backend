<?php
declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class SearchArticleByArgsAction extends Action
{
    public function action(): Response
    {
        $searchText = $this->request->getQueryParams()['search'];
        $searchText = '%' . $searchText . '%';

        if(!isset($searchText)) {
            return $this->respond(new ActionPayload(400));
        }

        $articles = $this->connection->query(
            'SELECT a.title AS article_title, a.description AS article_description, a.uuid AS article_uuid,
                    u.name AS user_name, u.surname AS user_surname, r.name AS role_name
             FROM article a 
             INNER JOIN user u ON a.user_uuid = u.uuid 
             INNER JOIN role r ON r.id = u.role_id
             INNER JOIN category c ON c.id = a.category_id
             WHERE a.title LIKE ? OR a.description LIKE ? OR u.name LIKE ? OR u.surname LIKE ? OR c.name LIKE ?',
            $searchText, $searchText, $searchText, $searchText, $searchText
        )->fetchAll();
        return $this->respondWithData($articles);
    }
}