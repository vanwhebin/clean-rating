<?php

namespace app\http\middleware;

use app\common\exception\ForbiddenException;
use app\common\exception\TokenException;
use app\common\model\User;
use app\lib\token\Token;
use Closure;
use think\Exception;
use think\facade\Config;

class Login
{
    /**
     * 判断用户登录
     * @param $request
     * @param Closure $next
     * @return bool|mixed
     * @throws ForbiddenException
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        try {
            $url = parse_url($request->url())['path']; // 获取访问接口地址
            $white = Config::get('secure.white');
            // 判断是否已经登录
            if (in_array($url, $white)) {
                return $next($request);
            }

            if ($userInfo = Token::getCurrentUserInfo()) {
                $request->user = (array)$userInfo;
            }
            // $userInfo = User::find(1)->toArray();
            // $request->user = $userInfo;
            return $next($request);
        } catch (TokenException $exception) {
            throw new ForbiddenException(['msg' => '请先登录']);
        }
    }
}
