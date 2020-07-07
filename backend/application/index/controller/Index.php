<?php
namespace app\index\controller;

use app\common\model\CampaignRating;

class Index
{
    public function index()
    {
        $campaignID = 1;
        $depts = [];
        echo "<pre>";
        $rawResult = CampaignRating::with(['dept'=> function($query){
            $query->field(['name', 'id'])->visible(['name']);
        }])->where(['campaign_id' => $campaignID])->order('score', 'desc')->select()->toArray();
        // var_dump($rawResult);
        foreach ($rawResult as $value){
            if (!isset($depts[$value['dept']['name']])) {
                $depts[$value['dept']['name']] = [];
            }
            // var_dump($value);
            $depts[$value['dept']['name']][] = $value['score'];
        }

        print_r($depts);
        $depts = array_map(function($item){
            $sum = array_sum($item);
            $item = round($sum / count($item), 2);
            return $item;
        }, $depts);
        print_r($depts);
        // return $depts;
    }
}
