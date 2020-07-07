<?php


namespace app\api\controller\v1;

use app\api\validate\CampaignValidate;
use app\api\validate\CreateCampaignValidate;
use app\common\controller\BaseController;
use app\common\exception\InvalidParamException;
use app\common\facade\ProgramFacade as ProgramModel;
use app\common\facade\CampaignFacade as CampaignModel;
use app\common\facade\ProgramRatingFacade as ProgramRatingModel;
use app\common\validate\PaginationValidate;
use think\facade\Hook;
use think\Request;
use think\response\Json;

class Campaign extends BaseController
{
    /**
     * 获取活动列表信息
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function collections(Request $request)
    {
        (new PaginationValidate())->validate();
        $data = $request->param();
        $campaigns = CampaignModel::getList($data['pageSize'], $data['pageNum'])->toArray();
        return resJson($campaigns);
    }


    /**
     * 当前活动的细节
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function index(Request $request)
    {
        (new CampaignValidate())->validate();
        $campaignUID = $request->param('campaignUID', 0);
        $campaign = CampaignModel::findByUid($campaignUID);
        return resJson($campaign);
    }

    /**
     * 用戶全部完成提交,不再修改
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function batchSubmit(Request $request)
    {
        // 检查当前用户需要评分的项目记录，是否都已经有了评分，如果没有，则进行反馈
        // 为真 直接返回
        (new CampaignValidate())->validate();
        $data = $request->param();
        $user = $request->user;
        $campaign = CampaignModel::findByUid($data['campaignUID']);
        ProgramRatingModel::disableStatus($campaign, $user);
        $allDone = ProgramRatingModel::checkRecord($campaign->id);
        if (!$allDone) {
            Hook::exec('app\\api\\behavior\\GetCampaignRatingBehavior', $campaign->toArray());
        }
        return resJson();
    }

    /**
     * 获取排行结果
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function top(Request $request)
    {
        (new CampaignValidate())->validate();
        $data = $request->param();
        $campaign = CampaignModel::findByUid($data['campaignUID']);
        $checkRating = ProgramModel::checkRating();
        if ($checkRating) {
            $programRanking = [];
        } else {
            $programRanking = ProgramModel::getRankedResults($campaign->id)->toArray();
        }
        return resJson($programRanking);
    }

    /**
     * 获取评分人员
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function rater(Request $request)
    {
        (new CampaignValidate())->validate();
        $data = $request->param();
        $campaign = CampaignModel::findByUid($data['campaignUID']);
        $raters = $campaign->getRaters($campaign->rule, $campaign->id);
        return resJson($raters);
    }


    /**
     * 查找当前最新的的活动信息
     * @param Request $request
     * @return Json
     */
    public function latest(Request $request)
    {
        $campaign = CampaignModel::findLatest();
        // return resJson([$campaign, time()]);
        if ($campaign) {
            return resJson($campaign);
        } else {
            $error = errCodeMsg('campaign', 'EMPTY');
            return resJson($request->param(), $error['msg'], $error['code']);
        }
    }

    /**
     * 更新评分规则
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function rule(Request $request)
    {
        (new CampaignValidate())->validate();
        $data = $request->param();
        $campaign = CampaignModel::findByUid($data['campaignUID']);
        $campaign->rule = json_encode($data['rule']);
        if ($campaign->save()) {
            return resJson();
        } else {
            $error = errCodeMsg('campaign', 'UPDATE_RULE_FAIL');
            return resJson($request->param(), $error['msg'], $error['code']);
        }
    }

    /**
     * 创建活动
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function create(Request $request)
    {

        (new CreateCampaignValidate())->validate();
        if ($campaign = CampaignModel::createOne($request->param())) {
            Hook::exec('app\\api\\behavior\\CreateUUIDBehavior', $campaign);
            return resJson($campaign);
        } else {
            logger('创建活动失败', json_encode($request->param()), __CLASS__.'#'.__METHOD__);
            $code = config('error_code.campaign')['CREATE_FAIL'];
            $msg = config('error_msg');
            return resJson($request->param(), $msg[$code], $code);
        }
    }

    /**
     * 更新活动信息
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function update(Request $request)
    {
        (new CampaignValidate())->scene('update')->validate();
        $data = $request->param();
        $campaign = CampaignModel::findByUid($data['campaignUID']);
        if ($campaign->updateInfo($campaign, $data)) {
            return resJson($campaign);
        } else {
            logger('更新活动失败', json_encode($request->param()), __CLASS__.'#'.__METHOD__);
            $code = config('error_code.campaign')['UPDATE_FAIL'];
            $msg = config('error_msg');
            return resJson($request->param(), $msg[$code], $code);
        }
    }


    /**
     * 刪除活動
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function delete(Request $request)
    {
        (new CampaignValidate())->validate();
        if (CampaignModel::where(['uuid' => $request->param('campaignUID', 0)])->find()->delete()) {
            $user = $request->user;
            logger($user->name.'刪除活动成功', json_encode($request->param()), __CLASS__.'#'.__METHOD__);
            return resJson();
        } else {
            logger('刪除活动失败', json_encode($request->param()), __CLASS__.'#'.__METHOD__);
            $error = errCodeMsg('campaign', 'DELETE_FAIL');
            return resJson($request->param(), $error['msg'], $error['code']);
        }
    }



}