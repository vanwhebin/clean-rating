<?php

use app\common\facade\CampaignFacade;
use app\common\facade\DeptFacade;
use app\common\facade\DeptRatingFacade;
use app\common\model\Campaign;
use app\common\model\Department;
use app\common\model\DeptRating;

return [
    'facade' => [
        CampaignFacade::class => Campaign::class,
        DeptFacade::class => Department::class,
        DeptRatingFacade::class => DeptRating::class,
    ],
    'alias' => [

    ]
];