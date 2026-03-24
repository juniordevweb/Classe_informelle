<?php namespace App\Models;

use CodeIgniter\Model;

class M_MenuModel extends Model
{
    protected $table      = 'menus';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom_menu', 'url', 'icone', 'ordre'];
}
