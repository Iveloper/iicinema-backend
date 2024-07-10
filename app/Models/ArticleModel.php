<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model
{
    protected $table = 'articles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['author_id', 'title', 'content', 'created_at', 'updated_at'];

    public function getArticles($pageSize, $offset) {
        $builder = $this->select('articles.*, users.username, users.email, CONCAT("http://localhost:8080/uploads/",images.image_url) AS image_url')
                    ->join('users', 'articles.author_id = users.id', 'left')
                    ->join('articles_images', 'articles.id = articles_images.article_id', 'left')
                    ->join('images', 'images.id = articles_images.image_id', 'left')
                    ->groupBy('articles.id')
                    ->limit($pageSize, $offset);

        $query = $builder->get();
        $articles = $query->getResult();

        // Get total count of articles for pagination
        $total = $this->countAllResults();

        $response = [
            'articles' => $articles,
            'total' => $total
        ];

        return $response;
    }
}