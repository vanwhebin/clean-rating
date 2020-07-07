<?php
/**
 * Created by PhpStorm.
 * User: a2
 * Date: 2020/2/22
 * Time: 15:10
 */

namespace app\lib\wx;


class WxMedia extends Wx
{
    protected $accessToken;

    /**
     * 向企业微信上传临时素材
     * @param $mediaPath string 服务器上文件的绝对路径
     * @param string $mediaType
     * @return mixed
     * @throws \Exception
     * @throws \think\Exception
     */
    public function uploadMedia($mediaPath, $mediaType='image')
    {
        $api = sprintf(config('wx.UPLOAD_MEDIA_API'), $this->accessToken, $mediaType);
        $fileSize = filesize($mediaPath);
        $fileInfo = pathinfo($mediaPath);
        $fileName = uniqid(). '@' .date('Ymd-His').'@'.$fileInfo['basename'];
        $params = [
            'file' => new \CURLFile($mediaPath, 'image/'.$fileInfo['extension'], $fileName),
        ];

        $header = [
            'Content-Type' => ' multipart/form-data',
            'Content-Length' =>  $fileSize,
        ];

        return curlHttp($api, $params, 'POST', $header, $multi = true);
    }
}