<?php

use app\common\facade\CampaignFacade;
use app\common\facade\ProgramFacade;
use app\common\facade\ProgramRatingFacade;
use app\common\model\Campaign;
use app\common\model\Program;
use app\common\model\ProgramRating;

return [
    'facade' => [
        CampaignFacade::class => Campaign::class,
        ProgramFacade::class => Program::class,
        ProgramRatingFacade::class => ProgramRating::class,
    ],
    'alias' => [

    ]
];