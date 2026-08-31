<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddConfigurationStructuresMenu extends Migration
{
    public function up()
    {
        $menus = $this->db->table('menus');
        $parametres = $menus
            ->groupStart()
            ->where('id', 6)
            ->orWhere('LOWER(nom_menu)', 'paramètres')
            ->orWhere('LOWER(nom_menu)', 'parametres')
            ->groupEnd()
            ->get()
            ->getRowArray();

        if (! $parametres) {
            return;
        }

        $sousMenus = $this->db->table('sous_menus');
        $exists = $sousMenus
            ->where('url', '/parametres/configuration-structures')
            ->orWhere('url', 'parametres/configuration-structures')
            ->countAllResults();

        if ($exists > 0) {
            return;
        }

        $sousMenus->insert([
            'menu_id' => (int) $parametres['id'],
            'nom_sous_menu' => 'Configuration des Structures',
            'url' => '/parametres/configuration-structures',
            'icon' => 'fa fa-cogs',
            'ordre' => 4,
            'permission_id' => 1,
            'statut' => 1,
        ]);
    }

    public function down()
    {
        $this->db->table('sous_menus')
            ->groupStart()
            ->where('url', '/parametres/configuration-structures')
            ->orWhere('url', 'parametres/configuration-structures')
            ->groupEnd()
            ->delete();
    }
}
