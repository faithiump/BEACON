<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCurrentOfficers extends Migration
{
    public function up()
    {
        $fields = [
            'current_officers' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'default'    => 0,
                'after'      => 'contact_phone'
            ],
        ];

        // organizations table
        if (!$this->db->fieldExists('current_officers', 'organizations')) {
            $this->forge->addColumn('organizations', $fields);
        }

        // organization_applications table
        if (!$this->db->fieldExists('current_officers', 'organization_applications')) {
            $this->forge->addColumn('organization_applications', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('current_officers', 'organizations')) {
            $this->forge->dropColumn('organizations', 'current_officers');
        }

        if ($this->db->fieldExists('current_officers', 'organization_applications')) {
            $this->forge->dropColumn('organization_applications', 'current_officers');
        }
    }
}

