<?php


namespace app\api\validate;


use app\common\validate\BaseValidate;

class LoginFormValidate extends BaseValidate
{
    public $rule = [
        'email' => 'require|isNotEmpty|email',
        'password' => 'require|isNotEmpty',
    ] ;

    public $message = [
        'email' => 'correct email is required',
        'password' => 'correct password is required'
    ];

}