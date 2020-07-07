<?php
return [
    'corpid'                =>  'ww0f3efc2873ad11c3',
    'agentid'               =>  '1000036',
    'corpsecret'            =>  'w5wdxJIB68-D09TtKxjHt3HJhiB40wytTdtoLSWN2SM',
    'redirect_uri'          =>  'http://vote.freebie-queen.com',
    'callbackState'         =>  'reviewer@aukey',
    'access_token_expire'   =>  7200,
    'access_token_cache'    =>  "wx_access_token",
    'contact_info'          =>  "欢迎使用测评平台！我们将尽快处理您的申请并通过企业微信通知您。如有任何疑问，请联系万伟斌或王超。",
    /*--------------api 接口-------------------*/
    // 获取access_token get
    'ACCESS_TOKEN_API'      => 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=%s&corpsecret=%s',
    // 获取企业微信中的userid get
    'USER_ID_API'           => 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=%s&code=%s',
    // 获取用户详情信息  get
    'USER_INFO_API'         => 'https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=%s&userid=%s',
    // 获取部门列表信息  get
    'DEPARTMENT_INFO_API'   => 'https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token=%s&id=%s',
    // 发送企业微信  post
    'SEND_MSG_API'   => 'https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=%s',
    // 上传素材  post
    'UPLOAD_MEDIA_API'   => 'https://qyapi.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=%s',
    /*--------------消息模板------------------*/
    'TXT_MSG_TPL'           => [
        'touser' => null,
        // 'toparty' => null,
        // 'totag' => null,
        'msgtype' => "text",
        'agentid' => '',
        'text' => [ 'content' => '', ],
        'safe' => 0,
        'enable_id_trans' => 0,
        'enable_duplicate_check' => 0,
    ],
    'IMAGE_MSG_TPL'           => [
        'touser' => null,
        // 'toparty' => null,
        // 'totag' => null,
        'msgtype' => "image",
        'agentid' => '',
        'image' => [ 'media_id' => '', ],
        'safe' => 0,
        // 'enable_duplicate_check' => 0,
        // 'duplicate_check_interval' => 0,
    ],
];