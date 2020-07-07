<?php


namespace app\api\controller\v1;

use app\api\validate\CampaignValidate;
use app\api\validate\CreateProgramValidate;
use app\api\validate\ProgramValidate;
use app\common\controller\BaseController;
use app\common\exception\InvalidParamException;
use app\common\facade\CampaignFacade as CampaignModel;
use app\common\facade\ProgramFacade as ProgramModel;
use app\lib\token\Token;
use think\facade\Hook;
use think\Request;
use think\response\Json;

class Program extends BaseController
{

    public function create(Request $request)
    {
        // 创建一个新活动
        (new CreateProgramValidate())->validate();
    	$data = $request->param();
    	$user = $request->user;
        $campaign = CampaignModel::findByUid($data['campaignUID']);
    	if (ProgramModel::createOne($campaign, $data, $request->user)){
    	    return resJson();
        } else {
            $err = errCodeMsg('program', 'CREATE_FAIL');
            return resJson($data, $err['msg'], $err['code']);
        }
    }

    /**
     * 提交评分
     * @param Request $request
     * @return Json
     * @throws InvalidParamException
     */
    public function rating(Request $request)
    {
        (new ProgramValidate())->scene('score')->validate();
        $data = $request->param();
        $user = $request->user;
        $campaign = CampaignModel::findByUid($data['campaignUID']);
        $program = ProgramModel::findByUid($data['programUID']);
        $res = ProgramModel::updateRating($campaign, $program, $user, $data['score']);
        if ($res) {
            return resJson();
        } else {
            $err = errCodeMsg('program', 'RATING_FAIL');
            return resJson($data, $err['msg'], $err['code']);
        }
    }

    public function collection(Request $request)
    {
        (new CampaignValidate())->validate();
        $user = $request->user;
        $campaignUID = $request->param('campaignUID', 0);
        $campaign = CampaignModel::findByUid($campaignUID);
        $programs = ProgramModel::getRatingProgram($campaign->id, $user->id);

        return resJson($programs);
    }




}