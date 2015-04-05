<?php

echo "<a href='index.php'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
include('checkauth.php');

$_SESSION['search'] = false;
$_SESSION['date'] = '';
$_SESSION['descr'] = '';

$month = date('m');
$year = date('Y');
$months_rus = array(1=>"Январь",2=>"Февраль",3=>"Март",4=>"Апрель",5=>"Май",6=>"Июнь",7=>"Июль",8=>"Август",9=>"Сентябрь",10=>"Октябрь",11=>"Ноябрь",12=>"Декабрь");


foreach ($months_rus as $key=>$mrus){	
	echo "<a href='index.php?month=".$key."'>".$mrus."</a><br>";
}

?>