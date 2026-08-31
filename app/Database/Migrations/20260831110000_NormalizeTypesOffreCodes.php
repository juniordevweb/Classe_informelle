<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeTypesOffreCodes extends Migration
{
    public function up()
    {
        // TINYINT plafonne les codes à 127 et transforme donc 401 en 127.
        $this->db->query("ALTER TABLE types_offre MODIFY code INT UNSIGNED NOT NULL");

        // Les anciens codes peuvent entrer en conflit avec les nouveaux codes.
        // On passe d'abord par des valeurs temporaires uniques.
        $this->db->query("UPDATE types_offre SET code = CONCAT('9', id) WHERE id IN (1, 2, 4, 12)");
        $this->db->query("UPDATE types_offre SET code = 401 WHERE id = 1");
        $this->db->query("UPDATE types_offre SET code = 403 WHERE id = 2");
        $this->db->query("UPDATE types_offre SET code = 404 WHERE id = 4");
        $this->db->query("UPDATE types_offre SET code = 402 WHERE id = 12");
    }

    public function down()
    {
        // Les valeurs précédentes ne sont pas restaurées afin de ne pas
        // réintroduire des codes non conformes.
    }
}
