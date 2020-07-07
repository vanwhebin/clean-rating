<?php
/**
 * Created by PhpStorm.
 * User: a2
 * Date: 2020/1/3
 * Time: 20:30
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    use SoftDelete;
	protected $autoWriteTimestamp = true;
    // protected $autoWriteTimestamp = 'timestamp';
	protected $hidden = ['delete_time'];

	protected function  prefixImgUrl($value, $data){
		$finalUrl = $value;
		if($data['from'] == 1){
			// 当文件为本地存储
			$finalUrl = config('media.img_prefix') . $value;
		}
		return $finalUrl;
	}
}