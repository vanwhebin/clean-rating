<?php

namespace app\common\model;

use PDOStatement;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;
use think\model\relation\BelongsTo;
use think\model\relation\HasManyThrough;
use think\Paginator;

class Campaign extends BaseModel
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    /**
     * 每次活动的评分的规则
     * @return BelongsTo
     */
    // public function rule()
    // {
    //     return $this->belongsTo('CampaignRatingRule', 'rating_rule_id', 'id');
    // }

    /**
     * 每次活动评分规则的维度
     * @return HasManyThrough
     */
    public function tag()
    {
        return $this->hasManyThrough('RatingTag', 'CampaignRatingTag', 'tag_id', 'campaign_id');
    }

    /**
     * 通过uuid查找到对应的campaign记录
     * @param $campaignID
     * @return array|PDOStatement|string|Model
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function findByUid($campaignID)
    {
        return self::where(['id' => $campaignID])->findOrFail();
    }

    /**
     * 活动列表
     * @param int $pageSize
     * @param int $pageNum
     * @return Paginator
     * @throws DbException
     */
    public function getList($pageSize=10, $pageNum=1)
    {
        return $this->order('id', 'DESC')->paginate($pageSize, false, ['page' => $pageNum]);
    }



    public function getRaters($rule, $campaignID)
    {
        // return $rule;
        $teamIDs = Department::where(['campaign_id' => $campaignID, 'status' => Department::ACTIVE])->column('team_id');
        $teamRaters = array_column(array_values(Team::getTeamInfo($teamIDs)->toArray()), 'rating');
        // return $teamRaters;
        // if (!$rule->content) { // 将外键方式修改为当前表方式
        if (!$rule) {
            // 获取评委， 评委来自参赛队伍表
            $raters = array_map(function($item){
                $item['weight'] = 0;
                return $item;
            },$teamRaters);
        } else {
            $existedRaters = json_decode($rule, true);
            $existedRaterIDs = array_keys($existedRaters);
            $raters = array_map(function($item) use ($existedRaterIDs, $existedRaters) {
                foreach ($existedRaterIDs as $r) {
                    if ($r == $item['name']) {
                        $item['weight'] = $existedRaters[$r];
                    } else {
                        $item['weight'] = 0;
                    }
                }
                return $item;
            },$teamRaters);
        }
        return $raters;
    }

    /**
     * 查找到当前最近的活动
     * @return array|PDOStatement|string|Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function findLatest()
    {
        return self::where( 'create_time', '<', time())
            ->order('create_time', 'DESC')
            ->field(['id', 'title', 'desc'])
            ->find();
    }

    public function createOne($data)
    {
        // 创建活动
        $startTime = strtotime($data['date']. ' ' .$data['start_time']);
        $endTime = strtotime($data['date']. ' ' .$data['end_time']);
        return self::create([
            'title' => !empty($data['title']) ? $data['title'] : '',
            'start_time' => $startTime,
            'end_time' => $endTime,
            'desc' => !empty($data['desc']) ? htmlspecialchars($data['desc']) : ''
        ]);
    }

    /**
     * @param $campaign
     * @return mixed
     */
    public function updateUID($campaign)
    {
        $uuid = createUID($campaign->id, config('secure.campaign_salt'));
        $campaign->uuid = $uuid;
        return $campaign->save();
    }

    /**
     * 更新活動信息
     * @param $campaign
     * @param $data
     * @return mixed
     */
    public function updateInfo($campaign, $data)
    {
        // 更新活動信息
        $campaign->title = trim($data['title']);
        $campaign->desc = trim($data['desc']);
        $campaign->start_time = strtotime($data['date']. '' . $data['start_time']);
        $campaign->end_time = strtotime($data['date']. '' . $data['end_time']);
        return $campaign->save();
    }






}
