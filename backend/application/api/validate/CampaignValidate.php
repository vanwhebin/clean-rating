<?php


namespace app\api\validate;


use app\common\validate\BaseValidate;

class CampaignValidate extends BaseValidate
{
    public $rule = [
        'campaignUID' => 'require|length:32'
    ];

    public $message = [
        'campaignUID' => '非法活动ID'
    ];

    public function sceneUpdate()
    {
        $this->append('desc', 'require|isNotEmpty');
        $this->append('date', 'require|isNotEmpty');
        $this->append('start_time', 'require|isNotEmpty');
        $this->append('end_time', 'require|isNotEmpty');
        return $this->append('title', 'require|isNotEmpty');
    }

}