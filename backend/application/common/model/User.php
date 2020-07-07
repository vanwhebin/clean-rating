<?php

namespace app\common\model;

use PDOStatement;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;

class User extends BaseModel
{
    /**
     * @param $workerID string 数据库表中的userid字段
     * @return array|PDOStatement|string|Model|null
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getUserByUserID($workerID)
    {
        // workerID就是企业微信分配给企业当中每个人唯一的id号，区别于user表的自增ID，使用workerID
        return self::where(['userid' => $workerID])->find();
    }

    /**
     * @param $email
     * @param $password
     * @return array|PDOStatement|string|Model
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getEmailUser($email, $password)
    {
        $user = self::where(['email' => $email])->findOrEmpty();
        if ($user->isEmpty()) {
            return false;
        } else {
            return $password === '123456' ? $user : false;
        }
    }
}
