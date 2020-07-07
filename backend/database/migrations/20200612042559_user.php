<?php

use think\migration\Migrator;
use think\migration\db\Column;

class User extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('user',array('engine'=>'InnoDB','charset' => 'utf8mb4'));
        $table->addColumn('name', 'string', ['limit' => 255, 'default' => '', 'comment' => '名称'])
            ->addColumn('userid', 'string', ['limit' => 255, 'default' => '', 'comment' => 'wechat work id'])
            ->addColumn('email', 'string', ['limit' => 255, 'default' => '', 'comment' => 'email'])
            ->addColumn('thumb', 'string', ['limit' => 255, 'default' => '', 'comment' => ''])
            ->addColumn('dept_id', 'integer', ['limit' => 2, 'signed' => false,  'default' => 0, 'comment' => '部门'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'signed' => false,'null'=> true,  'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'signed' => false, 'null'=> true, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'signed' => false,'null'=> true,  'comment' => '删除时间'])
            ->create();

    }
}
