<?php
/**
 * Created by PhpStorm.
 * User: a2
 * Date: 2020/1/6
 * Time: 15:34
 */

namespace app\common\model;


class Log extends BaseModel
{

    public function buildWhere($data)
    {
        $where = [];
        if (!empty($data['type'])) {
            $where['type'] = $data['type'];
        }
        return $where;
    }

    public function all($data, $num=10, $page=1)
    {
        $where = $this->buildWhere($data);
        return self::where($where)->order('id', 'desc')
            ->paginate($num, false, ['page' => $page]);
    }


}