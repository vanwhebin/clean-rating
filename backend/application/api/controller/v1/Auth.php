<?php

namespace app\api\controller\v1;

use app\api\service\User as UserService;
use app\api\validate\LoginFormValidate;
use app\common\controller\BaseController;
use app\common\exception\InvalidParamException;
use app\lib\token\Token;
use app\lib\wx\WxUser;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\facade\Hook;
use think\Request;
use think\response\Json;
use think\response\Redirect;

class Auth extends BaseController
{
    public $service;

    public function initialize()
    {
        parent::initialize();
        $this->service = new UserService();
    }


    /**
     *  * 企业微信登录回调
     * @param Request $request
     * @return Redirect
     * @throws InvalidParamException
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function wx(Request $request)
    {
        $param = $request->param();
        // 拿到code获取用户信息, 创建用户, 拿到企业微信的access_token 缓存起来
        $wxUser = new WxUser();
        // return json([$param, $wxUser]);
        $wxUserID = $wxUser->getUserID($param['code']);
        $user = \app\common\model\User::getUserByUserID($wxUserID);
        // Hook::exec('app\api\behavior\UserLoginBehavior', $user);
        $token = Token::getToken($user);
        $url = config('domain');
	    // 创建测评系统access_token，缓存用户的信息, 返回系统的access_token
        $url = $url . '?' . http_build_query($token);
        return redirect($url);
    }

    /**
     *  * 用户使用邮件登录
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws InvalidParamException
     * @throws ModelNotFoundException
     */
    public function email(Request $request)
    {
        (new LoginFormValidate())->validate();
        $data = $request->param();
        // return json($data);
        $user = \app\common\model\User::getEmailUser($data['email'], $data['password']);
        if ($user) {
            // return json($user);
            $token = Token::getToken($user);
            return resJson($token);
        } else {
            return json([], 401);
        }
    }

}
