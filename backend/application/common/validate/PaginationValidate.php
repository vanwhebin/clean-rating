<?php


namespace app\common\validate;


class PaginationValidate extends BaseValidate
{

    protected $rule = [
        'pageNum' => 'require|isPositiveInteger|between:1,200',
        'pageSize' => 'require|isPositiveInteger|between:1,200',
    ];

    protected $message = [
        'pageNum' => 'count must be a positive integer between 1 and 200',
        'pageSize' => 'page must be a positive integer between 1 and 200',
    ];


}