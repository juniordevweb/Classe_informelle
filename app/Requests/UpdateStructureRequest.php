<?php

namespace App\Requests;

use CodeIgniter\Validation\Validation;

class UpdateStructureRequest
{
    protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    /**
     * Règles de validation pour la mise à jour d'une structure.
     *
     * @return array
     */
    public function rules(array $activeEtatCodes = ['EN_ATTENTE', 'VALIDE', 'OUVERT', 'FERME', 'GELE']): array
    {
        return [
            'nom_structure' => 'required|max_length[255]',
            'region' => 'required|max_length[255]',
            'departement' => 'required|max_length[255]',
            'commune' => 'required|max_length[255]',
            'quartier' => 'required|max_length[255]',
            'ia' => 'required|max_length[255]',
            'ief' => 'required|max_length[255]',
            'latitude' => 'permit_empty|decimal',
            'longitude' => 'permit_empty|decimal',
            'langue_nationale' => 'required|max_length[255]',
            'operateur_id' => 'required|integer|is_not_unique[operateur.id]',
            'etat' => 'required|in_list[' . implode(',', $activeEtatCodes) . ']',
        ];
    }

    /**
     * Messages de validation personnalisés.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nom_structure' => [
                'required' => 'Le nom de la structure est requis.',
                'max_length' => 'Le nom de la structure ne peut pas dépasser 255 caractères.',
            ],
            'region' => [
                'required' => 'La région est requise.',
            ],
            // Ajouter d'autres messages si nécessaire
            'operateur_id' => [
                'is_not_unique' => 'L\'opérateur sélectionné n\'existe pas.',
            ],
            'etat' => [
                'in_list' => 'L\'état doit être l\'un des suivants: EN_ATTENTE, VALIDE, OUVERT, FERME, GELE.',
            ],
        ];
    }

    /**
     * Valide les données.
     *
     * @param array $data
     * @return bool
     */
    public function validate(array $data, array $activeEtatCodes = ['EN_ATTENTE', 'VALIDE', 'OUVERT', 'FERME', 'GELE']): bool
    {
        return $this->validation->setRules($this->rules($activeEtatCodes), $this->messages())->run($data);
    }

    /**
     * Obtient les erreurs de validation.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->validation->getErrors();
    }
}
