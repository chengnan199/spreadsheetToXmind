<?php
namespace src\controller;

//$arr = (new tree())->tree();
//file_put_contents('./tree.json', json_encode($arr, JSON_UNESCAPED_UNICODE));
class Tree {
    protected $rawArr;
    function __construct(){
//        if (!$rawArr){
//            //获取要转化的JSON并转化为数组对象
//            $rawArr = json_decode(file_get_contents('./json.json'));
//            $rawArr = json_decode(json_encode($rawArr), true);
//        }
//        $this->rawArr = $rawArr;
    }
    function tree(array $rawArr)
    {
        $arr = $this->filterTree($rawArr);

        $count = count($arr);
        for ($i = 0; $i < $count; $i++) {
            for ($y = $i + 1; $y < $count; $y++) {
                if (!empty($arr[$i]['mergeRangeArr']) && array_key_exists($arr[$y]['coordinate'], $arr[$i]['mergeRangeArr'])) {
                    $arr[$i]['child'] = $this->merge($arr[$i], $arr[$y]);
                    unset($arr[$y]);
                }
            }
        }
        $arr = array_values($arr);
        return $arr;
    }

//将同一行的元素创建属性结构，并删除多余元素
    function filterTree($rawArr)
    {
        $arr = [];
        foreach ($rawArr as $k => $v) {
            array_pop($v);
            $v[0]['child'][] = $this->toTree($v);
            $arr[] = $this->mergeRangeToArr($v[0]);
        }
        return $arr;
    }

//合并相同单元格的数据
    function merge($origin, $range)
    {
        $origin = $origin['child'] ?? [];
        $range = $range['child'] ?? [];
        if (!$range) return [];

        foreach ($range as $k => $range_v) {
            $flag = false;
            foreach ($origin as $kk => $origin_v) {
//            如果在原数组中找到了可合并的项
                if ($range_v['mergeRange'] && $origin_v['mergeRange'] && ($range_v['mergeRange'] == $origin_v['mergeRange'])) {
                    $origin[$kk]['child'] = $this->merge($origin_v, $range_v);
                    $flag = true;
                }
            }
//        如果不存在
            if (!$flag) {
                $origin[] = $range_v;
            }
        }
        return $origin;
    }

//同一行转化为树形结构
    function toTree($arr)
    {
        array_shift($arr);
        $child = $this->mergeRangeToArr($arr[0]);
        if (isset($arr[1])) {
            $child['child'][] = $this->toTree($arr);
        }
        return $child;
    }

//mergeRange 范围转成数组
    function mergeRangeToArr($vv)
    {
        $column = $vv['column'];
        $merge = $vv['mergeRange'];
        $mergeArr = [];
        if ($merge && strstr($merge, ':')) {
            $limit = explode(':', str_replace($column, '', $merge));
            for ($i = $limit[0]; $i <= $limit[1]; $i++) {
                $mergeArr[$column . $i] = $merge;
            }
        }
        $vv['mergeRangeArr'] = $mergeArr;
        return $vv;
    }

}


