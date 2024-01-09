<?php

namespace App\Controllers;

use App\Models\MovieModel;

class Movies extends BaseController
{
    protected $movieModel;

    public function __construct()
    {
        $this->movieModel = new MovieModel();
    }

    public function index()
    {
        $movies = $this->movieModel->findAll();

        return $this->response->setJSON($movies);
        // Pass the data to your view or do further processing
    }

    public function create()
    {
        // Display the form to create a new movie
    }

    public function store()
    {
        // Store the submitted movie data into the database
    }

    public function edit($id)
    {
        // Display the form to edit a specific movie
    }

    public function update($id)
    {
        // Update the specific movie in the database
    }

    public function delete($id)
    {
        // Delete the specific movie from the database
    }
}