<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'email', 'password', 'role', 'avatar'];
    protected $returnType = 'array';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
    ];

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function getClients()
    {
        return $this->where('role', 'client')->findAll();
    }

    public function getAdmins()
    {
        return $this->where('role', 'admin')->findAll();
    }
}
