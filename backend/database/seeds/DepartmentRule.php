<?php

use think\migration\Seeder;

class DepartmentRule extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'rule_id' => 1,
                'dept_id' => 11,
                'weight' => 25,
                'create_time' => time(),
            ],
            [
                'rule_id' => 2,
                'dept_id' => 11,
                'weight' => 15,
                'create_time' => time(),
            ],
            [
                'rule_id' => 3,
                'dept_id' => 11,
                'weight' => 5,
                'create_time' => time(),
            ],
            [
                'rule_id' => 4,
                'dept_id' => 11,
                'weight' => 15,
                'create_time' => time(),
            ],
            [
                'rule_id' => 5,
                'dept_id' => 11,
                'weight' => 10,
                'create_time' => time(),
            ],
            [
                'rule_id' => 6,
                'dept_id' => 11,
                'weight' => 10,
                'create_time' => time(),
            ],
            [
                'rule_id' => 7,
                'dept_id' => 11,
                'weight' => 10,
                'create_time' => time(),
            ],
            [
                'rule_id' => 8,
                'dept_id' => 11,
                'weight' => 10,
                'create_time' => time(),
            ],

        ];
        $this->table('department_rule')->insert($data)->save();
    }
}