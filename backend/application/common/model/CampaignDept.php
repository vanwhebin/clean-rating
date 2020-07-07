<?php


namespace app\common\model;


class CampaignDept extends BaseModel
{

    public function dept()
    {
        return $this->belongsTo('Department', 'dept_id', 'id');
    }

    public function campaign()
    {
        return $this->belongsTo('Campaign', 'campaign_id', 'id');
    }

    public static function getCampaignDept($campaignID)
    {
        $depts = self::with(['dept' => function($query){
            $query->field(['id', 'name']);
        }])->where([
            'status' => self::STATUS_ACTIVE,
            'campaign_id' => $campaignID
        ])->order('order', 'desc')->visible(['dept'])->select();
        return array_map(function(&$items){
            return $items['dept'];
        }, $depts->toArray());
    }


}