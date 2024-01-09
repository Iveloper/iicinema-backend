<?php
namespace App\Models;

use CodeIgniter\Model;

class FavoriteModel extends Model
{
    protected $table = 'users_favorites'; // No need to specify a table here, as it will vary

    // Add other model configurations

    public function addUserFavorite($userId, $type, $itemId)
    {
        $table = 'users_' . $type . 's';

        $data = [
            'user_id' => $userId,
            $type . '_id' => $itemId
        ];

        $this->db->table($table)->insert($data);
    }

    public function removeUserFavorite($userId, $type, $itemId)
    {
        $table = 'users_' . $type . 's';

        $this->db->table($table)
            ->where('user_id', $userId)
            ->where($type . '_id', $itemId)
            ->delete();
    }

    public function isUserFavorite($userId, $type, $itemId)
    {
        $table = 'users_' . $type . 's';

        return $this->db->table($table)
            ->where('user_id', $userId)
            ->where($type . '_id', $itemId)
            ->countAllResults() > 0;
    }

    public function getUserFavorites($userId, $type)
    {
        $favoritesTable = "users_" . $type . 's';

        return $this->db->table($favoritesTable)
            ->select("{$type}_id")
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();
    }
}





