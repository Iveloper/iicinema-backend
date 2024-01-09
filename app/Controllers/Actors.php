<?php

namespace App\Controllers;

use App\Models\ActorModel;

class Actors extends BaseController
{
    protected $actorModel;

    public function __construct()
    {
        $this->actorModel = new ActorModel();
    }

    public function index()
    {
        $actors = $this->actorModel->findAll();

        // Pass the data to your view or do further processing
    }

    public function create()
    {
        // Display the form to create a new actor
    }

    public function store()
    {
        // Store the submitted actor data into the database
    }

    public function edit($id)
    {
        // Display the form to edit a specific actor
    }

    public function update($id)
    {
        // Update the specific actor in the database
    }

    public function delete($id)
    {
        // Delete the specific actor from the database
    }
}