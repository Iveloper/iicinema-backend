<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticlesCommentsModel extends Model
{
    protected $table = 'articles_comments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['article_id', 'commenter_id', 'comment_title', 'comment_content', 'created_at', 'updated_at'];

    public function getArticleComments($articleId) {
        return $this->select('articles_comments.*, users.username, users.email')
                    ->join('users', 'articles_comments.commenter_id = users.id')
                    ->where('articles_comments.article_id', $articleId)
                    ->findAll();
     }

    public function deleteComment($commentId)
    {
        return $this->where('id', $commentId)->delete();
    }
}