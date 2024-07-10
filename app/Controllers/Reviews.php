<?php

namespace App\Controllers;
use Config\App;
use App\Models\ReviewModel;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Reviews extends ResourceController
{
    use ResponseTrait;
    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new \App\Models\ReviewModel();
    }

    public function index()
    {
        return $this->respond($this->reviewModel->findAll());
    }

    public function getProductionReviews($productionId) {
        return $this->respond($this->reviewModel->getProductionReviews($productionId));
    }

    public function create()
    {
        $postData = $this->request->getJSON(true);
        $data = [
            'reviewer_id' => $postData['user_id'],
            'production_id' => $postData['production_id'],
            'review_title' => $postData['review_title'],
            'review_content' => $postData['review_content'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->respond($this->reviewModel->insert($data));
    }

    public function update($id = null) {

        $data = json_decode(file_get_contents('php://input'), true);
        if ($id === null || empty($data)) {
            return $this->respond(['error' => 'Invalid data'], 400);
        }

        $updateData = [
            'review_title' => $data['review_title'],
            'review_content' => $data['review_content'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->reviewModel->update($id, $updateData);
        
        // Respond with a success message or other relevant data
        return $this->respond(['message' => 'Review updated successfully']);
    }

    public function delete($id = null)
    {
        if ($id === null) {
            return $this->failValidationError('No ID provided');
        }

        $review = $this->reviewModel->find($id);
        if (!$review) {
            return $this->failNotFound('Review not found');
        }

        if ($this->reviewModel->deleteReview($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Review deleted successfully']);
        } else {
            return $this->failServerError('Failed to delete review');
        }
    }
}