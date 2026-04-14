<?php

namespace App\Models;

use CodeIgniter\Model;

class M_SuperviseurModel extends Model
{
    protected $table = 'superviseur';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'matricule',
        'prenom',
        'nom',
        'sexe',
        'telephone',
        'email',
        'fonction',
        'structure_affectation',
        'region',
        'departement',
        'date_affectation',
        'login',
        'password',
        'statut',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
}
