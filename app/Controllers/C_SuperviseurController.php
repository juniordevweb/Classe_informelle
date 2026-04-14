<?php

namespace App\Controllers;

use App\Models\M_SuperviseurModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Database;

class C_SuperviseurController extends BaseController
{
    protected M_SuperviseurModel $superviseurModel;

    public function __construct()
    {
        $this->superviseurModel = new M_SuperviseurModel();
    }

    public function index()
    {
        $this->ensureSuperviseurTable();

        $data['user_permissions'] = $this->getUserPermissions();
        $data['superviseurs'] = $this->superviseurModel->orderBy('id', 'DESC')->findAll();

        return view('V_GestionSuperviseur', $data);
    }

    public function save()
    {
        $this->ensureSuperviseurTable();

        if (! $this->validate($this->getValidationRules(true))) {
            return redirect()->to('/superviseur')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->superviseurModel->insert($this->buildSuperviseurPayload(true));
        } catch (DatabaseException $e) {
            return redirect()->to('/superviseur')
                ->withInput()
                ->with('error', "Impossible d'enregistrer le superviseur.");
        }

        return redirect()->to('/superviseur')
            ->with('success', 'Superviseur ajoute avec succes.');
    }

    public function update()
    {
        $this->ensureSuperviseurTable();

        $id = (int) $this->request->getPost('id');
        $superviseur = $this->superviseurModel->find($id);

        if (! $superviseur) {
            return redirect()->to('/superviseur')
                ->with('error', 'Superviseur introuvable.');
        }

        $matricule = trim((string) $this->request->getPost('matricule'));
        $login = trim((string) $this->request->getPost('login'));

        $existingMatricule = $this->superviseurModel
            ->where('matricule', $matricule)
            ->where('id !=', $id)
            ->first();

        if ($existingMatricule) {
            return redirect()->to('/superviseur')
                ->withInput()
                ->with('error', 'Le matricule existe deja.');
        }

        $existingLogin = $this->superviseurModel
            ->where('login', $login)
            ->where('id !=', $id)
            ->first();

        if ($existingLogin) {
            return redirect()->to('/superviseur')
                ->withInput()
                ->with('error', 'Le login existe deja.');
        }

        if (! $this->validate($this->getValidationRules(false))) {
            return redirect()->to('/superviseur')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->superviseurModel->update($id, $this->buildSuperviseurPayload(false, $superviseur));
        } catch (DatabaseException $e) {
            return redirect()->to('/superviseur')
                ->withInput()
                ->with('error', 'Impossible de modifier le superviseur.');
        }

        return redirect()->to('/superviseur')
            ->with('success', 'Superviseur modifie avec succes.');
    }

    public function delete($id = null)
    {
        $this->ensureSuperviseurTable();

        $id = (int) $id;
        $superviseur = $this->superviseurModel->find($id);

        if (! $superviseur) {
            return redirect()->to('/superviseur')
                ->with('error', 'Superviseur introuvable.');
        }

        $this->superviseurModel->delete($id);

        return redirect()->to('/superviseur')
            ->with('success_delete', 'Superviseur supprime avec succes.');
    }

    protected function getValidationRules(bool $isCreate): array
    {
        return [
            'matricule' => $isCreate ? 'required|max_length[100]|is_unique[superviseur.matricule]' : 'required|max_length[100]',
            'prenom' => 'required|max_length[100]',
            'nom' => 'required|max_length[100]',
            'sexe' => 'required|in_list[Masculin,Feminin]',
            'telephone' => 'required|max_length[30]',
            'email' => 'permit_empty|valid_email|max_length[150]',
            'fonction' => 'required|in_list[IEF,IA,Central]',
            'structure_affectation' => 'required|max_length[150]',
            'region' => 'required|max_length[100]',
            'departement' => 'required|max_length[100]',
            'date_affectation' => 'required|valid_date[Y-m-d]',
            'login' => $isCreate ? 'required|max_length[100]|is_unique[superviseur.login]' : 'required|max_length[100]',
            'password' => $isCreate ? 'required|min_length[6]|max_length[255]' : 'permit_empty|min_length[6]|max_length[255]',
            'statut' => 'required|in_list[actif,inactif]',
        ];
    }

    protected function buildSuperviseurPayload(bool $isCreate, array $existing = []): array
    {
        $password = trim((string) $this->request->getPost('password'));

        $payload = [
            'matricule' => trim((string) $this->request->getPost('matricule')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'nom' => trim((string) $this->request->getPost('nom')),
            'sexe' => $this->request->getPost('sexe'),
            'telephone' => trim((string) $this->request->getPost('telephone')),
            'email' => trim((string) $this->request->getPost('email')),
            'fonction' => trim((string) $this->request->getPost('fonction')),
            'structure_affectation' => trim((string) $this->request->getPost('structure_affectation')),
            'region' => trim((string) $this->request->getPost('region')),
            'departement' => trim((string) $this->request->getPost('departement')),
            'date_affectation' => $this->request->getPost('date_affectation'),
            'login' => trim((string) $this->request->getPost('login')),
            'statut' => $this->request->getPost('statut'),
        ];

        if ($password !== '') {
            $payload['password'] = password_hash($password, PASSWORD_DEFAULT);
        } elseif (! $isCreate) {
            $payload['password'] = $existing['password'] ?? null;
        }

        return $payload;
    }

    protected function ensureSuperviseurTable(): void
    {
        $db = Database::connect();

        if ($db->tableExists('superviseur')) {
            return;
        }

        $forge = Database::forge();
        $forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'matricule' => ['type' => 'VARCHAR', 'constraint' => 100],
            'prenom' => ['type' => 'VARCHAR', 'constraint' => 100],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 100],
            'sexe' => ['type' => 'VARCHAR', 'constraint' => 20],
            'telephone' => ['type' => 'VARCHAR', 'constraint' => 30],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'fonction' => ['type' => 'VARCHAR', 'constraint' => 50],
            'structure_affectation' => ['type' => 'VARCHAR', 'constraint' => 150],
            'region' => ['type' => 'VARCHAR', 'constraint' => 100],
            'departement' => ['type' => 'VARCHAR', 'constraint' => 100],
            'date_affectation' => ['type' => 'DATE'],
            'login' => ['type' => 'VARCHAR', 'constraint' => 100],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'statut' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'actif'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $forge->addKey('id', true);
        $forge->addUniqueKey('matricule');
        $forge->addUniqueKey('login');
        $forge->createTable('superviseur', true);
    }
}
