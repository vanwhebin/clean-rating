<?php

use think\migration\Seeder;

class Rule extends Seeder
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
                'title' => '桌面整洁',
                'desc' => '桌面整洁无杂物、绿植，放置武平越少越好',
                'category' => '1',
            ],
            [
                'title' => '收纳整齐',
                'desc' => '收纳箱，置物架摆放整齐',
                'category' => '1',
            ],
            [
                'title' => '责任明确',
                'desc' => '明确责任人张贴标签',
                'category' => '2',
            ],
            [
                'title' => '摆放整齐',
                'desc' => '样品摆放整齐',
                'category' => '2',
            ],
            [
                'title' => '不超区域',
                'desc' => '样品摆放不超过规定区域',
                'category' => '2',
            ],
            [
                'title' => '未置杂物',
                'desc' => '未摆放除样品外的杂物',
                'category' => '2',
            ],
            [
                'title' => '没有私物',
                'desc' => '不摆放私人物品及杂物',
                'category' => '3',
            ],
            [
                'title' => '消防通道通畅',
                'desc' => '不占用消防通道',
                'category' => '3',
            ],
        ];
        $this->table('rule')->insert($data)->save();
    }
}