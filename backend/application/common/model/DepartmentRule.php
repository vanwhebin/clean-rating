<?php


namespace app\common\model;


use app\common\exception\InvalidParamException;

class DepartmentRule extends BaseModel
{
    public function rule()
    {
        return $this->belongsTo('Rule', 'rule_id', 'id');
    }

    public function dept()
    {
        return $this->belongsTo('Department', 'dept_id', 'id');
    }


    public static function getRuleCategory()
    {

    }

    /**
     * 检查是否提交分数是否超过权重
     * @param $deptID
     * @param $ruleID
     * @param $score
     * @throws InvalidParamException
     */
    public static function checkRuleWeight($deptID, $ruleID, $score)
    {
        $weight = self::where(['rule_id' => $ruleID, 'dept_id' => $deptID])->value('weight');
        if (intval($weight) < intval($score)) {
            throw new InvalidParamException(['msg' => '提交数值大于权重']);
        }
    }

}