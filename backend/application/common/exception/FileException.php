<?php
/*
* Created by DevilKing
* Date: 2019-06-08
*Time: 16:11
*/
namespace app\common\exception;


class FileException extends BaseException
{
    public $code = 413;
    public $msg  = '文件体积过大';
    public $errorCode = '60000';
}
