<?php

namespace App\Controllers;

use App\Models\M_StructureModel;
use App\Models\M_OperateurModel;
use App\Requests\UpdateStructureRequest;
use App\Policies\StructurePolicy;
use App\Services\StructureCodeService;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;

class C_StructureController extends BaseController
{
    protected $structureModel;
    protected $operateurModel;
    protected $updateRequest;
    protected $policy;
    protected $codeService;

    public function __construct()
    {
        $this->structureModel = new M_StructureModel();
        $this->operateurModel = new M_OperateurModel();
        $this->updateRequest = new UpdateStructureRequest();
        $this->policy = new StructurePolicy();
        $this->codeService = new StructureCodeService();
    }

    /**
     * Enregistre une nouvelle structure.
     */
    public function store()
    {
        $user = session()->get();

        if (!$this->policy->create($user)) {
            return redirect()->to('/structures')->with('error', 'Accès non autorisé.');
        }

        $etats = $this->activeEtats();
        $activeEtatCodes = array_column($etats, 'code');
        if ($activeEtatCodes === []) {
            return redirect()->to('/structures')->with('error', 'Aucun état actif n’est disponible.');
        }

        $data = [
            'code_structure' => $this->codeService->generateCode(),
            'nom_structure' => trim((string) $this->request->getPost('nom_structure')),
            'region' => trim((string) $this->request->getPost('region')),
            'departement' => trim((string) $this->request->getPost('departement')),
            'commune' => trim((string) $this->request->getPost('commune')),
            'quartier' => trim((string) $this->request->getPost('quartier')),
            'ia' => trim((string) $this->request->getPost('ia')),
            'ief' => trim((string) $this->request->getPost('ief')),
            'latitude' => $this->normalizeDecimal($this->request->getPost('latitude')),
            'longitude' => $this->normalizeDecimal($this->request->getPost('longitude')),
            'langue_nationale' => trim((string) $this->request->getPost('langue_nationale')),
            'operateur_id' => (int) $this->request->getPost('operateur_id'),
            'etat' => $this->request->getPost('etat') ?: $activeEtatCodes[0],
        ];

        $rules = [
            'code_structure' => 'required|is_unique[structures.code_structure]|exact_length[10]',
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

        if (! $this->validateData($data, $rules)) {
            return redirect()->to('/structures')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            if (! $this->structureModel->insert($data)) {
                return redirect()->to('/structures')
                    ->withInput()
                    ->with('error', 'Impossible de creer la structure.');
            }
        } catch (DatabaseException $e) {
            return redirect()->to('/structures')
                ->withInput()
                ->with('error', 'Impossible de creer la structure.');
        }

        return redirect()->to('/structures')
            ->with('swal_success', 'Structure ajoutée avec succès.');
    }

    /**
     * Affiche la liste des structures.
     */
    public function index()
    {
        $user = session()->get();

        if (!$this->policy->view($user)) {
            return redirect()->to('/dashboard')->with('error', 'Accès non autorisé.');
        }

        $perPage = 3;
        $page = $this->request->getGet('page') ?? 1;
        $search = $this->request->getGet('search');
        $region = $this->request->getGet('region');
        $etat = $this->request->getGet('etat');
        $operateur = $this->request->getGet('operateur');

        $query = $this->structureModel->select('structures.*, operateur.nom_organisation as nom_operateur, COUNT(classes.id) as nombre_classes')
                                      ->join('operateur', 'operateur.id = structures.operateur_id')
                                      ->join('classes', 'classes.structure_id = structures.id', 'left')
                                      ->groupBy('structures.id');

        if ($search) {
            $query->like('nom_structure', $search);
        }

        if ($region) {
            $query->where('region', $region);
        }

        if ($etat) {
            $query->where('etat', $etat);
        }

        if ($operateur) {
            $query->where('operateur_id', $operateur);
        }

        $structures = $query->paginate($perPage, 'structures', $page);
        $pager = $this->structureModel->pager;

        $operateurs = $this->operateurModel->findAll();
        $etats = $this->activeEtats();
        $languesNationales = db_connect()->table('langues_nationales')
            ->where('actif', 1)
            ->orderBy('nom', 'ASC')
            ->get()
            ->getResultArray();
        $ias = db_connect()->table('atlas')
            ->where('id_hierarchie', 9)
            ->orderBy('libelle_structure', 'ASC')
            ->get()
            ->getResultArray();
        $atlasRegions = db_connect()->table('atlas')
            ->where('id_hierarchie', 8)
            ->orderBy('libelle_structure', 'ASC')
            ->get()
            ->getResultArray();
        $regions = $this->structureModel->distinct()->select('region')->findAll();

        // Compteurs
        $totalStructures = $this->structureModel->countAll();
        $structuresOuvertes = $this->structureModel->where('etat', 'OUVERT')->countAllResults();
        $structuresFermees = $this->structureModel->where('etat', 'FERME')->countAllResults();
        $structuresValidees = $this->structureModel->where('etat', 'VALIDE')->countAllResults();

        return view('V_structures_index', [
            'user_permissions' => $this->getUserPermissions(),
            'structures' => $structures,
            'pager' => $pager,
            'operateurs' => $operateurs,
            'etats' => $etats,
            'languesNationales' => $languesNationales,
            'ias' => $ias,
            'atlasRegions' => $atlasRegions,
            'regions' => array_column($regions, 'region'),
            'totalStructures' => $totalStructures,
            'structuresOuvertes' => $structuresOuvertes,
            'structuresFermees' => $structuresFermees,
            'structuresValidees' => $structuresValidees,
        ]);
    }

    /**
     * Affiche les détails d'une structure.
     */
    public function show($id)
    {
        $user = session()->get();

        $structure = $this->structureModel->find($id);

        if (!$structure || !$this->policy->view($user)) {
            return redirect()->to('/structures')->with('error', 'Structure non trouvée ou accès non autorisé.');
        }

        $operateur = $this->operateurModel->find($structure['operateur_id']);
        
        // Compter les classes liées à cette structure
        $db = \Config\Database::connect();
        $nombreClasses = $db->table('classes')->where('structure_id', $id)->countAllResults();
        
        // Pour les apprenants, on initialise à 0 puisque la relation n'existe pas actuellement
        $nombreApprenants = 0;

        return view('V_structures_show', [
            'structure' => $structure,
            'operateur' => $operateur,
            'nombreClasses' => $nombreClasses,
            'nombreApprenants' => $nombreApprenants,
        ]);
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit($id)
    {
        $user = session()->get();

        $structure = $this->structureModel->find($id);

        if (!$structure || !$this->policy->update($user, $this->structureModel)) {
            return redirect()->to('/structures')->with('error', 'Structure non trouvée ou accès non autorisé.');
        }

        $operateurs = $this->operateurModel->findAll();
        $etats = $this->activeEtats();

        return view('V_structures_edit', [
            'structure' => $structure,
            'operateurs' => $operateurs,
            'etats' => $etats,
        ]);
    }

    /**
     * Met à jour une structure.
     */
    public function update($id)
    {
        $user = session()->get();

        $structure = $this->structureModel->find($id);

        if (!$structure || !$this->policy->update($user, $this->structureModel)) {
            return redirect()->to('/structures')->with('error', 'Structure non trouvée ou accès non autorisé.');
        }

        $data = $this->request->getPost();

        if (!$this->updateRequest->validate($data, array_column($this->activeEtats(), 'code'))) {
            return redirect()->back()->withInput()->with('errors', $this->updateRequest->getErrors());
        }

        if ($this->structureModel->update($id, $data)) {
            return redirect()->to('/structures')->with('success', 'Structure mise à jour avec succès.');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour de la structure.');
    }

    /**
     * Supprime une structure (soft delete).
     */
    public function destroy($id)
    {
        $user = session()->get();

        $structure = $this->structureModel->find($id);

        if (!$structure || !$this->policy->delete($user, $this->structureModel)) {
            return redirect()->to('/structures')->with('error', 'Structure non trouvée ou accès non autorisé.');
        }

        if ($this->structureModel->delete($id)) {
            return redirect()->to('/structures')->with('success', 'Structure supprimée avec succès.');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression de la structure.');
    }

    /**
     * API: Retourne les données d'une structure en JSON.
     */
    public function apiGet($id)
    {
        $user = session()->get();

        $structure = $this->structureModel->find($id);

        if (!$structure || !$this->policy->view($user)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Structure non trouvée ou accès non autorisé.'
            ]);
        }

        $operateur = $this->operateurModel->find($structure['operateur_id']);

        // Compter les classes liées à cette structure
        $db = \Config\Database::connect();
        $nombreClasses = $db->table('classes')->where('structure_id', $id)->countAllResults();

        $data = array_merge($structure, [
            'operateur_nom' => $operateur['nom_organisation'] ?? 'N/A',
            'nombre_classes' => $nombreClasses,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Normalise une valeur décimale optionnelle.
     */
    private function normalizeDecimal($value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /**
     * Retourne uniquement les états activés dans le référentiel.
     * Les identifiants historiques correspondent aux valeurs stockées dans structures.etat.
     */
    private function activeEtats(): array
    {
        $codesById = [
            1 => 'EN_ATTENTE',
            2 => 'VALIDE',
            3 => 'OUVERT',
            4 => 'FERME',
            5 => 'GELE',
        ];

        $etats = db_connect()->table('etat_structures')
            ->where('actif', 1)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        return array_values(array_filter(array_map(static function (array $etat) use ($codesById): ?array {
            $code = $codesById[(int) ($etat['id'] ?? 0)] ?? null;
            return $code === null ? null : [
                'code' => $code,
                'libelle' => (string) $etat['libelle'],
            ];
        }, $etats)));
    }
}
