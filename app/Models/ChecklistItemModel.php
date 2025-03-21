<?php

namespace App\Models;

use CodeIgniter\Model;

class ChecklistItemModel extends Model
{
    // Specify the table name
    protected $table = 'checklist_items';

    // Specify the primary key
    protected $primaryKey = 'id';

    // Fields that can be mass-assigned
    protected $allowedFields = ['item_name', 'checklist_id', 'completed'];

    // Disable automatic timestamp management
    protected $useTimestamps = false;

    /**
     * Method to find all items by checklist ID
     *
     * @param int $checklistId
     * @return array
     */
    public function getByChecklistId($checklistId)
    {
        return $this->where('checklist_id', $checklistId)->findAll();
    }

    /**
     * Method to find an item by ID and checklist ID
     *
     * @param int $itemId
     * @param int $checklistId
     * @return array|null
     */
    public function findByIdAndChecklistId($itemId, $checklistId)
    {
        return $this->where('id', $itemId)->where('checklist_id', $checklistId)->first();
    }

    /**
     * Method to toggle the completed status of an item
     *
     * @param int $itemId
     * @param int $checklistId
     * @return bool
     */
    public function toggleStatus($itemId, $checklistId)
    {
        $item = $this->findByIdAndChecklistId($itemId, $checklistId);

        if (!$item) {
            return false;
        }

        $updatedData = ['completed' => !$item['completed']];

        return $this->update($itemId, $updatedData);
    }
}