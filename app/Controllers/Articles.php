<?php
namespace App\Controllers;

use Config\App;
use App\Models\ArticleModel;
use App\Models\ImageModel;
use App\Models\ArticlesCommentsModel;
use App\Models\ArticlesImagesModel;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Articles extends ResourceController
{
    use ResponseTrait;
    protected $articleModel;
    protected $imageModel;
    protected $articlesCommentsModel;
    protected $articlesImagesModel;

    public function __construct()
    {
        $this->articleModel = new \App\Models\ArticleModel();
        $this->imageModel = new \App\Models\ImageModel();
        $this->articlesImagesModel = new \App\Models\ArticlesImagesModel();
        $this->articlesCommentsModel = new \App\Models\ArticlesCommentsModel();
    }

    public function index()
    {
        return $this->respond($this->articleModel->findAll());
    }

    public function getArticles()
    {
        $page = $this->request->getGet('page') ?? 0;
        $pageSize = $this->request->getGet('pageSize') ?? 4;
        $offset = $page * $pageSize;
        return $this->respond($this->articleModel->getArticles($pageSize, $offset));
    }

    public function create()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'title' => 'required',
            'content' => 'required',
            'author_id' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->fail($validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'content' =>  $this->request->getPost('content'),
            'author_id' =>  $this->request->getPost('author_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $article_id = $this->articleModel->insert($data);

        if (!$article_id) {
            return $this->fail('Unable to create article.');
        }

        $files = $this->request->getFiles();
        if ($files) {
            foreach ($files['images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $fileName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads', $fileName);

                    $imageData = [
                        'image_url' => $fileName,
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    $image_id = $this->imageModel->insert($imageData);

                    $this->articlesImagesModel->insert([
                        'article_id' => $article_id,
                        'image_id' => $image_id
                    ]);
                }
            }
        }

        return $this->respondCreated(['message' => 'Article created successfully', 'article_id' => $article_id]);
    }

    public function show($id = null)
    {
        $article = $this->articleModel->find($id);
        if (!$article) return $this->failNotFound('Article not found');

        // Get related images and comments
        $db = \Config\Database::connect();
        $comments = $db->table('articles_comments')
            ->select('articles_comments.*, users.username, users.email')
            ->join('users', 'users.id = articles_comments.commenter_id')
            ->where('article_id', $id)
            ->get()
            ->getResult();
        $author = $db->table('users')
            ->select('username, email, role_id')
            ->where('id', $article['author_id'])
            ->get()
            ->getResult();
        $images = $this->articlesImagesModel
            ->select('images.image_url')
            ->join('images', 'images.id = articles_images.image_id')
            ->where('articles_images.article_id', $id)
            ->findAll();

        $articleImages = $this->articlesImagesModel->where('article_id', $id)->findAll();
        $images = [];

        foreach ($articleImages as $articleImage) {
            $image = $this->imageModel->find($articleImage['image_id']);
            if ($image) {
                $images[] = [
                    'src' => base_url('uploads/' . $image['image_url']),
                    'id' => $image['id']
                ];
            }
        }

        $article['images'] = $images;

        // $article['images'] = array_map(function ($image) {
        //     return base_url('uploads/' . $image['image_url']);
        // }, $images);

        return $this->respond(['article' => $article, 'images' => $article['images'], 'comments' => $comments, 'author' => $author]);
    }

    public function update($id = null)
    {

        $data = json_decode(file_get_contents('php://input'), true);
        if ($id === null || empty($data)) {
            return $this->respond(['error' => 'Invalid data'], 400);
        }

        $updateData = [
            'title' => $data['title'],
            'content' => $data['content'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->articleModel->update($id, $updateData);
        
        return $this->respond(['message' => 'Article updated successfully']);
    }

    public function delete($id = null)
    {
        $article = $this->articleModel->find($id);
        if (!$article) return $this->failNotFound('Article not found');
        $this->articleModel->delete($id);
        return $this->respond(['message' => 'Article deleted successfully']);
    }

    public function deleteImg($id = null)
    {
        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            // Delete from articles_images table
            $this->articlesImagesModel->where('image_id', $id)->delete();

            // Delete from images table
            $this->imageModel->delete($id);

            // Commit transaction
            $db->transCommit();

            return $this->respond(['message' => 'Image deleted successfully']);
        } catch (\Exception $e) {
            // Rollback transaction if something went wrong
            $db->transRollback();

            return $this->failServerError('Failed to delete image');
        }
    }
}