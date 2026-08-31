<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;

/**
 * CRUD commun aux référentiels de configuration des structures.
 */
class C_ReferentielController extends BaseController
{
    private array $referentiels = [
        'types-offre' => [
            'title' => "Types d'offre", 'table' => 'types_offre', 'label' => 'libelle',
            'fields' => ['libelle', 'code', 'prefixe', 'description', 'actif'],
            'labels' => ['libelle' => 'Libellé', 'code' => 'Code', 'prefixe' => 'Préfixe', 'description' => 'Description', 'actif' => 'Actif'],
            'types' => ['code' => 'text'],
        ],
        'langues' => [
            'title' => 'Langues nationales', 'table' => 'langues_nationales', 'label' => 'nom',
            'fields' => ['nom', 'code', 'actif'],
            'labels' => ['nom' => 'Nom', 'code' => 'Code', 'actif' => 'Actif'],
        ],
        'sites' => [
            'title' => 'Sites abritants', 'table' => 'sites_structure', 'label' => 'libelle',
            'fields' => ['libelle', 'description', 'actif'],
            'labels' => ['libelle' => 'Libellé', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'statuts' => [
            'title' => "Statuts d'occupation", 'table' => 'statuts_occupation', 'label' => 'libelle',
            'fields' => ['libelle', 'description', 'actif'],
            'labels' => ['libelle' => 'Libellé', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'financements' => [
            'title' => 'Sources de financement', 'table' => 'sources_financement', 'label' => 'libelle',
            'fields' => ['libelle', 'description', 'actif'],
            'labels' => ['libelle' => 'Libellé', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'programmes' => [
            'title' => 'Programmes', 'table' => 'programmes', 'label' => 'nom',
            'fields' => ['nom', 'description', 'actif'],
            'labels' => ['nom' => 'Nom', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'etats' => [
            'title' => 'États des structures', 'table' => 'etat_structures', 'label' => 'libelle',
            'fields' => ['libelle', 'description', 'actif'],
            'labels' => ['libelle' => 'Libellé', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'nomenclature' => [
            'title' => 'Nomenclature', 'table' => 'nomenclatures', 'label' => 'libelle',
            'fields' => ['code', 'libelle', 'description', 'actif'],
            'labels' => ['code' => 'Code', 'libelle' => 'Libellé', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'codification' => [
            'title' => 'Codification', 'table' => 'regles_codification', 'label' => 'libelle',
            'fields' => ['code', 'libelle', 'prefixe', 'format', 'description', 'actif'],
            'labels' => ['code' => 'Code', 'libelle' => 'Libellé', 'prefixe' => 'Préfixe', 'format' => 'Format', 'description' => 'Description', 'actif' => 'Actif'],
        ],
    ];

    public function index(string $slug)
    {
        $config = $this->config($slug);
        $items = $this->db()->table($config['table'])->orderBy('id', 'DESC')->get()->getResultArray();

        return view('V_Referentiel', [
            'user_permissions' => $this->getUserPermissions(),
            'config' => $config,
            'slug' => $slug,
            'items' => $items,
        ]);
    }

    public function store(string $slug)
    {
        $config = $this->config($slug);
        $payload = $this->payload($config);

        if ($payload === false) {
            return redirect()->back()->withInput()->with('error', $this->payloadError($config));
        }

        $this->db()->table($config['table'])->insert($payload);

        return redirect()->to('parametres/' . $slug)->with('success', $config['title'] . ' ajouté(e) avec succès.');
    }

    public function update(string $slug, int $id)
    {
        $config = $this->config($slug);
        $payload = $this->payload($config, $id);

        if ($payload === false) {
            return redirect()->back()->withInput()->with('error', $this->payloadError($config));
        }

        $this->db()->table($config['table'])->where('id', $id)->update($payload);

        return redirect()->to('parametres/' . $slug)->with('success', $config['title'] . ' modifié(e) avec succès.');
    }

    public function delete(string $slug, int $id)
    {
        $config = $this->config($slug);
        $this->db()->table($config['table'])->where('id', $id)->delete();

        return redirect()->to('parametres/' . $slug)->with('success', $config['title'] . ' supprimé(e) avec succès.');
    }

    private function config(string $slug): array
    {
        if (! isset($this->referentiels[$slug])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Référentiel introuvable.');
        }

        return $this->referentiels[$slug];
    }

    private function payload(array $config, ?int $id = null): array|false
    {
        $payload = [];

        foreach ($config['fields'] as $field) {
            if ($field === 'actif') {
                $payload[$field] = $this->request->getPost($field) ? 1 : 0;
                continue;
            }

            $value = trim((string) $this->request->getPost($field));
            if ($field === $config['label'] && $value === '') {
                return false;
            }
            $payload[$field] = $value === '' ? null : $value;
        }

        if (($config['table'] ?? null) === 'types_offre') {
            $suffix = $this->offerCodeSuffix((string) ($payload['libelle'] ?? ''))
                ?? $this->nextOfferSuffix($id);

            // Le code envoyé par le formulaire et l'ancien code sont toujours
            // ignorés : chaque enregistrement reçoit un code conforme généré ici.
            $payload['code'] = $this->nextOfferCode($suffix, $id);
        }

        return $payload;
    }

    private function payloadError(array $config): string
    {
        if (($config['table'] ?? null) !== 'types_offre') {
            return 'Le libellé est obligatoire.';
        }

        return "Le code de l'offre est généré automatiquement avec le préfixe 4 et le suffixe correspondant à l'offre.";
    }

    private function nextOfferCode(int $suffix, ?int $excludeId = null): string
    {
        $query = $this->db()->table('types_offre')->select('id, code');
        if ($excludeId !== null) {
            $query->where('id !=', $excludeId);
        }

        $maxSequence = -1;
        foreach ($query->get()->getResultArray() as $item) {
            if (preg_match('/^4(\d*)' . $suffix . '$/', (string) ($item['code'] ?? ''), $matches) === 1) {
                $maxSequence = max($maxSequence, $matches[1] === '' ? 0 : (int) $matches[1]);
            }
        }

        return '4' . ($maxSequence + 1) . $suffix;
    }

    private function nextOfferSuffix(?int $excludeId = null): int
    {
        $query = $this->db()->table('types_offre')->select('id, code');
        if ($excludeId !== null) {
            $query->where('id !=', $excludeId);
        }

        $maxSuffix = 0;
        foreach ($query->get()->getResultArray() as $item) {
            if (preg_match('/^4\d*([1-9])$/', (string) ($item['code'] ?? ''), $matches) === 1) {
                $maxSuffix = max($maxSuffix, (int) $matches[1]);
            }
        }

        return $maxSuffix + 1;
    }

    private function offerCodeSuffix(string $label): ?int
    {
        $label = strtoupper(strtr(trim($label), [
            'À' => 'A', 'Â' => 'A', 'Ä' => 'A', 'Ç' => 'C', 'É' => 'E',
            'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Î' => 'I', 'Ï' => 'I',
            'Ô' => 'O', 'Ö' => 'O', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
        ]));

        return match (true) {
            str_contains($label, 'CAF') => 1,
            str_contains($label, 'ECB') => 2,
            str_contains($label, 'PASSERELLE') => 3,
            str_contains($label, 'DAARA') => 4,
            default => null,
        };
    }

    private function db(): BaseConnection
    {
        return db_connect();
    }
}
