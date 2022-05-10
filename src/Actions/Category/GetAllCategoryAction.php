<?php
declare(strict_types=1);

namespace App\Actions\Category;

use App\Actions\Action;
use Slim\Psr7\Response;

class GetAllCategoryAction extends Action
{
    public function action(): Response
    {
        $categories = $this->connection->query('SELECT * FROM category')->fetchAll();
        return $this->respondWithData($categories);
    }
}