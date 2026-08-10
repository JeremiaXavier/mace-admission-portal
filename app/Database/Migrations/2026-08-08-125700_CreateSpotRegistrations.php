<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpotRegistrations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'application_no' => [
                'type' => 'VARCHAR',
                'constraint' => '8',
                'unique' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'submitted'],
                'default' => 'draft',
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ],
            'mobile_no' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ],
            'time_of_reporting' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'entrance_roll_no' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'unique' => true,
            ],
            'entrance_rank' => [
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => true,
            ],
            'eligible_category' => [
                'type' => 'ENUM',
                'constraint' => ['SM','EWS','EZ','MU','BH','LA','BX','KU','VK','DV','KN','SC','ST','OEC','XS','PI','PT'],
            ],
            'admitted_elsewhere' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
            ],
            'present_college' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ],
            'present_branch' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'has_noc' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
            ],
            'has_tc_cc' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
            ],
            'option_1' => ['type' => 'ENUM', 'constraint' => ['AI','CE','CSE','DS','EEE','ECE','ME'], 'null' => true],
            'option_2' => ['type' => 'ENUM', 'constraint' => ['AI','CE','CSE','DS','EEE','ECE','ME'], 'null' => true],
            'option_3' => ['type' => 'ENUM', 'constraint' => ['AI','CE','CSE','DS','EEE','ECE','ME'], 'null' => true],
            'option_4' => ['type' => 'ENUM', 'constraint' => ['AI','CE','CSE','DS','EEE','ECE','ME'], 'null' => true],
            'option_5' => ['type' => 'ENUM', 'constraint' => ['AI','CE','CSE','DS','EEE','ECE','ME'], 'null' => true],
            'option_6' => ['type' => 'ENUM', 'constraint' => ['AI','CE','CSE','DS','EEE','ECE','ME'], 'null' => true],
            'option_7' => ['type' => 'ENUM', 'constraint' => ['AI','CE','CSE','DS','EEE','ECE','ME'], 'null' => true],
            'declaration' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
            ],
            'registered_at' => [
                'type' => 'TIMESTAMP',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('entrance_rank');
        $this->forge->addKey('eligible_category');
        $this->forge->addKey(['eligible_category', 'entrance_rank']);
        $this->forge->addKey('registered_at');
        
        $this->forge->createTable('spot_registrations');
    }

    public function down()
    {
        $this->forge->dropTable('spot_registrations');
    }
}
