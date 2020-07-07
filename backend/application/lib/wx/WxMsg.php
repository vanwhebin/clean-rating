<?php
/**
 * Created by PhpStorm.
 * User: a2
 * Date: 2020/1/6
 * Time: 15:00
 */

namespace app\lib\wx;


use app\lib\enum\LogEnum;
use think\facade\Log;

class WxMsg extends Wx
{
    protected $accessToken;

    /**
     * @param $userID string|array
     * @param $msg array content 消息内容 一些其他的数据，比如发送者的名称等等
     * @return array|bool
     * @throws \Exception
     * @throws \think\Exception
     */
    public function sendTextMsg($userID, $msg)
    {
        $msgParam = config('wx.TXT_MSG_TPL');
        $msgParam['agentid'] = intval(config('wx.agentid'));
        $msgParam['text']['content'] = substr($msg['content'], 0,2048);
        $msgParam['touser'] = is_array($userID) ? implode('|', $userID) : $userID;
        $api = sprintf(config('wx.SEND_MSG_API'), $this->accessToken);
        $curlRes = json_decode(curlHttp($api, json_encode($msgParam),
            'POST', ['content-type' => "application/json"], true), true);
        logger(LogEnum::WX_POST, [$api, $curlRes, $msgParam],'send wx text msg');
        if ($curlRes['errcode'] === 0) {
            // 出错返回码，为0表示成功，非0表示调用失败
            return ['msg'=>"发送成功", 'error' => 0];
        } else {
            Log::error($curlRes['errmsg']."###". json_encode($curlRes));
            return ['msg'=>"调用企业微信发送文本消息失败: {$curlRes['errmsg']}", 'error' => 1];
        }
    }

    /**
     * 发送图片给到企业微信用户
     * @param $userID
     * @param $msg array 'content' 图片文件在服务器上的相对位置  'reviewerName' 测评人员名称
     * @return array|bool
     * @throws \Exception
     * @throws \think\Exception
     */
    public function sendImageMsg($userID, $msg)
    {
        // $imagePath 为相对路径
        $api = sprintf(config('wx.SEND_MSG_API'), $this->accessToken);
        $msgParam = config('wx.IMAGE_MSG_TPL');
        $msgParam['agentid'] = intval(config('wx.agentid'));
        $mediaRes = json_decode((new WxMedia())->uploadMedia(realpath($msg['content']), $msgParam['msgtype']), true);
        if ($mediaRes['errcode']){
            $reviewer = !empty($msg['reviewerName']) ? $msg['reviewerName'] : '有测评用户' ;
            $textMsg['content'] = "{$reviewer} 给你发了一张图片，企业微信发送失败，请到平台或者FB查看。";
            return self::sendTextMsg($userID, $msg);
        } else {
            $reviewer = !empty($msg['reviewerName']) ? $msg['reviewerName'] : '有测评用户' ;
            $textMsg['content'] = "{$reviewer} 给你发了一张图片。";
            self::sendTextMsg($userID, $textMsg);
            $msgParam['image']['media_id'] = $mediaRes['media_id'];
            $msgParam['touser'] = is_array($userID) ? implode('|', $userID) : $userID;
            $curlRes = json_decode(curlHttp($api, json_encode($msgParam),
                'POST', ['content-type' => "application/json"], true), true);
            logger(LogEnum::WX_POST, [$api, $curlRes, $msgParam],'send wx image msg');
            if ($curlRes['errcode'] === 0) {
                // 出错返回码，为0表示成功，非0表示调用失败
                return ['msg'=>"发送成功", 'error' => 0];;
            } else {
                Log::error($curlRes['errmsg']."###". json_encode($curlRes));
                return ['msg'=>'调用企业微信发送图片消息失败'];
            }
        }
    }
}