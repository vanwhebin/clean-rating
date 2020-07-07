<?php

use think\migration\Seeder;

class Users extends Seeder
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
                'name' => '莫日华',
                'userid' => 'qy0113d4531ed09d0228f47971f0',
                'email' => 'morihua@aukeys.com',
                'thumb' => 'https://wework.qpic.cn/wwhead/duc2TvpEgSQO4BpE0WZSZyeLiaa9CFRDm6rbYdYtJ8icOlP9GPjFUXpMQSMh9pgwcXmQKmQXcqKns/0',
                'role_id' => '0',
                'gender' => 0
            ],
        ];
        $this->table('user')->insert($data)->save();
    }
}