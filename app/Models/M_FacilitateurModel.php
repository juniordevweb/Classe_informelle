<?php

namespace App\Models;

use CodeIgniter\Model;

class M_FacilitateurModel extends Model
{
    protected $table = 'facilitateur';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'matricule',
        'prenom',
        'nom',
        'sexe',
        'date_naissance',
        'telephone',
        'email',
        'adresse',
        'niveau_etude',
        'specialite',
        'date_recrutement',
        'type_contrat',
        'structure_id',
        'classe_id',
        'statut',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
}
