<?php

use think\migration\Migrator;
use think\migration\db\Column;

class DepartmentRule extends Migrator
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
        $table = $this->table('department_rule',array('engine'=>'MyISAM','charset' => 'utf8mb4'));
        $table->addColumn('rule_id', 'integer', ['limit' => 3, 'signed' => false, 'comment' => '规则表ID'])
            ->addColumn('dept_id', 'integer', ['limit' => 3, 'signed' => false, 'comment' => '部门ID'])
            ->addColumn('weight', 'integer', ['limit' => 3, 'signed' => false,'comment' => '占比'])
            ->addColumn('status', 'integer', ['limit' => 1, 'signed' => false, 'default' => 1, 'comment' => '状态'])
            ->addColumn('order', 'integer', ['limit' => 3, 'signed' => false, 'default' => 0, 'comment' => '排序'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'signed' => false,'null'=> true,  'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'signed' => false, 'null'=> true, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 11, 'signed' => false,'null'=> true,  'comment' => '删除时间'])
            ->create();
    }
}
