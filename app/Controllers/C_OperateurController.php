<?php

namespace App\Controllers;

use App\Models\M_OperateurModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Database;

class C_OperateurController extends BaseController
{
    protected M_OperateurModel $operateurModel;

    public function __construct()
    {
        $this->operateurModel = new M_OperateurModel();
    }

    public function index()
    {
        $this->ensureOperateurTable();

        $data['user_permissions'] = $this->getUserPermissions();
        $data['operateurs'] = $this->operateurModel->orderBy('id', 'DESC')->findAll();
        $data['structures'] = $this->getReferenceOptions('structure');

        return view('V_GestionOperateur', $data);
    }

    public function save()
    {
        $this->ensureOperateurTable();

        $rules = [
            'code_operateur' => 'required|max_length[100]|is_unique[operateur.code_operateur]',
            'nom_organisation' => 'required|max_length[150]',
            'type_operateur' => 'required|max_length[100]',
            'prenom_responsable' => 'required|max_length[100]',
            'nom_responsable' => 'required|max_length[100]',
            'telephone' => 'required|max_length[30]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'adresse' => 'permit_empty|max_length[255]',
            'numero_agrement' => 'permit_empty|max_length[100]',
            'date_debut' => 'required|valid_date[Y-m-d]',
            'date_fin' => 'permit_empty|valid_date[Y-m-d]',
            'structure_id' => 'permit_empty|integer',
            'statut' => 'required|in_list[actif,inactif]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/operateur')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->operateurModel->insert($this->buildOperateurPayload());
        } catch (DatabaseException $e) {
            return redirect()->to('/operateur')
                ->withInput()
                ->with('error', "Impossible d'enregistrer l'operateur.");
        }

        return redirect()->to('/operateur')
            ->with('success', 'Operateur ajoute avec succes.');
    }

    public function update()
    {
        $this->ensureOperateurTable();

        $id = (int) $this->request->getPost('id');
        $operateur = $this->operateurModel->find($id);

        if (! $operateur) {
            return redirect()->to('/operateur')
                ->with('error', 'Operateur introuvable.');
        }

        $codeOperateur = trim((string) $this->request->getPost('code_operateur'));
        $existing = $this->operateurModel
            ->where('code_operateur', $codeOperateur)
            ->where('id !=', $id)
            ->first();

        if ($existing) {
            return redirect()->to('/operateur')
                ->withInput()
                ->with('error', 'Le code operateur existe deja.');
        }

        $rules = [
            'code_operateur' => 'required|max_length[100]',
            'nom_organisation' => 'required|max_length[150]',
            'type_operateur' => 'required|max_length[100]',
            'prenom_responsable' => 'required|max_length[100]',
            'nom_responsable' => 'required|max_length[100]',
            'telephone' => 'required|max_length[30]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'adresse' => 'permit_empty|max_length[255]',
            'numero_agrement' => 'permit_empty|max_length[100]',
            'date_debut' => 'required|valid_date[Y-m-d]',
            'date_fin' => 'permit_empty|valid_date[Y-m-d]',
            'structure_id' => 'permit_empty|integer',
            'statut' => 'required|in_list[actif,inactif]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/operateur')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->operateurModel->update($id, $this->buildOperateurPayload());
        } catch (DatabaseException $e) {
            return redirect()->to('/operateur')
                ->withInput()
                ->with('error', "Impossible de modifier l'operateur.");
        }

        return redirect()->to('/operateur')
            ->with('success', 'Operateur modifie avec succes.');
    }

    public function delete($id = null)
    {
        $this->ensureOperateurTable();

        $id = (int) $id;
        $operateur = $this->operateurModel->find($id);

        if (! $operateur) {
            return redirect()->to('/operateur')
                ->with('error', 'Operateur introuvable.');
        }

        $this->operateurModel->delete($id);

        return redirect()->to('/operateur')
            ->with('success_delete', 'Operateur supprime avec succes.');
    }

    protected function ensureOperateurTable(): void
    {
        $db = Database::connect();

        if ($db->tableExists('operateur')) {
            return;
        }

        $forge = Database::forge();
        $forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'code_operateur' => ['type' => 'VARCHAR', 'constraint' => 100],
            'nom_organisation' => ['type' => 'VARCHAR', 'constraint' => 150],
            'type_operateur' => ['type' => 'VARCHAR', 'constraint' => 100],
            'prenom_responsable' => ['type' => 'VARCHAR', 'constraint' => 100],
            'nom_responsable' => ['type' => 'VARCHAR', 'constraint' => 100],
            'telephone' => ['type' => 'VARCHAR', 'constraint' => 30],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'adresse' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'numero_agrement' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'date_debut' => ['type' => 'DATE'],
            'date_fin' => ['type' => 'DATE', 'null' => true],
            'structure_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'statut' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'actif'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $forge->addKey('id', true);
        $forge->addUniqueKey('code_operateur');
        $forge->createTable('operateur', true);
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
        $candidates = ['nom', 'nom_structure', 'libelle', 'designation', 'titre'];

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

    protected function buildOperateurPayload(): array
    {
        $structureId = $this->normalizeOptionalForeignKey($this->request->getPost('structure_id'), 'structure');

        return [
            'code_operateur' => trim((string) $this->request->getPost('code_operateur')),
            'nom_organisation' => trim((string) $this->request->getPost('nom_organisation')),
            'type_operateur' => trim((string) $this->request->getPost('type_operateur')),
            'prenom_responsable' => trim((string) $this->request->getPost('prenom_responsable')),
            'nom_responsable' => trim((string) $this->request->getPost('nom_responsable')),
            'telephone' => trim((string) $this->request->getPost('telephone')),
            'email' => trim((string) $this->request->getPost('email')),
            'adresse' => trim((string) $this->request->getPost('adresse')),
            'numero_agrement' => trim((string) $this->request->getPost('numero_agrement')),
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin' => $this->request->getPost('date_fin') ?: null,
            'structure_id' => $structureId,
            'statut' => $this->request->getPost('statut'),
        ];
    }
}
