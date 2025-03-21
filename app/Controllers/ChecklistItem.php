<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ChecklistItemModel;

class ChecklistItem extends ResourceController
{
    public function __construct()
    {
        $this->model = new ChecklistItemModel();
    }

    public function index($checklistId)
    {
        // Get the authenticated user's ID from the request
        $userId = $this->request->userId;

        // Verify that the checklist belongs to the user
        $checklistModel = new \App\Models\ChecklistModel();
        $checklist = $checklistModel->where('id', $checklistId)->where('user_id', $userId)->first();

        if (!$checklist) {
            return $this->failNotFound('Checklist not found or unauthorized');
        }

        // Fetch all items for the checklist
        $items = $this->model->where('checklist_id', $checklistId)->findAll();

        return $this->respond($items);
    }

    public function create($checklistId)
    {
        // Get the authenticated user's ID from the request
        $userId = $this->request->userId;

        // Verify that the checklist belongs to the user
        $checklistModel = new \App\Models\ChecklistModel();
        $checklist = $checklistModel->where('id', $checklistId)->where('user_id', $userId)->first();

        if (!$checklist) {
            return $this->failNotFound('Checklist not found or unauthorized');
        }

        // Get the request data
        $data = $this->request->getJSON();
        $itemName = $data->itemName;

        // Insert the new item into the database
        $item = [
            'item_name' => $itemName,
            'checklist_id' => $checklistId,
            'completed' => false,
        ];

        if ($this->model->insert($item)) {
            return $this->respond(['message' => 'Item created successfully'], 201);
        } else {
            return $this->fail($this->model->errors());
        }
    }

    public function updateStatus($checklistId, $checklistItemId)
    {
        // Get the authenticated user's ID from the request
        $userId = $this->request->userId;

        // Verify that the checklist belongs to the user
        $checklistModel = new \App\Models\ChecklistModel();
        $checklist = $checklistModel->where('id', $checklistId)->where('user_id', $userId)->first();

        if (!$checklist) {
            return $this->failNotFound('Checklist not found or unauthorized');
        }

        // Find the item and update its status
        $item = $this->model->where('id', $checklistItemId)->where('checklist_id', $checklistId)->first();

        if (!$item) {
            return $this->failNotFound('Item not found');
        }

        $updatedData = ['completed' => !$item['completed']]; // Toggle the status

        if ($this->model->update($checklistItemId, $updatedData)) {
            return $this->respond(['message' => 'Item status updated successfully']);
        } else {
            return $this->failServerError('Failed to update item status');
        }
    }

    public function rename($checklistId, $checklistItemId)
    {
        // Get the authenticated user's ID from the request
        $userId = $this->request->userId;

        // Verify that the checklist belongs to the user
        $checklistModel = new \App\Models\ChecklistModel();
        $checklist = $checklistModel->where('id', $checklistId)->where('user_id', $userId)->first();

        if (!$checklist) {
            return $this->failNotFound('Checklist not found or unauthorized');
        }

        // Get the request data
        $data = $this->request->getJSON();
        $newName = $data->itemName;

        // Find the item and update its name
        $item = $this->model->where('id', $checklistItemId)->where('checklist_id', $checklistId)->first();

        if (!$item) {
            return $this->failNotFound('Item not found');
        }

        $updatedData = ['item_name' => $newName];

        if ($this->model->update($checklistItemId, $updatedData)) {
            return $this->respond(['message' => 'Item renamed successfully']);
        } else {
            return $this->failServerError('Failed to rename item');
        }
    }

    public function delete($checklistId, $checklistItemId)
    {
        // Get the authenticated user's ID from the request
        $userId = $this->request->userId;

        // Verify that the checklist belongs to the user
        $checklistModel = new \App\Models\ChecklistModel();
        $checklist = $checklistModel->where('id', $checklistId)->where('user_id', $userId)->first();

        if (!$checklist) {
            return $this->failNotFound('Checklist not found or unauthorized');
        }

        // Delete the item
        if ($this->model->delete($checklistItemId)) {
            return $this->respond(['message' => 'Item deleted successfully']);
        } else {
            return $this->failServerError('Failed to delete item');
        }
    }
}
