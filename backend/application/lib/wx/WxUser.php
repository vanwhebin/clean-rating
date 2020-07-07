<?php
/**
 * Created by PhpStorm.
 * User: a2
 * Date: 2020/1/3
 * Time: 21:25
 */

namespace app\lib\wx;

use app\common\exception\InvalidParamException;
use think\Exception;
use think\facade\Log;

class WxUser extends Wx
{
	protected $accessToken;

	/**
	 * 获取企业微信用户ID
	 * @param $code
	 * @return mixed
	 * @throws InvalidParamException
	 * @throws \Exception
	 * @throws Exception
	 */
	public function getUserID($code)
	{
		$api = sprintf(config('wx.USER_ID_API'), $this->accessToken, $code);
		$curlRes = json_decode(curlHttp($api), true);
		if ($curlRes['errcode'] === 0) {
			// 出错返回码，为0表示成功，非0表示调用失败
			$userID = $curlRes['UserId'];
			return $userID;
		} else {
			// logger(LogEnum::WX_POST,'调用企业微信获取userID失败 '.$curlRes['errmsg']."###". json_encode($curlRes), '获取企业微信信息');
            Log::write($curlRes['errmsg']."###". json_encode($curlRes));
			throw new InvalidParamException(['msg'=>'调用企业微信获取userID失败']);
		}
	}

	/**
	 * 获取企业微信用户信息
	 * @param $userID
	 * @return mixed
	 * @throws InvalidParamException
	 * @throws \Exception
	 * @throws Exception
	 */
	public function getUserInfo($userID)
	{
		$api = sprintf(config('wx.USER_INFO_API'), $this->accessToken, $userID);
		$curlRes = json_decode(curlHttp($api), true);
		if ($curlRes['errcode'] === 0) {
			// 出错返回码，为0表示成功，非0表示调用失败
			return $curlRes;
		} else {
            logger(LogEnum::WX_POST,'调用企业微信获取userInfo失败: '.$curlRes['errmsg']."###". json_encode($curlRes), '获取企业微信信息');
			Log::write($curlRes['errmsg']."###". json_encode($curlRes));
			throw new InvalidParamException(['msg'=>'调用企业微信获取userInfo失败']);
		}
	}


    /**
     * @param $deptID
     * @return mixed
     * @throws InvalidParamException
     * @throws \Exception
     * @throws Exception
     */
	private function retrieveDeptInfo($deptID)
	{
		$api = sprintf(config('wx.DEPARTMENT_INFO_API'), $this->accessToken, $deptID);
		$curlRes = json_decode(curlHttp($api), true);
		if ($curlRes['errcode'] === 0) {
			// 出错返回码，为0表示成功，非0表示调用失败
			return $curlRes['department'];
		} else {
            logger(LogEnum::WX_POST,'调用企业微信获取DepartInfo失败: '.$curlRes['errmsg']."###". json_encode($curlRes), '获取企业微信信息');
            Log::write($curlRes['errmsg']."###". json_encode($curlRes));
			throw new InvalidParamException(['msg'=>'调用企业微信获取DepartInfo失败']);
		}

	}


    /**
     * @param $deptID
     * @return array
     * @throws InvalidParamException
     * @throws \Exception
     * @throws Exception
     */
	public function getUserDeptInfo($deptID)
	{
		$limit = 5;
		$dept = [];
		$tmpDeptID = $deptID;
		for($i=0; $i< $limit;$i++){
			$info = $this->retrieveDeptInfo($tmpDeptID);
			$count = count($info);
			for($j=0; $j < $count; $j++){
			    if ($tmpDeptID === $info[$j]['id']) {
                    array_unshift($dept, $info[$j]['name']);
                    if ($info[$j]['parentid'] === 1) {
                        break 2;
                    } else {
                        $tmpDeptID = $info[$j]['parentid'];
                    }
                    break;
                }
            }
		}
		return $dept;
	}
}