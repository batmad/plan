<html>
<head>
<title>Информацию по контрольным поручениям Министерства ЖКХ и энергетики РС(Я)</title>
<script type="text/javascript">
function sh(){
	var det = document.getElementById('detail');
	var but = document.getElementById('button1');
	if  (det.style.visibility == 'hidden'){
		det.style.visibility = 'visible';
		but.value = "Скрыть";
	}
	else{
		det.style.visibility = 'hidden';
		but.value = "Подробная информация по исполнению";
	}
	return false;
}
</script>
</head>
<body>
<?php
$month = date('m');
$year = date('Y');
$months_rus = array('01'=>"Январь",'02'=>"Февраль",'03'=>"Март",'04'=>"Апрель",'05'=>"Май",'06'=>"Июнь",'07'=>"Июль",'08'=>"Август",'09'=>"Сентябрь",'10'=>"Октябрь",'11'=>"Ноябрь",'12'=>"Декабрь");
$echo = "Информация по контролю за ".$months_rus[$month]." ".$year." года";
$total_year = false;


header('Content-type: text/html; charset=utf-8');
include('bd.php');
echo "<a href='/'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";
echo "<a href='/control'><img src='/img/cabinet.png' title='Личный кабинет'></a>";
echo "<a href='month.php'><img src='/img/calend.png' title='Поручения по месяцам'></a>";
echo "<a href='/control/analytics.php'><img src='/img/search.png' title='Поиск поручений'></a><br/><br/>";

if (isset($_GET) && !empty($_GET)){
	if(isset($_GET['total'])){
		$old_year = $_GET['total'];
		$echo = "Информация по контролю за ".$old_year." год";
		$query_search= "SELECT `c`.`id`,`c`.`dep_id`,`c`.`date`,`c`.`performed`,`c`.`ctrl` FROM `control` AS `c` WHERE YEAR(`c`.`date`)='$old_year'";
		$total_year = true;
	}
	else{
		$month = $_GET['month'];
		$mrus = $month;
		if ($month<10){
			$month = "0".$month;
		}
		$echo = "Информация по контролю за ".$months_rus[$month]." ".$year." года";
	}
}

echo "<h2>".$echo."</h2>";

$query = "SELECT name,id FROM department";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()){
	$deps[] = $row;
}

if($total_year){
	$query = $query_search;
}
else{
	//$query= "SELECT `c`.`id`,`c`.`dep_id`,`c`.`date`,`c`.`ctrl` FROM `control` AS `c` WHERE `c`.`date` LIKE '%-$month-$year'";
	$query= "SELECT `c`.`id`,`c`.`dep_id`,`c`.`date`,`c`.`ctrl` FROM `control` AS `c` WHERE `c`.`date` LIKE '$year-$month-%'";
}
$result = $mysqli->query($query);
$row_cnt = $result ->num_rows;



if($row_cnt){

while ($row = $result->fetch_assoc()){
	foreach ($deps as $dep){
		//Если Департамент равен департаменту искомому
		if ($dep['id'] == $row['dep_id']){
			$dep_name = $dep['name'];
			$dep_id = $dep['id'];
			//Проверяем существуют ли данные записи, если нет, 
			//то приравнием начальное значение равное нулю
			if (!isset($ctrl[$dep_id]['ctrl'])){
				$ctrl["$dep_id"]['ctrl'] = 0;
			}
			if (!isset($ctrl[$dep_id]['unctrl'])){
				$ctrl["$dep_id"]['unctrl'] = 0;
			}
			if (!isset($ctrl[$dep_id]['dead'])){
				$ctrl["$dep_id"]['dead'] = 0;
			}
			if (!isset($ctrl[$dep_id]['total'])){
				$ctrl["$dep_id"]['total'] = 0;
			}
			$ctrl["$dep_id"]['total'] = $ctrl["$dep_id"]['total'] + 1;
			//Если контроль равен 1, т.е. исполнено, то приравнием количество поручений
			//иначе записываем количество не исполненных
			if ($row['ctrl']){
				$ctrl["$dep_id"]['ctrl'] = $ctrl["$dep_id"]['ctrl'] + 1;
			}
			else{
				$date1 = $row['date'];
				//$dates = explode('-',$row['date']);
				//$date1 = $dates[2]."-".$dates[1]."-".$dates[0];
				//$date1 = $dates[0]."-".$dates[1]."-".$dates[2];
				$date2 = date('Y-m-d');
				$datetime1 = new DateTime($date1);
				$datetime2 = new DateTime($date2);
				//print_r($datetime1);
				$interval = $datetime1->diff($datetime2);
				$days_with_symbol = $interval->format('%R%a');
				if ($days_with_symbol > 0){
					$ctrl["$dep_id"]['dead'] = $ctrl["$dep_id"]['dead'] + 1;
				}
				else {
					$ctrl["$dep_id"]['unctrl'] = $ctrl["$dep_id"]['unctrl'] + 1;
				}
			}
			$ctrl["$dep_id"]['name'] = $dep_name;
		}
	}
}

echo "<table border='1'>";
echo "<tr><th >Департамент</th><th>На исполнении</th><th>Просрочено</th><th>Исполнено</th><th>Всего</th></tr>";


$total_unctrl = 0;
$total_dead = 0;
$total_ctrl = 0;
$total = 0;

foreach ($ctrl as $c){
	echo "<tr>";
	echo "<td>".$c['name']."</td>";
	
	if(isset($c['unctrl'])){
		$total_unctrl = $total_unctrl + $c['unctrl'];
		echo "<td>".$c['unctrl']."</td>";		
	}
	else{
		echo "<td>0</td>";
	}
	
	if(isset($c['dead'])){
		$total_dead = $total_dead + $c['dead'];
		echo "<td>".$c['dead']."</td>";
	}
	else{
		echo "<td>0</td>";
	}
	
	if(isset($c['ctrl'])){
		$total_ctrl = $total_ctrl + $c['ctrl'];
		echo "<td>".$c['ctrl']."</td>";
	}
	else{
		echo "<td>0</td>";
	}
	

	$total = $total + $c['total'];
	echo "<td>".$c['total']."</td>";
	echo "</tr>";
}
echo "<tr><td>Всего</td><td>$total_unctrl</td><td>$total_dead</td><td>$total_ctrl</td><td>$total</td></tr>";
echo "</table>";


//Рисуем табличку подробнее
//Рисуем табличку подробнее
//Рисуем табличку подробнее
//Рисуем табличку подробнее
//Рисуем табличку подробнее
//Рисуем табличку подробнее
//Рисуем табличку подробнее
//Рисуем табличку подробнее
//Рисуем табличку подробнее
//Рисуем табличку подробнее
//Рисуем табличку подробнее
//Рисуем табличку подробнее
echo "<br/><br/>";
?>
<form onsubmit="return sh();">
<input type="submit" value="Подробная информация по исполнению" id="button1">
</form>

<?php
Echo "<div id='detail' style='visibility:hidden'><br/><br/>";
$ctrl = [];



if($total_year){
	$query = $query_search;
}
else{
	//$query= "SELECT `c`.`id`,`c`.`dep_id`,`c`.`date`,`c`.`performed`,`c`.`ctrl` FROM `control` AS `c` WHERE `c`.`date` LIKE '$%-$month-$year'";
	$query= "SELECT `c`.`id`,`c`.`dep_id`,`c`.`date`,`c`.`performed`,`c`.`ctrl` FROM `control` AS `c` WHERE `c`.`date` LIKE '$year-$month-%'";
}
$result = $mysqli->query($query);





while ($row = $result->fetch_assoc()){
	foreach ($deps as $dep){
		//Если Департамент равен департаменту искомому
		if ($dep['id'] == $row['dep_id']){
			$dep_name = $dep['name'];
			$dep_id = $dep['id'];
			//Проверяем существуют ли данные записи, если нет, 
			//то приравнием начальное значение равное нулю
			if (!isset($ctrl[$dep_id]['ctrl'])){
				$ctrl["$dep_id"]['ctrl'] = 0;
			}
			if (!isset($ctrl[$dep_id]['unctrl'])){
				$ctrl["$dep_id"]['unctrl'] = 0;
			}
			if (!isset($ctrl[$dep_id]['dead_unctrl'])){
				$ctrl["$dep_id"]['dead_unctrl'] = 0;
			}
			if (!isset($ctrl[$dep_id]['dead_ctrl'])){
				$ctrl["$dep_id"]['dead_ctrl'] = 0;
			}
			if (!isset($ctrl[$dep_id]['total'])){
				$ctrl["$dep_id"]['total'] = 0;
			}
			if (!isset($ctrl[$dep_id]['total_ctrl'])){
				$ctrl["$dep_id"]['total_ctrl'] = 0;
			}
			if (!isset($ctrl[$dep_id]['total_unctrl'])){
				$ctrl["$dep_id"]['total_unctrl'] = 0;
			}
			$ctrl["$dep_id"]['total'] = $ctrl["$dep_id"]['total'] + 1;
			//Если контроль равен 1, т.е. исполнено, то приравнием количество поручений
			//иначе записываем количество не исполненных
			if ($row['ctrl']){
				if ($row['performed']!=0){
					$ctrl["$dep_id"]['dead_ctrl'] = $ctrl["$dep_id"]['dead_ctrl'] + 1;
				}
				else{
					$ctrl["$dep_id"]['ctrl'] = $ctrl["$dep_id"]['ctrl'] + 1;
				}
				$ctrl[$dep_id]['total_ctrl'] = $ctrl[$dep_id]['total_ctrl'] + 1;
			}
			else{
				$date1 = $row['date'];
				//$dates = explode('-',$row['date']);
				//$date1 = $dates[2]."-".$dates[1]."-".$dates[0];
				//$date1 = $dates[0]."-".$dates[1]."-".$dates[2];
				$date2 = date('Y-m-d');
				$datetime1 = new DateTime($date1);
				$datetime2 = new DateTime($date2);
				$interval = $datetime1->diff($datetime2);
				$days_with_symbol = $interval->format('%R%a');
				if ($days_with_symbol > 0){
					$ctrl["$dep_id"]['dead_unctrl'] = $ctrl["$dep_id"]['dead_unctrl'] + 1;
				}
				else {
					$ctrl["$dep_id"]['unctrl'] = $ctrl["$dep_id"]['unctrl'] + 1;
				}
				$ctrl[$dep_id]['total_unctrl'] = $ctrl[$dep_id]['total_unctrl'] + 1;
				$ctrl[$dep_id]['total'] = $ctrl[$dep_id]['total'] + 1;
			}
			$ctrl[$dep_id]['total'] = $ctrl[$dep_id]['total_ctrl'] + $ctrl[$dep_id]['total_unctrl'] ;
			$ctrl["$dep_id"]['name'] = $dep_name;
		}
	}
}

echo "<table border='1'>";
echo "<tr><th rowspan=2>Департамент</th><th colspan=3>На исполнении</th><th colspan=3>Исполнено</th><th rowspan=2>Всего</th>";
echo "</tr>";
echo "<tr><td>В срок</td><td>Не в срок</td><td>Всего</td>";
echo "<td>В срок</td><td>Не в срок</td><td>Всего</td>";
echo "</tr>";
foreach ($ctrl as $c){
	echo "<tr>";
	echo "<td>".$c['name']."</td>";
	
	if(isset($c['unctrl'])){
		echo "<td>".$c['unctrl']."</td>";
	}
	else{
		echo "<td>0</td>";
	}
	
	if(isset($c['dead_unctrl'])){
		echo "<td>".$c['dead_unctrl']."</td>";
	}
	else{
		echo "<td>0</td>";
	}
	

	
	echo "<td><b>".$c['total_unctrl']."</b></td>";
	
	
	if(isset($c['ctrl'])){
		echo "<td>".$c['ctrl']."</td>";
	}
	else{
		echo "<td>0</td>";
	}
	if(isset($c['dead_ctrl'])){
		echo "<td>".$c['dead_ctrl']."</td>";
	}
	else{
		echo "<td>0</td>";
	}
	
	if(isset($c['total_ctrl'])){
		echo "<td><b>".$c['total_ctrl']."</b></td>";
	}
	else{
		echo "<td>0</td>";
	}
	echo "<td>".$c['total']."</td>";
	echo "</tr>";
	}
	echo "</table></div>";
	}
	
else{
	echo "По данному месяцу нет поручений.";
}
?>
</body>
</html>