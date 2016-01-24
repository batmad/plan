<html>
<head>
<title>Архив план работ Министерства ЖКХ и энергетики РС(Я)</title>
</head>
<body>
<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
echo "<a href='/index.php'><img src='/img/previous.png' title='Вернуться обратно'></a><br/>";


$first_year = 2014;

if (isset($_GET) && !empty($_GET)){
	$year = $_GET['year'];
	$current_week = 1;
	$old_year = true;
}
else{
	$year = date('Y');
	$current_week = date('W');
	$old_year = false;
}

$week_number = 0;
$week = array();
$months_rus = array(1=>"Январь",2=>"Февраль",3=>"Март",4=>"Апрель",5=>"Май",6=>"Июнь",7=>"Июль",8=>"Август",9=>"Сентябрь",10=>"Октябрь",11=>"Ноябрь",12=>"Декабрь");
if ($year % 4 == 0 && $year % 100 != 0 || $year % 400 == 0){
	$months = array(1=>31,2=>29,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);
}
else {
	$months = array(1=>31,2=>28,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);
}

//Рисуем возможность выбора года архива плана работы
for ($i = $first_year; $i < $year; $i++){
	echo "<a href='week.php?year=$i'>План работы за $i год</a><br/>";
}




//В каждом элементе массива $months ключ записан как месяц, а значение как день
//Определяем переменную $i и пока переменная меньше дня считаем:
//Если дата которую мы отправляем равна воскресенью, тогда записываем в
//промежуточную перменную $week_number номер недели
//И затем если $number_week меньше или равно текущему номеру недели
//записываем все в массив $week
//ВНИМАНИЕ НЕ ИСПОЛЬЗОВАТЬ WHILE, при сравнении номера недели


foreach($months as $month=>$day){
	$i = 1;
	while($i <=$day){
		if (date('N',mktime(1,1,1,$month,$i,$year))==7){
			$week_number = date('W',mktime(1,1,1,$month,$i,$year));

			if ($old_year){
					$week[$week_number]['start']=date('d-m-Y',mktime(1,1,1,$month,$i-6,$year));
					$week[$week_number]['end']=date('d-m-Y',mktime(1,1,1,$month,$i,$year));
					$week[$week_number]['month']=$months_rus["$month"];
					$week[$week_number]['month_number']=$month;
					$week[$week_number]['day_number']=$i;
			}
			else {
				if($week_number<$current_week){
					$week[$week_number]['start']=date('d-m-Y',mktime(1,1,1,$month,$i-6,$year));
					$week[$week_number]['end']=date('d-m-Y',mktime(1,1,1,$month,$i,$year));
					$week[$week_number]['month']=$months_rus["$month"];
					$week[$week_number]['month_number']=$month;
					$week[$week_number]['day_number']=$i;
				}				
			}
		}
		$i++;
	}
}
ksort($week);

$old_plan = array();
$month_pocket = "Some string";
echo "<br/>";
foreach($week as $w){
	if ($w['month']!=$month_pocket){
		echo "<br/><b>".$w['month']."</b><br/>";
		$month_pocket = $w['month'];
	}
	echo "<a href=\"http://$_SERVER[SERVER_ADDR]/old_plan.php?";
	$j = 0;
	for ($i=6;$i>0;$i--){		
		$date = new DateTime();
		$date->setDate($year, $w['month_number'], $w['day_number']-$i);
		$old = $date->format('d-m-Y');
		echo "&old_plan[$j]=".$old;
		$j++;
	}
	echo "\">План работы от ".$w['start']." по ".$w['end']."<br/></a>";
}


$mysqli->close();
?>

