<?php


namespace app\common\model;


class Rule extends BaseModel
{
    public $category = [
        '1' => '办公区域',
        '2' => '样品区域',
        '3' => '公共区域',
    ];


    public function getCategoryAttr($value, $data)
    {
        // switch (intval($value)) {
        //     case 1:
        //         $value = '办公区域';
        //         break;
        //     case 2:
        //         $value = '公共区域';
        //         break;
        //     case 3:
        //         $value = '样品区域';
        //         break;
        // }
        return $this->category[$value];
    }


}