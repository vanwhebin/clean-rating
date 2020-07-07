<?php

use think\migration\Seeder;

class Dept extends Seeder
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
               'name' => '品牌一部',
               'category' => '1',
            ],
            [
                'name' => '品牌二部',
                'category' => '1',
            ],
            [
                'name' => '品牌三部',
                'category' => '1',
            ],
            [
                'name' => '品牌四部',
                'category' => '1',
            ],
            [
                'name' => '品牌五部',
                'category' => '1',
            ],
            [
                'name' => '品牌七部',
                'category' => '1',
            ],
            [
                'name' => '品牌八部',
                'category' => '1',
            ],
            [
                'name' => '品牌九部',
                'category' => '1',
            ],
            [
                'name' => '品牌十部',
                'category' => '1',
            ],
            [
                'name' => '品牌十一部',
                'category' => '1',
            ],
            [
                'name' => '产品中心',
                'category' => '1',
            ],
            [
                'name' => '人力资源部',
                'category' => '2',
            ],
            [
                'name' => '商务部',
                'category' => '2',
            ],

            [
                'name' => '信息技术部',
                'category' => '2',
            ],

            [
                'name' => '供应链管理部',
                'category' => '2',
            ],

            [
                'name' => '物流管理部',
                'category' => '2',
            ],

            [
                'name' => '财务管理部',
                'category' => '2',
            ],

        ];
        $this->table('department')->insert($data)->save();
    }
}