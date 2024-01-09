<?php

namespace App\Models;

use CodeIgniter\Model;

class ShowModel extends Model
{
    protected $table = 'shows';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'year_start', 'year_end', 'poster'];
    protected $useTimestamps = true;
}