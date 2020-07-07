<?php
// 项目类错误         1000X
// 活动类错误         2000X
// 服务端类错误       3000X
// 业务类错误         4000X
// 第三方接口错误     5000X
// 通用错误          110
return [
    "program" => [
        "VALIDATE_FAIL" => 10000,
        "EMPTY" => 10001,
        "CREATE_FAIL" => 10002,
        "RATING_FAIL" => 10003,
        "SAVE_FAIL" => 10004,
    ],
   'campaign' => [
       'VALIDATE_FAIL' => 20000,
       'EMPTY' => 20001,
       'CREATE_FAIL' => 20002,
       'UPDATE_RULE_FAIL' => 20003,
       'UPDATE_FAIL' => 20004,
   ]
];