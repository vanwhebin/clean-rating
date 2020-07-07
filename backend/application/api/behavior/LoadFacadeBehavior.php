<?php
namespace app\api\behavior;

use think\Facade;
use think\facade\Config;
use think\Loader;

class LoadFacadeBehavior
{
    public function run()
    {
        Facade::bind(Config::get('facade.facade'));
        Loader::addClassAlias(Config::get('facade.alias'));
    }

}