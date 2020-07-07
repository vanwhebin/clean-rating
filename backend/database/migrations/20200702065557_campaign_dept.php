<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CampaignDept extends Migrator
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
        $table = $this->table('campaign_dept',array('engine'=>'MyISAM','charset' => 'utf8mb4'));
        $table->addColumn('dept_id', 'integer', ['limit' => 3, 'signed' => false, 'comment' => '部门表ID'])
            ->addColumn('campaign_id', 'integer', ['limit' => 3, 'signed' => false,  'comment' => 'campaign表ID'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1,'signed' => false,  'comment' => '状态'])
            ->addColumn('order', 'integer', ['limit' => 3, 'default' => 0,'signed' => false,  'comment' => '排序'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'signed' => false,'null'=> true,  'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'signed' => false, 'null'=> true, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'signed' => false,'null'=> true,  'comment' => '删除时间'])
            ->create();

    }
}
