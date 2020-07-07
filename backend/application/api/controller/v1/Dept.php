<?php


namespace app\api\controller\v1;

use app\api\validate\CampaignValidate;
use app\api\validate\DeptValidate;
use app\common\controller\BaseController;
use app\common\exception\InvalidParamException;
use app\common\model\Campaign as CampaignModel;
use app\common\model\CampaignDept;
use app\common\model\CampaignRating;
use app\common\model\Department as DeptModel;
use app\common\model\DepartmentRule;
use app\common\model\CampaignRatingResult;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\Request;
use think\response\Json;

class Dept extends BaseController
{

    /**
     * 提交评分
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function score(Request $request)
    {
        (new DeptValidate())->scene('score')->validate();
        $data = $request->param();
        $user = $request->user;
        try {
            $campaign = CampaignModel::findByUid($data['campaignID']);
            $dept = DeptModel::findByUid($data['deptID']);
            DepartmentRule::checkRuleWeight($dept->id, $data['ruleID'], $data['score']);
            CampaignRatingResult::postScore($campaign, $dept, $user, $data['score'], $data['ruleID']);
        } catch(InvalidParamException | Exception $e) {
            $err = errCodeMsg('program', 'RATING_FAIL');
            return resJson($data, $err['msg'] . ' ' . $e->getMessage(), $err['code']);
        }
        return resJson();
    }

    /**
     * * 获取部门列表
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function collection(Request $request)
    {
        (new CampaignValidate())->validate();
        $user = $request->user;
        $campaignID = $request->param('campaignID', 0);
        $campaign = CampaignModel::findOrFail($campaignID);
        $programs = CampaignDept::getCampaignDept($campaign->id);
        $ratedDeptIDs = CampaignRating::getRatedDept($campaignID, $user['id']);
        $programs = array_map(function($item) use ($ratedDeptIDs) {
            $item['status'] = !in_array($item['id'], $ratedDeptIDs);
            return $item;
        }, $programs);
        sort($programs);
        return resJson($programs);
    }

    /**
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws InvalidParamException
     * @throws ModelNotFoundException
     */
    public function rule(Request $request)
    {
       // 获取部门规则
        (new DeptValidate())->validate();
        $dept = DeptModel::findOne($request->param('deptID', 0));
        $rules = $dept->getRules();
        return resJson($rules);
    }

    /**
     * 获取已有的评分记录
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function rating(Request $request)
    {
        //
        (new DeptValidate())->scene('rule')->validate();
        $data = $request->param();
        $user = $request->user;
        $ruleIDs = CampaignRatingResult::getRatingRuleID($data['campaignID'], $data['deptID'], $user['id']);
        return resJson($ruleIDs);
    }


    /**
     * 确认提交不可修改，计算总分
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function status(Request $request)
    {
        (new DeptValidate())->scene('rule')->validate();
        $data = $request->param();
        $user = $request->user;
        try{
            $res = CampaignRating::updateStatus($data['campaignID'], $data['deptID'], $user['id']);
            if ($res) {
                return resJson();
            } else {
                throw new InvalidParamException(['msg' => '保存提交分数有误']);
            }
        } catch(Exception | InvalidParamException $e){
            $err = errCodeMsg('program', 'SAVE_FAIL');
            return resJson($data, $err['msg'] . ' ' . $e->getMessage(), $err['code']);
        }
    }


}