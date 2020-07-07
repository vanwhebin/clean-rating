<?php
namespace app\lib\token;


use app\common\exception\TokenException;
use app\common\model\Reviewer;
use app\common\model\User;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use think\Exception;
use think\facade\Config;
use think\facade\Request;
use UnexpectedValueException;

class Token
{
    /**
     * @param $user
     * @return array
     */
    public static function getToken($user)
    {
        $accessToken = self::createAccessToken($user);
        // $refreshToken = self::createRefreshToken($user);
        return [
            'access_token' => $accessToken,
            // 'refresh_token' => $refreshToken
        ];
    }

    /**
     * @return array
     * @throws Exception
     * @throws TokenException
     * @throws UnexpectedValueException
     */
    public static function refreshToken()
    {
        $user = self::getCurrentUser();
        $accessToken = self::createAccessToken($user);

        return [
            'access_token' => $accessToken,
        ];
    }

    /**
     * @param $user
     * @return mixed
     */
    private static function createAccessToken($user)
    {
        $key = config('secure.token_salt');
        $payload = [
            'iss' => Config::get('domain'), //签发者
            'iat' => time(), //什么时候签发的
            'exp' => time() + (3600 * 6), //过期时间
            'user' => $user,
        ];
        return JWT::encode($payload, $key);

    }

    private static function createRefreshToken($user)
    {
        $key = config('secure.token_salt');
        $payload = [
            'iss' => Config::get('domain'), //签发者
            'iat' => time(), //什么时候签发的
            'user' => $user,
        ];
        return JWT::encode($payload, $key);
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws TokenException
     * @throws UnexpectedValueException
     */
    public static function getCurrentUser()
    {
        $uid = self::getCurrentUID();
        $user = User::get($uid);
        return $user->hidden(['create_time', 'update_time', 'role_id']);
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws TokenException
     * @throws UnexpectedValueException
     */
    public static function getCurrentUID()
    {
        return self::getCurrentTokenVar('id');
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws TokenException
     * @throws UnexpectedValueException
     */
    public static function getCurrentName()
    {
        return self::getCurrentTokenVar('name');
    }

    /**
     * 当前用户缓存的所有信息
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentUserInfo()
    {
        return self::getCurrentTokenVar('all');
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     * @throws TokenException
     * @throws UnexpectedValueException
     */
    private static function getCurrentTokenVar($key)
    {
        $authorization = Request::header('Authorization');

        if (!$authorization) {
            throw new TokenException(['msg' => '请求未携带Authorization信息']);
        }

        list($type, $token) = explode(' ', $authorization);

        if ($type !== 'Bearer') throw new TokenException(['msg' => '接口认证方式需为Bearer']);

        if (!$token) {
            throw new TokenException(['msg' => '尝试获取的authorization信息不存在']);
        }

        $secretKey = config('secure.token_salt');

        try {
            $jwt = (array)JWT::decode($token, $secretKey, ['HS256']);
        } catch (SignatureInvalidException $e) {  //签名不正确
            throw new TokenException(['msg' => '令牌签名不正确']);
        } catch (BeforeValidException $e) {  // 签名在某个时间点之后才能用
            throw new TokenException(['msg' => '令牌尚未生效']);
        } catch (ExpiredException $e) {  // token过期
            throw new TokenException(['msg' => '令牌已过期，刷新浏览器重试']);
        } catch (Exception $e) {  //其他错误
            throw new Exception($e->getMessage());
        }
        if (strtolower($key) == 'all') {
            return $jwt['user'];
        }

        if (array_key_exists($key, $jwt['user'])) {
            return $jwt['user']->$key;
        } else {
            throw new TokenException(['msg' => '尝试获取的Token变量不存在']);
        }

    }
}