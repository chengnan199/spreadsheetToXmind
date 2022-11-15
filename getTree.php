<?php
//获取要转化的JSON并转化为数组对象
$rawArr = json_decode(file_get_contents('./json.json'));
function ss ($data){
    $arr = [];
    $arr1 = [];
    foreach ($data as $k=>$v){
        $arr1[$v->mergeRange][] = $v;

        if (isset($arr[$v->mergeRange])){
           array_push($arr[$v->mergeRange]['rowArr'],$v->row);
        }else{
            $arr[$v->mergeRange] = [
                'value'=>$v->value,
                'mergeRange'=>$v->mergeRange,
                'column'=>$v->column,
                'coordinate'=>$v->coordinate,
                'row'=>$v->row,
                'rowArr'=>[$v->row],
            ];
        }
    }
    return $arr;
}

function tree ($rawArr){
    $data = [];
    $last = [];
    foreach ($rawArr as $k=>$v){
        $endColumn = end($v)->column;
        foreach ($v as $kk=>$vv){
            if ($vv->column == $endColumn){
                continue;
            }
            if (!$vv->value){
                $vv->value = $last[$kk]->value;
            }
            $vv = mergeRangeToArr($vv);
            $data[] = $vv;
        }
        $last = $v;
    }
    return $data;
}
function aa ($rawArr){
    $rawArr = json_decode(json_encode($rawArr),true);

    $arr = [];
    foreach ($rawArr as $k=>&$v){
        $v[0]['child'][] = q($v);
        $arr[] = mergeRangeToArr($v[0]);
    }
    unset($v);
    $a = [];
    $a =  www($arr);
    var_dump(count($a));
    file_put_contents('./tree.json',json_encode($a,JSON_UNESCAPED_UNICODE));
//    file_put_contents('./tree1.json',json_encode($arr,JSON_UNESCAPED_UNICODE));
}
function www ($arr) {
    static $lsit = [];
    $tree = [];
    $first = array_shift($arr);

        foreach ($arr as $k=>$v){
           if (in_array($v['coordinate'],$first['mergeRangeArr'])){
               $first['child'] = rrr($first,$v);
               unset($arr[$k]);
           }
        }

        if ($arr){
            www ($arr);
        }
    var_dump($first);
    file_put_contents('./tree.json',json_encode($first,JSON_UNESCAPED_UNICODE));

    die();
    return $lsit;
}
function rrr($origin , $range){
    $origin = $origin['child']??[];
    $range = $range['child']??[];
    if (!$range) return [];
    $arr = array_merge($origin,$range);

    foreach ($range as $k => $v) {
        $flag = false;
        foreach ($origin as $kk => $origin_v) {
//            如果在原数组中找到了可合并的项
            if ($v['mergeRange'] == $origin_v['mergeRange']) {
                $origin[$kk]['child'] = rrr($v, $origin_v);
                $flag = true;
            }
        }
//        如果不存在
        if (!$flag){
            $origin[] = $v;
        }
    }
 return $origin;
}
function q ($arr){
    array_shift($arr);
    $child = mergeRangeToArr($arr[0]);
    if (isset($arr[1])){
        $child['child'][] = q($arr);
    }
    return $child;
}
aa($rawArr);
//aa($rawArr);
//mergeRange 范围转成数组
function mergeRangeToArr ($vv){
    $vv = json_decode(json_encode($vv));

    $column = $vv->column;
    $merge = $vv->mergeRange;
    $mergeArr = [];
    if (!$merge || !strstr($merge,':')){
//        $coordinate =  $vv->coordinate;
//        $mergeRange = $coordinate.':'.$coordinate;
//        $vv->mergeRange = $mergeRange;
//        $mergeArr =  [$coordinate=>$mergeRange];
    }else{
        $limit =  explode(':',str_replace($column,'',$merge));
        for ($i = $limit[0];$i<=$limit[1];$i++){
            $mergeArr[$column.$i] = $merge;
        }
    }

    $vv->mergeRangeArr = $mergeArr;
    $vv = json_decode(json_encode($vv),true);
    return $vv;
}
function a ($kk,&$arr){

}
//$data = tree($rawArr);
////var_dump($data);
//file_put_contents('./tree.json',json_encode($data,JSON_UNESCAPED_UNICODE));