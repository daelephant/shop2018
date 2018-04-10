<?php
//jsonp数组写法，本项目首页实际测试，console.log打印信息
$arr = array(
	'name' => 'elephant',
	'age'  => '26',
	 );
$str = json_encode($arr);
echo "abc($str)";
?>