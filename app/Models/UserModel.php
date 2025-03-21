<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Specify the table name
    protected $table = 'users';

    // Specify the primary key
    protected $primaryKey = 'id';

    // Fields that can be mass-assigned
    protected $allowedFields = ['username', 'email', 'password'];

    // Disable automatic timestamp management
    protected $useTimestamps = false;

    /**
     * Method to find a user by username
     *
     * @param string $username
     * @return array|null
     */
    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Method to find a user by email
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}