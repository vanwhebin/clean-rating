<?php


namespace app\api\validate;


use app\common\validate\BaseValidate;

class DeptValidate extends BaseValidate
{
    public $rule = [
        'deptID' => 'require|isPositiveInteger'
    ];

    public $message = [
        'deptID' => '非法部门ID'
    ];

    public function sceneScore()
    {
        $this->append('campaignID', 'require|isPositiveInteger');
        $this->append('ruleID', 'require|isPositiveInteger');
        return $this->append('score', 'require|number|min:1|max:100');
    }

    public function sceneRule()
    {
        return $this->append('campaignID', 'require|isPositiveInteger');
    }



}