<?php

namespace App\Controllers;

use App\Models\ShowModel;

class Shows extends BaseController
{
    protected $showModel;

    public function __construct()
    {
        $this->showModel = new ShowModel();
    }

    public function index()
    {
        $shows = $this->showModel->findAll();

        // Pass the data to your view or do further processing
    }

    public function create()
    {
        // Display the form to create a new show
    }

    public function store()
    {
        // Store the submitted show data into the database
    }

    public function edit($id)
    {
        // Display the form to edit a specific show
    }

    public function update($id)
    {
        // Update the specific show in the database
    }

    public function delete($id)
    {
        // Delete the specific show from the database
    }
}