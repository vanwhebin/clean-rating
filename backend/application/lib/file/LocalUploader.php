<?php
/*
* Created by DevilKing
* Date: 2019-06-08
*Time: 16:19
*/

namespace app\lib\file;

use app\common\model\Media;
use app\common\exception\FileException;

/**
 * Class LocalUploader
 * @package app\lib\file
 */
class LocalUploader extends File
{
    /**
     * @return array
     * @throws FileException
     */
    public function upload()
    {
        $ret = [];
        foreach ($this->files as $key => $file) {
            $md5 = $this->generateMd5($file);
            $exists = Media::get(['md5' => $md5]);
            if ($exists) {
                array_push($ret, [
                    'key' => $key,
                    'id' => $exists['id'],
                    'url' => getMediaUrl($exists['url'], $exists['from'])
                ]);
            } else {
                $size = $this->getSize($file);
                $info = $file->move($this->storeDir);
                if ($info) {
                    $extension = '.' . $info->getExtension();
                    $path = str_replace('\\','/',$info->getSaveName());
                    $name = $info->getFilename();
                } else {
                    throw new FileException([
                        'msg' => $this->getError,
                        'errorCode' => 60001
                    ]);
                }
                $linFile = Media::create([
                    'name' => $name,
                    'url' => $path,
                    'size' => $size,
                    'extension' => $extension,
                    'md5' => $md5,
                    'from' => 1
                ]);
                array_push($ret, [
                    'key' => $key,
                    'id' => $linFile->id,
                    'url' => getMediaUrl($linFile['url'], $linFile['from'])
                ]);
            }
        }
        return $ret;
    }
}
