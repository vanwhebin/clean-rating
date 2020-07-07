<?php


namespace app\common\model;


use app\common\exception\InvalidParamException;
use PDOStatement;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;

class CampaignRating extends BaseModel
{
    public function dept()
    {
        return $this->belongsTo('Department', 'dept_id', 'id');
    }

    public function campaign()
    {
        return $this->belongsTo('Campaign', 'campaign_id', 'id');
    }

    /**
     * 当前活动的排名
     * @param $data
     * @return array|PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getRankingResult($data)
    {
        $depts = [];
        $rawResult = self::with(['dept'=> function($query){
            $query->field(['name', 'id'])->visible(['name']);
        }])->where(['campaign_id' => $data['campaignID']])->order('score', 'desc');
        if (!empty($data['pageSize'])) {
            $rawResult->limit(0, intval($data['pageSize']));
        }

        $rawResult = $rawResult->select();

        foreach ($rawResult as $value){
            if (!isset($depts[$value['dept']['name']])) {
                $depts[$value['dept']['name']] = [];
            }
            $depts[$value['dept']['name']][] = $value['score'];
        }
        $depts = array_map(function($item){
            $sum = array_sum($item);
            $item = round($sum / count($item), 2);
            return $item;
        }, $depts);
        return $depts;
    }

    /**
     * 获取已评分的部门ID
     * @param $campaignID
     * @param $userID
     * @return array
     */
    public static function getRatedDept($campaignID, $userID)
    {
        return self::where(['campaign_id' => $campaignID, 'user_id' => $userID])->column('dept_id');
    }


    /**
     * 计算当前部门当前人员的评分结果
     * @param $campaignID
     * @param $deptID
     * @param $userID
     * @return CampaignRating|array|PDOStatement|string|Model
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function updateStatus($campaignID, $deptID, $userID)
    {
        $where = ['campaign_id' => $campaignID, 'dept_id' => $deptID, 'user_id' => $userID];
        $model = self::where($where)->findOrEmpty();
        $scores = CampaignRatingResult::where($where)->column('score');
        $calculateScores = array_reduce($scores, function($calculateScore, $item) {
            $calculateScore += $item;
            return $calculateScore;
        });

        if (!$model->isEmpty()) {
            return true;
            // throw new InvalidParamException(['msg' => '评分已保存，不可重复提交']);
        } else {
            $model = self::create([
                'dept_id' => $deptID,
                'campaign_id' => $campaignID,
                'user_id' => $userID,
                'score' => $calculateScores
            ]);
        }
        return $model;
    }

}