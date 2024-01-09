<?php

namespace App\Models;

use CodeIgniter\Model;

class ActorModel extends Model
{
    protected $table = 'actors';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'age', 'image'];
    protected $useTimestamps = true;
}