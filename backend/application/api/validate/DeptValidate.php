<?php


namespace app\api\validate;


use app\common\validate\BaseValidate;

class ProgramValidate extends BaseValidate
{
    public $rule = [
        'programUID' => 'require|length:32'
    ];

    public $message = [
        'programUID' => '非法项目ID'
    ];

    public function sceneScore()
    {
        return $this->append('score', 'require|number|min:1|max:10');
    }


}