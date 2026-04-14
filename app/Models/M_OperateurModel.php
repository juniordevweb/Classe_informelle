<?php

namespace App\Models;

use CodeIgniter\Model;

class M_OperateurModel extends Model
{
    protected $table = 'operateur';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'code_operateur',
        'nom_organisation',
        'type_operateur',
        'prenom_responsable',
        'nom_responsable',
        'telephone',
        'email',
        'adresse',
        'numero_agrement',
        'date_debut',
        'date_fin',
        'structure_id',
        'statut',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
}
