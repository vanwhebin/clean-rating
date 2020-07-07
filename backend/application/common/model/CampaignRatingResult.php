<?php


namespace app\common\model;


use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class CampaignRatingResult extends BaseModel
{
    /**
     * 给部门在限定的条件下打分
     * @param $campaign
     * @param $dept
     * @param $user
     * @param $score
     * @param $ruleID
     * @return CampaignRatingResult|bool
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function postScore($campaign, $dept, $user, $score, $ruleID)
    {
        $where = [
            'dept_id' => $dept->id,
            'campaign_id' => $campaign->id,
            'user_id' => $user['id'],
            'rule_id' => $ruleID,
        ];
        $model = self::where($where)->findOrEmpty();
        if ($model->isEmpty()) {
            return self::create(array_merge($where, [ 'score' => $score]));
        } else {
            $model->score = $score;
            return $model->save();
        }
    }

    /**
     * 查询获得已评分的部门ID
     * @param $campaignID
     * @param $userID
     * @return array
     */
    public static function getRatedDeptIDs($campaignID, $userID)
    {
        return self::where(['campaign_id' => $campaignID, 'user_id' => $userID])->column('dept_id');
    }

    /**
     * 获取已经评分的规则ID列表
     * @param $campaignID
     * @param $deptID
     * @param $userID
     * @return array
     */
    public static function getRatingRuleID($campaignID, $deptID, $userID)
    {
        return self::where(['campaign_id' => $campaignID, 'dept_id' => $deptID, 'user_id'=> $userID])->column('rule_id');
    }

}