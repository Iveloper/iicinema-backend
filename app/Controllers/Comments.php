<?php

namespace App\Controllers;

use Config\App;
use App\Models\ArticleModel;
use App\Models\ArticlesCommentsModel;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;


class Comments extends ResourceController
{
    use ResponseTrait;
    protected $articleModel;
    protected $articlesCommentsModel;

    public function __construct()
    {
        $this->articleModel = new \App\Models\ArticleModel();
        $this->articlesCommentsModel = new \App\Models\ArticlesCommentsModel();
    }

    public function index()
    {
        return $this->respond($this->articlesCommentsModel->findAll());
    }

    public function getArticleComments($articleId) {
        return $this->respond($this->articlesCommentsModel->getArticleComments($articleId));
    }

    public function create()
    {
        $postData = $this->request->getJSON(true);
        $data = [
            'commenter_id' => $postData['commenter_id'],
            'article_id' => $postData['article_id'],
            'comment_title' => $postData['comment_title'],
            'comment_content' => $postData['comment_content'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->respond($this->articlesCommentsModel->insert($data));
    }

    public function update($id = null) {

        $data = json_decode(file_get_contents('php://input'), true);
        if ($id === null || empty($data)) {
            return $this->respond(['error' => 'Invalid data'], 400);
        }

        $updateData = [
            'comment_title' => $data['comment_title'],
            'comment_content' => $data['comment_content'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->articlesCommentsModel->update($id, $updateData);
        
        // Respond with a success message or other relevant data
        return $this->respond(['message' => 'Comment updated successfully']);
    }

    public function delete($id = null)
    {
        if ($id === null) {
            return $this->failValidationError('No ID provided');
        }

        $review = $this->articlesCommentsModel->find($id);
        if (!$review) {
            return $this->failNotFound('Review not found');
        }

        if ($this->articlesCommentsModel->deleteComment($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Comment deleted successfully']);
        } else {
            return $this->failServerError('Failed to delete review');
        }
    }
}