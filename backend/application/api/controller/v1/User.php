<?php


namespace app\api\controller\v1;

use app\api\validate\LoginFormValidate;
use app\common\model\User as UserModel;
use app\common\controller\BaseController;
use app\lib\token\Token;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Request;
use think\response\Json;

class User extends BaseController
{
    /**
     * @return Json
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function all()
    {
        $users = UserModel::where(['status' => 1])-> field(['id', 'name'])->select()->toArray();
        return resJson($users);
    }


    public function token()
    {
        $token = Token::getToken();
        return resJson($token);
    }



}