<?php

namespace App\Controllers;

/**
 * Pages de configuration des référentiels.
 */
class C_ConfigurationController extends BaseController
{
    public function structures()
    {
        $db = db_connect();
        $counts = [];
        $countTables = [
            'types_offre' => 'types_offre', 'langues' => 'langues_nationales', 'sites' => 'sites_structure',
            'statuts' => 'statuts_occupation', 'financements' => 'sources_financement', 'programmes' => 'programmes',
            'etats' => 'etat_structures', 'nomenclature' => 'nomenclatures', 'codification' => 'regles_codification',
        ];
        foreach ($countTables as $key => $table) {
            $counts[$key] = $db->tableExists($table) ? $db->table($table)->countAllResults() : 0;
        }

        return view('V_ConfigurationStructures', [
            'user_permissions' => $this->getUserPermissions(),
            'referentielCounts' => $counts,
        ]);
    }
}
