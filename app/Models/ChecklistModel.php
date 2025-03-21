<?php

namespace App\Models;

use CodeIgniter\Model;

class ChecklistModel extends Model
{
    // Specify the table name
    protected $table = 'checklists';

    // Specify the primary key
    protected $primaryKey = 'id';

    // Fields that can be mass-assigned
    protected $allowedFields = ['name', 'user_id'];

    // Disable automatic timestamp management
    protected $useTimestamps = false;

    /**
     * Method to find all checklists by user ID
     *
     * @param int $userId
     * @return array
     */
    public function getByUserId($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    /**
     * Method to find a checklist by ID and user ID
     *
     * @param int $checklistId
     * @param int $userId
     * @return array|null
     */
    public function findByIdAndUserId($checklistId, $userId)
    {
        return $this->where('id', $checklistId)->where('user_id', $userId)->first();
    }
}