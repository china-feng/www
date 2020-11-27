<?php

//递归清空一个目录
function deldir($path){
   //如果是目录则继续
   if(is_dir($path)){
    //扫描一个文件夹内的所有文件夹和文件并返回数组
   $p = scandir($path);
   foreach($p as $val){
    //排除目录中的.和..
    if($val !="." && $val !=".."){
     //如果是目录则递归子目录，继续操作
     if(is_dir($path.$val)){
      //子目录中操作删除文件夹和文件
      deldir($path.$val.'/');
      //目录清空后删除空文件夹
      @rmdir($path.$val.'/');
     }else{
      //如果是文件直接删除
      unlink($path.$val);
     }
    }
   }
  }
}

/**
*异步执行
*/
ignore_user_abort(true); //表示忽略与用户的断开,继续向下执行,不然过了1s超时时间会停止执行的
function asynch($data = array())
{
    $url = 'http://open.5qwan.local.com/src/OSS/2.php';//接受curl请求的地址

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type:application/json; charset=utf-8"));

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));//post方式数据为json格式
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);//设置超时时间为1s

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

//保留几位小数  不进行四舍五入
function formatFloat($num, $n){
   return substr(sprintf('%.' . $n - 1 . 'f', $res['recharge_num'] / 100), 0, -1);
}



