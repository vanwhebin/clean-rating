<?php


namespace app\api\controller\v1;

use app\api\validate\CampaignValidate;
use app\common\controller\BaseController;
use app\common\exception\InvalidParamException;
use app\common\model\Campaign as CampaignModel;
use app\common\model\CampaignRating;
use app\common\model\CampaignRatingResult;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Request;
use think\response\Json;

class Campaign extends BaseController
{

    /**
     *  当前活动的细节
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws InvalidParamException
     * @throws ModelNotFoundException
     */
    public function index(Request $request)
    {
        (new CampaignValidate())->validate();
        $campaignID = $request->param('campaignID', 0);
        $campaign = CampaignModel::findByUid($campaignID);
        return resJson($campaign);
    }


    /**
     * 更新活动信息
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws InvalidParamException
     * @throws ModelNotFoundException
     */
    public function update(Request $request)
    {
        (new CampaignValidate())->scene('update')->validate();
        $data = $request->param();
        $campaign = CampaignModel::findByUid($data['campaignID']);
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
     * 查找当前最新的的活动信息
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
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
     * 获取排名
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function ranking(Request $request)
    {
        (new CampaignValidate())->validate();
        $data = $request->param();
        $ranking = CampaignRating::getRankingResult($data);
        return resJson($ranking);
    }


    /**
     * 获取已评分的部门
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function ratedDept(Request $request)
    {
        (new CampaignValidate())->validate();
        $campaignID = $request->only('campaignID');
        $user = $request->user;
        $deptIDs = CampaignRatingResult::getRatedDeptIDs($campaignID, $user['id']);
        return resJson($deptIDs);
    }


    

}