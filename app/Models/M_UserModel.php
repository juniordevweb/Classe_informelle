<?php namespace App\Models;

use CodeIgniter\Model;

class M_UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'email', 'password', 'role_id', 'status', 'created_at'];
    protected $useTimestamps = true;
}
