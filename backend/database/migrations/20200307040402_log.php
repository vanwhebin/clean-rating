<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Log extends Migrator
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
        $table = $this->table('log',array('engine'=>'MyISAM','charset' => 'utf8mb4'));
        $table->addColumn('log', 'string',array('limit' => 5000, 'default'=> '','comment'=>'日志记录'))
            ->addColumn('type', 'string',array('limit' => 50, 'default'=> '', 'comment'=>'日志分类'))
            ->addColumn('topic', 'string',array('limit' => 100,'default'=> '', 'comment'=>'日志主题'))
            ->addColumn('create_time', 'integer', ['limit' => 11, 'signed' => false,'null'=> true,  'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'signed' => false, 'null'=> true, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'signed' => false,'null'=> true,  'comment' => '删除时间'])
            ->create();
    }
}
