<?php


namespace Sczts\Skeleton\Traits;

use App\Models\User\User;
use Illuminate\Support\Facades\DB;

trait Random
{
    /**
     * 根据表名获取随机一个ID
     *
     * @param $table
     * @param $field
     *
     * @return mixed
     */
    public function randomId($table,$field = 'id')
    {
        $data = DB::table($table)->inRandomOrder()->first();
        if ($field == '*'){
            return $data;
        }
        return $data->$field;
    }

    /**
     * 根据表名获取随机一个ID
     *
     * @param string $table
     * @param int    $min
     * @param int    $max
     * @return array
     * @throws \Exception
     */
    public function randomIds(string $table, int $min, int $max)
    {
        $number = random_int($min, $max);
        return DB::table($table)->inRandomOrder()->take($number)->pluck('id')->toArray();
    }


    /**
     * 随机中文字符串
     * @param $min
     * @param $max
     * @return string
     * @throws \Exception
     */
    public function randomChinese($min,$max){
        $length = random_int($min,$max);
        $str = '四川猪太帅科技有限公司作为一家互联网科技公司，主要业务包括：互联网建设丶电子商务丶企业咨询丶软件管理系统开发 。其中，云建工程宝管理系统(www.360gcb.com) 、哼哼OA（www.henghengoa.com）、证在5G（www.5g.com.cn） 是公司的核心产品，意在为建筑类企业提供高效便利的技术服务公司会根据每一位客户的企业文化丶特色及行业特性，制定相应的个性化方案。务求为每个合作企业带来 智能化管理，一键式轻松体验的管理理念。目前，公司已经成立包括业务部丶技术部，市场部丶运营部， 为合作客户保证强力的技术支撑和后续保障。与时俱进，开拓创新！力求将最好的的产品倾情奉献给客户，这是猪太帅科技一直不断追求的梦想和前进的动力！';
        $strArray = preg_split('/(?<!^)(?!$)/u', $str);
        shuffle($strArray);
        $str = implode('',$strArray);
        $string = mb_substr($str,0,$length);
        return $string;
    }

}



