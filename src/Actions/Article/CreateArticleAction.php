<?php
declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class CreateArticleAction extends Action
{
    public function action(): Response
    {
        $body = $this->request->getParsedBody();

        $title = $body['title'];
        $description = $body['description'];
        $text = $body['text'];
        $categoryId = $body['category_id'];
        $userUuid = $body['user_uuid'];
        $readTime = strlen($text) / 20;
        $dateCrated = new \DateTime();

        $this->connection->query(
            'INSERT INTO article (uuid, title, description, date_created, read_time, text, user_uuid, category_id)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)', (string)\Ramsey\Uuid\Uuid::uuid4(), $title, $description, $dateCrated, $readTime, $text, $userUuid, (int)$categoryId
        );

        return $this->respond(new ActionPayload(200));
    }
}