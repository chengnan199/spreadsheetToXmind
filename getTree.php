<?php

//获取要转化的JSON并转化为数组对象
$rawArr = json_decode(file_get_contents('./json.json'));
function tree ($rawArr){
    $arr = [];
    $last = [];
    foreach ($rawArr as $k=>$v){
//        $v 是一行数据
        foreach ($v as  $kk=>$vv){
            if (!$vv->value){
                $vv->value = $last[$kk]->value;
            }
            $vv->mergeRangeArr = mergeRangeToArr($vv->column,$vv->mergeRange);
        }
        $last = $v;
    }
    return $rawArr;
    return $arr;
}
//mergeRange 范围转成数组
function mergeRangeToArr ($column,$merge){
    $mergeArr = [];
    if (!$merge || strstr($merge,':')){
        return [];
    }
   $limit =  explode(':',str_replace($column,'',$merge));
   for ($i = $limit[0];$i<=$limit[1];$i++){
       $mergeArr[] = $column.$i;
   }
    return $mergeArr;
}
function a ($kk,&$arr){

}
$data = tree($rawArr);
var_dump($data);
file_put_contents('./tree.json',json_encode($data,JSON_UNESCAPED_UNICODE));