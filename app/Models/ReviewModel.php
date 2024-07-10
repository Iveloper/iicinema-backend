<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $allowedFields = ['reviewer_id', 'production_id', 'review_title', 'review_content', 'created_at', 'updated_at'];

    public function getProductionReviews($productionId) {
       return $this->select('reviews.*, users.username, users.email')
                   ->join('users', 'reviews.reviewer_id = users.id')
                   ->where('reviews.production_id', $productionId)
                   ->findAll();
    }

    public function deleteReview($reviewId)
    {
        return $this->where('id', $reviewId)->delete();
    }
}