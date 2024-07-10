<?php

namespace App\Controllers;
use Config\App;
use App\Models\UserModel;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        return $this->userModel->findAll();
    }

    public function login()
    {
        $postData = $this->request->getJSON(true);

        $email = $postData['email'];
        $password = $postData['password'];

        $userModel = new \App\Models\UserModel();

        // Find the user by username
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return $this->respond(['message' => 'User not found'], 404);
        }

        // Verify hashed password
        if (password_verify($password, $user['password']) && $user['is_approved'] == 1) {
            // Password is correct, return user details to the client
            $response = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role_id' => $user['role_id']
            ];

            return $this->respond($response, 200);
        } else {
            // Password is incorrect
            return $this->respond(['message' => 'Invalid credentials'], 401);
        }
    }

    public function register()
    {
        // Get the data from the request
        $postData = $this->request->getJSON(true);

        // Extract data from the POST request
        $username = $postData['username'];
        $email = $postData['email'];
        $password = $postData['password'];

        // If validation fails, return an error response
        if (!$username || !$email || !$password) {
            return $this->failValidationErrors('Invalid input data');
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Save the user data to the database
        $this->userModel->insert([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'role_id' => 2 // role_id 2 corresponds to a regular user role
        ]);

        return $this->respond([
            'message' => 'User registered successfully'
        ]);
    }

    public function update($id = null) {

        // Retrieve posted data
        $data = $this->request->getJSON(true);

        if ($data['id'] === null) {
            return $this->fail('No ID provided');
        }
        
        // Update the user's information in the database
        $user_id = $data['id'];// Get the user's ID from the session or elsewhere

        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $updatedData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role_id' => 2
        ];
        
        $this->userModel->update($user_id, $updatedData);
        
        // Respond with a success message or other relevant data
        return $this->respond(['message' => 'User information updated successfully']);
    }

    public function delete($id = null)
    {
        if ($id === null) {
            return $this->failValidationError('No ID provided');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->failNotFound('User not found');
        }

        if ($this->userModel->deleteUser($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'User deleted successfully!']);
        } else {
            return $this->failServerError('Failed to delete user');
        }
    }

    public function approve($id = null) {

        if ($id === null) {
            return $this->fail('No ID provided');
        }

        $this->userModel->approveUser($id);
        
        // Respond with a success message or other relevant data
        return $this->respond(['message' => 'User approved successfully']);
    }

    public function changeRole($userId) {
        $data = $this->request->getJSON();
        $role_id = $data->role_id;

        $this->userModel->update($userId, ['role_id' => $role_id]);

        return $this->respond(['message' => 'User role changed successfully']);
    }

    public function getUnapproved()
    {
        return $this->respond($this->userModel->getUnapprovedUsers());
    }

    public function getAllUsersAndAuthors()
    {
        return $this->respond($this->userModel->getAllUsersAndAuthors());
    }

    public function fetchFavorites($userId = null)
    {
        if (intval($userId) === null) {
            return $this->fail('No User ID provided');
        }

        $favoriteModel = new \App\Models\FavoriteModel();

        $favorites = [
            'movie' => $favoriteModel->getUserFavorites(intval($userId), 'movie'),
            'show' => $favoriteModel->getUserFavorites(intval($userId), 'show'),
            'actor' => $favoriteModel->getUserFavorites(intval($userId), 'actor'),
        ];

        return $this->respond($favorites);
    }

    public function addFavorite()
    {
        // Retrieve posted data
        $data = $this->request->getJSON(true);

        if (!$data['user_id'] || !$data['type'] || !$data['item_id']) {
            return $this->fail('Not enough info provided');
        }

        $favoriteModel = new \App\Models\FavoriteModel();

        $favoriteModel->addUserFavorite($data['user_id'], $data['type'], $data['item_id']);
        return $this->respondCreated(['message' => 'Item added to favorites']);
    }

    public function removeFavorite()
    {
        // Retrieve posted data
        $data = $this->request->getJSON(true);

        if (!$data['user_id'] || !$data['type'] || !$data['item_id']) {
            return $this->fail('Not enough info provided');
        }

        $favoriteModel = new \App\Models\FavoriteModel();

        $favoriteModel->removeUserFavorite($data['user_id'], $data['type'], $data['item_id']);
        return $this->respondDeleted(['message' => 'Item removed from favorites']);
    }
}