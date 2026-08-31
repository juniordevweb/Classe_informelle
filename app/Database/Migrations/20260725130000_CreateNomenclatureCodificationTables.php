<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNomenclatureCodificationTables extends Migration
{
    public function up()
    {
        foreach (['nomenclatures', 'regles_codification'] as $table) {
            if ($this->db->tableExists($table)) {
                continue;
            }

            $this->forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'code' => ['type' => 'VARCHAR', 'constraint' => 50],
                'libelle' => ['type' => 'VARCHAR', 'constraint' => 150],
                'prefixe' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'format' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'description' => ['type' => 'TEXT', 'null' => true],
                'actif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable($table, true);
        }
    }

    public function down()
    {
        $this->forge->dropTable('nomenclatures', true);
        $this->forge->dropTable('regles_codification', true);
    }
}
