<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ChecklistModel;

class Checklist extends ResourceController
{
    public function __construct()
    {
        $this->model = new ChecklistModel();
    }

    public function index()
    {
        // Get the authenticated user's ID from the request
        $userId = $this->request->userId;

        // Fetch all checklists for the user
        $checklists = $this->model->where('user_id', $userId)->findAll();

        return $this->respond($checklists);
    }

    public function create()
    {
        // Get the authenticated user's ID from the request
        $userId = $this->request->userId;

        // Get the request data
        $data = $this->request->getJSON();
        $name = $data->name;

        // Insert the new checklist into the database
        $checklist = [
            'name' => $name,
            'user_id' => $userId,
        ];

        if ($this->model->insert($checklist)) {
            return $this->respond(['message' => 'Checklist created successfully'], 201);
        } else {
            return $this->fail($this->model->errors());
        }
    }

    public function delete($checklistId)
    {
        // Get the authenticated user's ID from the request
        $userId = $this->request->userId;

        // Find the checklist by ID and ensure it belongs to the user
        $checklist = $this->model->where('id', $checklistId)->where('user_id', $userId)->first();

        if (!$checklist) {
            return $this->failNotFound('Checklist not found or unauthorized');
        }

        // Delete the checklist
        if ($this->model->delete($checklistId)) {
            return $this->respond(['message' => 'Checklist deleted successfully']);
        } else {
            return $this->failServerError('Failed to delete checklist');
        }
    }
}