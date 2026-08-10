<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAllottedCourseToRegistrations extends Migration
{
    public function up()
    {
        $fields = [
            'allotted_course' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
                'default'    => null,
                'after'      => 'eligible_category'
            ],
        ];
        $this->forge->addColumn('spot_registrations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('spot_registrations', 'allotted_course');
    }
}
