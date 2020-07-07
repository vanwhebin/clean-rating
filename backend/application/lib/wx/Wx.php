<?php
/**
 * Created by PhpStorm.
 * User: a2
 * Date: 2020/1/6
 * Time: 15:12
 */

namespace app\lib\wx;

use app\common\exception\InvalidParamException;
use Exception;
use think\facade\Log;

class Wx
{
    protected $accessToken;

    /**
     * Wx constructor.
     * @throws InvalidParamException
     * @throws Exception
     * @throws \think\Exception
     */
    public function __construct()
    {
        // 获取企业微信的access_token
        $this->accessToken = cache(config('wx.access_token_cache'));
        if (!$this->accessToken) {
            $this->accessToken = self::getWxAccessToken();
        }
    }

    /**
     * @return mixed
     * @throws InvalidParamException
     * @throws Exception
     */
    private static function getWxAccessToken()
    {
        $accessTokenApi = sprintf(config('wx.ACCESS_TOKEN_API'), config('wx.corpid'), config('wx.corpsecret'));
        $curlRes = json_decode(curlHttp($accessTokenApi), true);
        if ($curlRes['errcode'] === 0) {
            // 出错返回码，为0表示成功，非0表示调用失败
            return $curlRes['access_token'];
        } else {
            Log::error($curlRes['errmsg']."###". json_encode($curlRes));
            throw new InvalidParamException(['msg'=>'调用企业微信获取access_token失败']);
        }
    }
}