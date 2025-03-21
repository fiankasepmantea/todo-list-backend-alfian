<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends ResourceController
{
    public function register()
    {
        $data = $this->request->getJSON();
        $model = new \App\Models\UserModel();

        $hashedPassword = password_hash($data->password, PASSWORD_BCRYPT);
        $user = [
            'username' => $data->username,
            'email' => $data->email,
            'password' => $hashedPassword,
        ];

        if ($model->insert($user)) {
            return $this->respond(['message' => 'User registered successfully'], 201);
        } else {
            return $this->fail($model->errors());
        }
    }

    public function login()
    {
        $data = $this->request->getJSON();
        $model = new \App\Models\UserModel();

        $user = $model->where('username', $data->username)->first();
        if (!$user || !password_verify($data->password, $user['password'])) {
            return $this->failUnauthorized('Invalid credentials');
        }

        $key = getenv('JWT_SECRET');
        $payload = ['userId' => $user['id']];
        $token = JWT::encode($payload, $key, 'HS256');

        return $this->respond(['token' => $token]);
    }
}