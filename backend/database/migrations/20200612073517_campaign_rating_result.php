<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CampaignRatingRule extends Migrator
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
        $table = $this->table('campaign_rating_rule',array('engine'=>'MyISAM','charset' => 'utf8mb4'));
        $table->addColumn('title', 'string', ['limit' => 255, 'null' => false, 'comment' => '规则标题'])
            ->addColumn('desc', 'string', ['limit' => 255, 'null' => false, 'default'=> '', 'comment' => '具体规则的描述'])
            ->addColumn('content', 'string', ['limit' => 500, 'null' => false, 'comment' => '执行的规则'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'signed' => false,'null'=> true,  'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'signed' => false, 'null'=> true, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'signed' => false,'null'=> true,  'comment' => '删除时间'])
            ->create();
    }
}
