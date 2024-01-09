<?php

namespace App\Controllers;

use App\Models\RoleModel;

class Roles extends BaseController
{
    protected $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $roles = $this->roleModel->findAll();

        // Pass the data to your view or do further processing
    }

    public function create()
    {
        // Display the form to create a new role
    }

    public function store()
    {
        // Store the submitted role data into the database
    }

    public function edit($id)
    {
        // Display the form to edit a specific role
    }

    public function update($id)
    {
        // Update the specific role in the database
    }

    public function delete($id)
    {
        // Delete the specific role from the database
    }
}