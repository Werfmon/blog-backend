<?php
declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Action;
use App\Actions\ActionPayload;
use Slim\Psr7\Response;

class UpdateArticleAction extends Action
{
    private array $keys = ['title', 'description', 'text', 'category_id'];
    public function action(): Response
    {
        $articleUuid = $this->request->getAttribute('articleUUID');
        $body = $this->request->getParsedBody();

        if(!$this->checkKeys($body))
        {
            return $this->respond(new ActionPayload(400));
        }
        $readTime = strlen($body['text']) / 20;
        $this->connection->query(
            'UPDATE article 
             SET title=?, description=?, read_time=?, text=?, category_id=?
             WHERE uuid=?',
             $body['title'], $body['description'], $readTime, $body['text'], (int)$body['category_id'], $articleUuid
        );

        return $this->respond(new ActionPayload(200));
    }
    private function checkKeys(array $data): bool
    {
        foreach($this->keys as $key)
        {
            if(!key_exists($key, $data)) 
            {
                return false;
            }
        }
        return true;
    }
}