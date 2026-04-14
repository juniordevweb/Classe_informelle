<?php

namespace App\Controllers;

use App\Models\M_FacilitateurModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Database;

class C_FacilitateurController extends BaseController
{
    protected M_FacilitateurModel $facilitateurModel;

    public function __construct()
    {
        $this->facilitateurModel = new M_FacilitateurModel();
    }

    public function index()
    {
        $this->ensureFacilitateurTable();

        $data['user_permissions'] = $this->getUserPermissions();
        $data['facilitateurs'] = $this->facilitateurModel->orderBy('id', 'DESC')->findAll();
        $data['structures'] = $this->getReferenceOptions('structure');
        $data['classes'] = $this->getReferenceOptions('classe');

        return view('V_GestionFacilitateur', $data);
    }

    public function save()
    {
        $this->ensureFacilitateurTable();

        $rules = [
            'matricule' => 'required|max_length[100]|is_unique[facilitateur.matricule]',
            'prenom' => 'required|max_length[100]',
            'nom' => 'required|max_length[100]',
            'sexe' => 'required|in_list[Masculin,Feminin]',
            'date_naissance' => 'required|valid_date[Y-m-d]',
            'telephone' => 'required|max_length[30]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'adresse' => 'permit_empty|max_length[255]',
            'niveau_etude' => 'permit_empty|max_length[150]',
            'specialite' => 'permit_empty|max_length[150]',
            'date_recrutement' => 'required|valid_date[Y-m-d]',
            'type_contrat' => 'required|max_length[100]',
            'structure_id' => 'permit_empty|integer',
            'classe_id' => 'permit_empty|integer',
            'statut' => 'required|in_list[actif,inactif]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/facilitateur')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $structureId = $this->normalizeOptionalForeignKey($this->request->getPost('structure_id'), 'structure');
        $classeId = $this->normalizeOptionalForeignKey($this->request->getPost('classe_id'), 'classe');

        $data = [
            'matricule' => trim((string) $this->request->getPost('matricule')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'nom' => trim((string) $this->request->getPost('nom')),
            'sexe' => $this->request->getPost('sexe'),
            'date_naissance' => $this->request->getPost('date_naissance'),
            'telephone' => trim((string) $this->request->getPost('telephone')),
            'email' => trim((string) $this->request->getPost('email')),
            'adresse' => trim((string) $this->request->getPost('adresse')),
            'niveau_etude' => trim((string) $this->request->getPost('niveau_etude')),
            'specialite' => trim((string) $this->request->getPost('specialite')),
            'date_recrutement' => $this->request->getPost('date_recrutement'),
            'type_contrat' => trim((string) $this->request->getPost('type_contrat')),
            'structure_id' => $structureId,
            'classe_id' => $classeId,
            'statut' => $this->request->getPost('statut'),
        ];

        try {
            $this->facilitateurModel->insert($data);
        } catch (DatabaseException $e) {
            return redirect()->to('/facilitateur')
                ->withInput()
                ->with('error', "Impossible d'enregistrer le facilitateur.");
        }

        return redirect()->to('/facilitateur')
            ->with('success', 'Facilitateur ajoute avec succes.');
    }

    public function update()
    {
        $this->ensureFacilitateurTable();

        $id = (int) $this->request->getPost('id');
        $facilitateur = $this->facilitateurModel->find($id);

        if (! $facilitateur) {
            return redirect()->to('/facilitateur')
                ->with('error', 'Facilitateur introuvable.');
        }

        $matricule = trim((string) $this->request->getPost('matricule'));
        $existing = $this->facilitateurModel
            ->where('matricule', $matricule)
            ->where('id !=', $id)
            ->first();

        if ($existing) {
            return redirect()->to('/facilitateur')
                ->withInput()
                ->with('error', 'Le matricule existe deja.');
        }

        $rules = [
            'matricule' => 'required|max_length[100]',
            'prenom' => 'required|max_length[100]',
            'nom' => 'required|max_length[100]',
            'sexe' => 'required|in_list[Masculin,Feminin]',
            'date_naissance' => 'required|valid_date[Y-m-d]',
            'telephone' => 'required|max_length[30]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'adresse' => 'permit_empty|max_length[255]',
            'niveau_etude' => 'permit_empty|max_length[150]',
            'specialite' => 'permit_empty|max_length[150]',
            'date_recrutement' => 'required|valid_date[Y-m-d]',
            'type_contrat' => 'required|max_length[100]',
            'structure_id' => 'permit_empty|integer',
            'classe_id' => 'permit_empty|integer',
            'statut' => 'required|in_list[actif,inactif]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/facilitateur')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = $this->buildFacilitateurPayload();

        try {
            $this->facilitateurModel->update($id, $data);
        } catch (DatabaseException $e) {
            return redirect()->to('/facilitateur')
                ->withInput()
                ->with('error', 'Impossible de modifier le facilitateur.');
        }

        return redirect()->to('/facilitateur')
            ->with('success', 'Facilitateur modifie avec succes.');
    }

    public function delete($id = null)
    {
        $this->ensureFacilitateurTable();

        $id = (int) $id;
        $facilitateur = $this->facilitateurModel->find($id);

        if (! $facilitateur) {
            return redirect()->to('/facilitateur')
                ->with('error', 'Facilitateur introuvable.');
        }

        $this->facilitateurModel->delete($id);

        return redirect()->to('/facilitateur')
            ->with('success_delete', 'Facilitateur supprime avec succes.');
    }

    protected function ensureFacilitateurTable(): void
    {
        $db = Database::connect();

        if ($db->tableExists('facilitateur')) {
            return;
        }

        $forge = Database::forge();
        $forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'matricule' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'prenom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'sexe' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'date_naissance' => [
                'type' => 'DATE',
            ],
            'telephone' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'adresse' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'niveau_etude' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'specialite' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'date_recrutement' => [
                'type' => 'DATE',
            ],
            'type_contrat' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'structure_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'classe_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'statut' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'actif',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $forge->addKey('id', true);
        $forge->addUniqueKey('matricule');
        $forge->createTable('facilitateur', true);
    }

    protected function getReferenceOptions(string $tableName): array
    {
        $db = Database::connect();

        if (! $db->tableExists($tableName)) {
            return [];
        }

        $fields = $db->getFieldNames($tableName);
        $labelColumn = $this->resolveLabelColumn($fields);

        if ($labelColumn === null || ! in_array('id', $fields, true)) {
            return [];
        }

        $rows = $db->table($tableName)
            ->select('id, ' . $labelColumn)
            ->orderBy($labelColumn, 'ASC')
            ->get()
            ->getResultArray();

        return array_map(static function (array $row) use ($labelColumn): array {
            return [
                'id' => $row['id'],
                'label' => $row[$labelColumn] ?? ('#' . $row['id']),
            ];
        }, $rows);
    }

    protected function resolveLabelColumn(array $fields): ?string
    {
        $candidates = ['nom', 'nom_structure', 'nom_classe', 'libelle', 'designation', 'titre'];

        foreach ($candidates as $candidate) {
            if (in_array($candidate, $fields, true)) {
                return $candidate;
            }
        }

        return null;
    }

    protected function normalizeOptionalForeignKey($value, string $tableName): ?int
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $id = (int) $value;

        if ($id <= 0) {
            return null;
        }

        $db = Database::connect();

        if (! $db->tableExists($tableName)) {
            return null;
        }

        $exists = $db->table($tableName)->where('id', $id)->countAllResults();

        return $exists > 0 ? $id : null;
    }

    protected function buildFacilitateurPayload(): array
    {
        $structureId = $this->normalizeOptionalForeignKey($this->request->getPost('structure_id'), 'structure');
        $classeId = $this->normalizeOptionalForeignKey($this->request->getPost('classe_id'), 'classe');

        return [
            'matricule' => trim((string) $this->request->getPost('matricule')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'nom' => trim((string) $this->request->getPost('nom')),
            'sexe' => $this->request->getPost('sexe'),
            'date_naissance' => $this->request->getPost('date_naissance'),
            'telephone' => trim((string) $this->request->getPost('telephone')),
            'email' => trim((string) $this->request->getPost('email')),
            'adresse' => trim((string) $this->request->getPost('adresse')),
            'niveau_etude' => trim((string) $this->request->getPost('niveau_etude')),
            'specialite' => trim((string) $this->request->getPost('specialite')),
            'date_recrutement' => $this->request->getPost('date_recrutement'),
            'type_contrat' => trim((string) $this->request->getPost('type_contrat')),
            'structure_id' => $structureId,
            'classe_id' => $classeId,
            'statut' => $this->request->getPost('statut'),
        ];
    }
}
