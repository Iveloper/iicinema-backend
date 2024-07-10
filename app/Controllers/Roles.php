<?php

namespace App\Controllers;
use Config\App;
use App\Models\RoleModel;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Roles extends ResourceController
{
    use ResponseTrait;
    protected $roleModel;

    public function __construct()
    {
        $this->roleModel = new \App\Models\RoleModel();
    }

    public function index()
    {
        return $this->respond($this->roleModel->findAll());
    }
}