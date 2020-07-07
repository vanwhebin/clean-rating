<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Media extends Migrator
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
        $table = $this->table('media',array('engine'=>'MyISAM','charset' => 'utf8mb4'));
        $table->addColumn('url', 'string',array('limit' => 255, 'default'=> '','comment'=>'图片路径'))
            ->addColumn('from', 'enum',array('values' => ['1', '2'], 'default'=> '1', 'comment'=>'1 来自本地，2 来自公网'))
            ->addColumn('name', 'string',array('limit' => 100,'default'=> null, 'comment'=>'日志主题'))
            ->addColumn('size', 'integer',array('limit' => 10, 'default'=> null, 'signed' =>  false, 'comment'=>'日志主题'))
            ->addColumn('extension', 'string',array('limit' => 10,'default'=> '', 'comment'=>'后缀'))
            ->addColumn('md5', 'string',array('limit' => 32,'default'=> '', 'comment'=>'md5哈希'))
            ->addColumn('create_time', 'integer', array('limit' => 11, 'null' => true, 'signed' => false, 'comment' => '创建时间'))
            ->addColumn('update_time', 'integer', array('limit' => 11, 'null' => true, 'signed' => false, 'comment' => '更新时间'))
            ->addColumn('delete_time','integer',array('limit' => 11,'null' => true, 'signed'=> false,'comment'=>'删除'))
            ->create();
    }
}
