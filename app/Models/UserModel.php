<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'role_id', 'is_approved'];

    public function getUnapprovedUsers() {
       return $this->select('id, username, email, role_id, is_approved')
                   ->where('is_approved', 0)
                   ->findAll();
    }

    public function getAllUsersAndAuthors() {
        return $this->select('id, username, email, role_id')
                    ->where('is_approved = 1')
                    ->findAll();
        
        if (!empty($query)) {
            return $query;
        } else {
            return array();
        }
     }

    public function changeRole($userId, $roleId) {
        return $this->update($userId, ['role_id' => $roleId]);
    }

    public function approveUser($userId)
    {
        $builder = $this->db->table($this->table);
        $builder->set('is_approved', 1);
        $builder->where('id', $userId);
        $builder->update();
    }

    public function deleteUser($userId)
    {
        return $this->where('id', $userId)->delete();
    }
}