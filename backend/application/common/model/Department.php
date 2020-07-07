<?php

namespace app\common\model;

use app\common\exception\InvalidParamException;
use PDOStatement;
use think\Collection;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\facade\Hook;
use think\Model;
use think\model\relation\BelongsTo;
use think\model\relation\HasManyThrough;

class Department extends BaseModel
{
    const DEFAULT_RATING = 0;

	/**
	 * 关联活动
	 * @return BelongsTo
	 */
	public function campaign()
	{
		return $this->belongsTo('Campaign', 'campaign_id','id');
	}




    /**
     * @param $campaignID
     * @return array|PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getAllPrograms($campaignID)
    {
        return self::with(['campaign' => function($query) {
            $query->field(['title', 'id', 'uuid'])->hidden(['id']);
        } ,'product' => function($query){
            $query->field(['name', 'id'])-> visible(['name']);
        }])->visible(['title', 'desc', 'memo', 'uuid', 'id'])
            ->where(['campaign_id' => $campaignID, 'status' => Department::ACTIVE])
            ->select();
    }

    /**
     * @param $campaignID
     * @param $userID
     * @return array|PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getRating($campaignID, $userID)
    {
        return DeptRating::where(['campaign_id' => $campaignID, 'rating_user_id' => $userID])
            ->select();
    }


    /**
     * @param $campaignID
     * @param $userID
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getRatingDept($campaignID, $userID)
    {
        $programs = self::getAllPrograms($campaignID)->toArray();
        $programsRatings = self::getRating($campaignID, $userID)->toArray();
        $status = DeptRating::where(['status' => DeptRating::DISABLED, 'campaign_id' => $campaignID, 'rating_user_id' => $userID])
            ->value('status');
        $programs = array_map(function(&$item) use ($programsRatings) {
            foreach($programsRatings as $rating) {
                if ($rating['program_id'] == $item['id']) {
                    $item['self_rating'] = $rating['score'];
                    $item['rating_status'] = $rating['status'];
                }
            }
            unset($item['id']);
            return $item;
        }, $programs);

        return ['status' => $status === 1 ? 1 : 0, 'programs' => $programs,];
    }


    /**
     * 部门ID
     * @param $deptID
     * @return array|PDOStatement|string|Model
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function findByUid($deptID)
    {
        return self::where(['id' => $deptID, 'status' => self::STATUS_ACTIVE])->findOrFail();
    }


    /**
     * @param $campaignID
     * @return array|PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getRankedResults($campaignID)
    {
        // 获取所有项目的排名结果
        return $this->where(['status' => self::STATUS_ACTIVE, 'campaign_id' => $campaignID])
            ->field(['id', 'title', 'campaign_id', 'rating'])
            ->visible(['title', 'rating'])
            ->order('rating', 'DESC')
            ->limit(0,8)
            ->select()->each(function($item){
            $item->rating = $item->rating / 100;
            return $item;
        });

    }

    /**
     * 创建项目记录
     * @param $campaign
     * @param $data
     * @param $user
     * @return Department|bool
     */
    public function createOne($campaign, $data, $user)
    {
        Db::startTrans();
        try{
            $program = self::create([
                'title' => trim($data['title']),
                'desc' => trim($data['desc']),
                'campaign_id' => $campaign->id,
                'user_id' => $user->id,
                'memo' => !empty($data['memo'])? trim($data['memo']) : '',
            ]);
            $team = Team::createOne(array_merge($data, ['campaign_id' => intval($campaign->id)]));
            $program->uuid = createUID($campaign->id, config('secure.program_salt'));
            $program->team_id = $team->id;
            $program->save();
        } catch(DbException | Exception $exception){
            Db::rollback();
            logger(get_class($exception), $exception->getMessage(), __CLASS__.'#'.__METHOD__);
            return false;
        }
        Db::commit();
        return true;
    }

    /**
     * 查找是否有未完成计算的评分项目
     * @param $deptID
     * @param $ruleID
     * @param $score
     * @throws DataNotFoundException
     * @throws DbException
     * @throws InvalidParamException
     * @throws ModelNotFoundException
     */
    public static function checkRuleWeight($deptID, $ruleID, $score)
    {
        $ruleWeight = self::where([
            'rule_id' => $ruleID,
            'status' => self::STATUS_ACTIVE,
            'dept_id' => $deptID,
        ])->findOrFail();
        if (intval($ruleWeight->weight) < intval($score)) {
            throw new InvalidParamException(['msg' => '评分数据有误']);
        }
    }

    /**
     * @param $deptID
     * @return array|PDOStatement|string|Model
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function findOne($deptID)
    {
        return self::findOrFail($deptID);
    }

    public function getRules()
    {
        $where = ['status' => self::STATUS_ACTIVE, 'dept_id' => $this->id];
        $fields = ['id', 'weight','rule_id', 'dept_id'];
        $connectedRules = DepartmentRule::with(['rule' => function($query) {
            $query->field(['id', 'title', 'desc','category']);
        }])->where($where)->order('order', 'desc')
            ->field($fields)
            ->select()
            ->toArray();
        $rulesArr = [];
        foreach($connectedRules as $item) {
            if (!isset($rulesArr[$item['rule']['category']])) {
                $rulesArr[$item['rule']['category']] = [];
            }
            $rulesArr[$item['rule']['category']][] = [
                'weight' => $item['weight'],
                'rule_id' => $item['rule_id'],
                'title' => $item['rule']['title'],
                'desc' => $item['rule']['desc']
            ];
        }

        return $rulesArr;
    }





}
