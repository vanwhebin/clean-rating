<?php


namespace app\api\behavior;


use app\common\model\Campaign;
use app\common\model\Department;

class CreateUUIDBehavior
{
    public function run($class)
    {
        if ($class instanceof Campaign) {
            $salt = config('secure.campaign_salt');
        } else if ($class instanceof Department) {
            $salt = config('secure.program_salt');
        }
        $uuid = createUID($class->id, $salt);
        $class->uuid = $uuid;
        return $class->save();
    }

}