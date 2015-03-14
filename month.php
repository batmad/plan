<?php

echo "<a href='control.php'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";
$year = date('Y');
$act_month = date('m');
$months_rus = array(1=>"Январь",2=>"Февраль",3=>"Март",4=>"Апрель",5=>"Май",6=>"Июнь",7=>"Июль",8=>"Август",9=>"Сентябрь",10=>"Октябрь",11=>"Ноябрь",12=>"Декабрь");
$first_year = 2014;

//Рисуем возможность выбора года архива плана работы
for ($i = $first_year; $i < $year; $i++){
	echo "<a href='control.php?total=$i'>Всего за $i год</a><br/>";
}

echo "<a href='control.php?total=$year'>Всего за $year год<br>";
foreach ($months_rus as $number=>$month){
	if ($number<=$act_month){
		echo "<a href='control.php?month=".$number."'>".$month."<br>";
	}
}



?>