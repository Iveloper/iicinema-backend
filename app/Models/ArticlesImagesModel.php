<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticlesImagesModel extends Model
{
    protected $table = 'articles_images';
    protected $primaryKey = 'id';
    protected $allowedFields = ['article_id', 'image_id'];
}