<?php

use think\migration\Seeder;

class CampaignDept extends Seeder
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
                'dept_id' => 1,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 2,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 3,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 4,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 5,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 6,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 7,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 8,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 9,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 10,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 11,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 12,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 13,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 14,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 15,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 16,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],
            [
                'dept_id' => 17,
                'campaign_id' => 1,
                'create_time' =>  time()
            ],

        ];

        $this->table('campaign_dept')->insert($data)->save();
    }
}