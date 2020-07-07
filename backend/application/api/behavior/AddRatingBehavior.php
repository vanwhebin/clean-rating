<?php


namespace app\api\behavior;


use app\common\model\Department;
use app\common\model\DeptRating;
use app\common\model\User;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class AddRatingBehavior
{
    /**
     * * 评分同时，同时再评分表中增加对应的评分记录
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function run(array $params)
    {
        $campaign = $params['campaign'];
        $ratingID = $params['rating'];

        $programRating = new DeptRating();
        $where = [
            'status' => Department::ACTIVE,
            'campaign_id' => $campaign->id
        ];
        $programIDs = Department::where($where)->column('id');
        $arr = [];
        array_map(function($item) use (&$arr, $campaign, $ratingID){
            $arr[] = [
                'program_id' => $item,
                'campaign_id' => $campaign['id'],
                'rating_user_id' =>  $ratingID,
            ];
            return $item;
        }, $programIDs);
        // 批量新增
        $programRating->saveAll($arr);
        return true;
    }
}