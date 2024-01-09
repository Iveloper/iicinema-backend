<?php

namespace App\Models;

use CodeIgniter\Model;

class MovieModel extends Model
{
    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'year', 'poster'];
    protected $useTimestamps = true;
}