<?php
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include($_SERVER['DOCUMENT_ROOT'].'/date.php');
header('Content-type: text/html; charset=utf-8');
echo "<a href='/control/admin'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";

if (isset($_POST) && !empty($_POST)){
	$date = $_POST['date'];
	$query = "INSERT INTO `closed_premium` (`date`) VALUES('$date')";
	$result = $mysqli->query($query);
	$id_closed_premium = $mysqli->insert_id;
	foreach($_POST['data'] as $key=>$value){
		foreach ($value as $k=>$v){
			$data[$key][$k] = $v;
		}
		$days_pros = $data[$key]['days_pros'];
		$total = $data[$key]['total'];
		$dead_ctrl = $data[$key]['dead_ctrl'];
		$ctrl = $data[$key]['ctrl'];
		$name = $data[$key]['name'];
		$sort = $data[$key]['sort'];
		$spec_id = $data[$key]['id'];
		$percent = $data[$key]['percent'];
		$premium = $data[$key]['premium'];
		$query = "INSERT INTO `premium` (`days_pros`,
										`total`,
										`dead_ctrl`,
										`ctrl`,
										`name`,
										`sort`,
										`spec_id`,
										`percent`,
										`premium`,
										`id_closed_premium`)

				VALUES(					'$days_pros',
							  			'$total',
							  			'$dead_ctrl',
							  			'$ctrl',
							  			'$name',
							  			'$sort',
							  			'$spec_id',
							  			'$percent',
							  			'$premium',
							  			'$id_closed_premium'
				  	)";
		$result = $mysqli->query($query);
	}
}

//$search = date('Y')."-".date('m');
//$sql = "SELECT EXISTS (SELECT * FROM `closed_premium` WHERE date = '{$search}')";
//$result = $mysqli->query($sql); 
//$closed_premium = $result->fetch_row();
//$myDate = new mDate();







//$year = date('Y');
//$month = date('m');
$year = "2016";
$month = "01";

$day = date('t',mktime(0,0,0,$month,1,$year));
$dayBegin = $year."-".$month."-01";
$dayEnd = $year."-".$month."-".$day;


//echo "<h2>Премия за ".$myDate->monthRus($month+0)." ".$year."г.</h2>";

function cmp($a, $b) {
  return $a["sort"] - $b["sort"];
}

session_start();
$_SESSION['search'] = false;
$_SESSION['date'] = '';
$_SESSION['descr'] = '';





$query = "SELECT n.name,n.id,n.head,n.id_dep,n.weight FROM name AS n WHERE n.del <> 1 ORDER BY weight";
$result = $mysqli->query($query); 
while ($row = $result->fetch_assoc()){
	$specs[] = $row;
}

//$query= "SELECT `c`.`id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`performed`,`c`.`day_performed`,`n`.`id_dep`,`n`.`weight` FROM `control` AS `c` LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) WHERE `c`.`date` LIKE '$year-$month-%' OR `c`.`day_performed` LIKE '$year-$month-%' ORDER BY `n`.`id_dep`,`n`.`weight` ";
//$query= "SELECT `c`.`id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`c`.`performed`,`c`.`day_performed`,`n`.`id_dep`,`n`.`weight` FROM `control` AS `c` LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) WHERE `c`.`day_performed` LIKE '$year-$month-%' OR ((`c`.`date` BETWEEN '0000-00-00' AND '$year-$month-$day') AND `c`.`ctrl`='0') ORDER BY `n`.`id_dep`,`n`.`weight` ";
$query= "SELECT `c`.`id`,
				`c`.`spec_id`,
				`c`.`date`,
				`c`.`ctrl`,
				`c`.`performed`,
				`c`.`day_performed`,
				`n`.`id_dep`,
				`n`.`weight` 
		FROM `control` AS `c` 
		LEFT JOIN `name` AS `n` 
		ON (`c`.`spec_id`=`n`.`id`) 
		WHERE (`c`.`day_performed` BETWEEN '$dayBegin' AND '$dayEnd') 
		OR (`c`.`date` BETWEEN '$dayBegin' AND '$dayEnd') 
		OR ((`c`.`date` BETWEEN '0000-00-00' AND '$dayEnd') AND `c`.`ctrl`='0') 
		ORDER BY `n`.`id_dep`,`n`.`weight` ";

$result = $mysqli->query($query);




while ($row = $result->fetch_assoc()){

	foreach ($specs as $spec){
		//Если специалист равен искомому специалисту
		if ($spec['id'] == $row['spec_id']){
			$dep_name = $spec['name'];
			$dep_id = $spec['id'];
			$ctrl["$dep_id"]['id'] = $row['spec_id'];
			//Проверяем существуют ли данные записи, если нет, 
			//то приравнием начальное значение равное нулю
			if (!isset($ctrl[$dep_id]['ctrl'])){
				$ctrl["$dep_id"]['ctrl'] = 0;
			}
			if (!isset($ctrl[$dep_id]['dead_ctrl'])){
				$ctrl["$dep_id"]['dead_ctrl'] = 0;
			}
			if (!isset($ctrl[$dep_id]['total'])){
				$ctrl["$dep_id"]['total'] = 0;
			}
			if (!isset($ctrl[$dep_id]['unctrl'])){
				$ctrl["$dep_id"]['unctrl'] = 0;
			}
			if (!isset($ctrl[$dep_id]['days_pros'])){
				$ctrl["$dep_id"]['days_pros'] = 0;
			}
			$ctrl["$dep_id"]['total'] = $ctrl["$dep_id"]['total'] + 1;
			//Если контроль равен 1, т.е. исполнено, то приравнием количество поручений
			//иначе записываем количество не исполненных
			if ($row['ctrl']){
				if ($row['performed']){			
					$ctrl["$dep_id"]['dead_ctrl'] = $ctrl["$dep_id"]['dead_ctrl'] + 1;
					$ctrl["$dep_id"]['days_pros'] = $ctrl["$dep_id"]['days_pros'] + $row['performed'];
				}
				else{
					$ctrl["$dep_id"]['ctrl'] = $ctrl["$dep_id"]['ctrl'] + 1;
				}
			}
			else {
				/**********************
				Если 'год поручения'-'месяц поручения' не равен 'текущий год'-'расчетный месяц',
				тогда приравниваем date1 = текущий год - расчетный месяц - 01
				т.к. априори в запросе в БД - месяц и год срока поручения не могут быть выше 
				расчетного месяца и текущего года.
				Это необходимо в связи с тем, срок поручения как бы устанавливается
				равным началу месяца, потому что у исполнителя в предыдущем месяце была списана 
				премия по прошлому расчетному периоду.
				date2 = текущий год - расчетный месяц - максимальный день 
				Считаем разницу между date1 и date2
				Если разница положительная в пользу date2
				********************/
				$date1 = $row['date'];
				$dates = explode('-',$row['date']);
				if ($dates[0]."-".$dates[1] != $year."-".$month){
					$date1 = $year."-".$month."-01";
				}
				$date2 = $year."-".$month."-".$day;
				$datetime1 = new DateTime($date1);
				$datetime2 = new DateTime($date2);
				$interval = $datetime1->diff($datetime2);
				$days_with_symbol = $interval->format('%R%a');
				$days_without_symbol = $interval->format('%a');
				if ($days_with_symbol > 0){
					$ctrl["$dep_id"]['dead_ctrl'] = $ctrl["$dep_id"]['dead_ctrl'] + 1;
					$ctrl["$dep_id"]['days_pros'] = $ctrl["$dep_id"]['days_pros'] + $days_without_symbol;
				}
				else {
					$ctrl["$dep_id"]['ctrl'] = $ctrl["$dep_id"]['ctrl'] + 1;
				}
			}
			$ctrl["$dep_id"]['name'] = $dep_name;
			$ctrl["$dep_id"]['sort'] = $row['id_dep'].$row['weight'];
		}
	}
}



foreach ($specs as $ruk){
	if ($ruk['head']){
		$ruk_id = $ruk['id'];
		$ruk_id_new = $ruk_id."a";
		$sort = $ruk['id_dep'].$ruk['weight'];
		if(isset($ctrl[$ruk_id])){
			$dep_pros = $ctrl[$ruk_id]['days_pros'];
			$ruk_pros = $ctrl[$ruk_id]['days_pros'];
			$total = $ctrl[$ruk_id]['total'];
			$dead_ctrl = $ctrl[$ruk_id]['dead_ctrl'];
			$c = $ctrl[$ruk_id]['ctrl'];
		}
		else {
			$ruk_pros = 0;
			$dep_pros = 0;
			$total = 0;
			$dead_ctrl = 0;
			$c = 0;
		}
		$count = 1;

		foreach ($specs as $spec) {
			$id = $spec['id'];
			if ($ruk['id_dep'] == $spec['id_dep'] and $ruk['id'] != $spec['id'] and isset($ctrl[$id])){
				$spec_id = $spec['id'];
				$count++;
				$dep_pros = $dep_pros + $ctrl[$spec_id]['days_pros'];
				$total = $total + $ctrl[$spec_id]['total'];
				$dead_ctrl = $dead_ctrl + $ctrl[$spec_id]['dead_ctrl'];
				$c = $c + $ctrl[$spec_id]['ctrl'];
			}
		}
		$dep_pros = round($dep_pros/$count,2);
		$ctrl[$ruk_id_new]['days_pros'] = $ruk_pros + $dep_pros ;
		$ctrl[$ruk_id_new]['total'] = $total;
		$ctrl[$ruk_id_new]['dead_ctrl'] = $dead_ctrl;
		$ctrl[$ruk_id_new]['ctrl'] = $c;
		$ctrl[$ruk_id_new]['name'] = $ruk['name']." общ.";
		$ctrl[$ruk_id_new]['sort'] = $sort;
		$ctrl[$ruk_id_new]['id'] = $ruk_id;
		
	}
}
usort($ctrl, "cmp");
//usort($ctrl, "cmp_weight");



echo "<table border='1'>";
echo "<tr><th>Специалист</th>
		  <th>Всего</th>
		  <th>В срок</th>
		  <th>Просрочено</th>
		  <th>Сумма дней просрочки</th>
		  <th>Проценты</th>
		  <th>Премия</th>";

foreach ($ctrl as $k => $c){
	echo "<tr>";
	echo "<td><a href='personal.php?month=".$month."&id=".$c['id']."'>".$c['name']."</a></td>";
	
	echo "<td>".$c['total']."</td>";
	
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
	if(isset($c['days_pros'])){
		echo "<td>".$c['days_pros']."</td>";
	}
	else{
		echo "<td>0</td>";
	}
	//Формируем процент премии в зависимости от количества дней просрочки
	//В общем формула = количеству дней просрочки * 100% и деленное на количество дней в месяце
	//полученное значение округляем до сотых
	echo "<td>";
	$percent = round($c['days_pros']*100/date('t',mktime(1,1,1,$month,1,$year)),2);
	if ($percent > 100) $percent = 100;
	echo $percent;
	echo "</td>";
	$ctrl[$k]['percent']=$percent;
	echo "<td>";
	$premium = round((100 - $percent)*35.83/100,2);
	if ($premium < 0) $premium = 0;
	echo $premium;
	$ctrl[$k]['premium']=$premium;
	echo "</td>";
	
	echo "</tr>";
}
echo "</table>";



?>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="date" value="<?php echo $search ?>">
<input type="hidden" name="arr" value="$ctrl">
<?php
foreach($ctrl as $key=>$value){
	foreach ($value as $k=>$v){

		echo "<input type='hidden' name='data[".$key."][".$k."]"."' value='$v'>";
	}	

}
?>

</form>
<?php

//if ($closed_premium[0])
//{
	//echo "<h2>Текущий месяц закрыт</h2>";
//}
//else {
	//echo "<input type='submit' name='button1' value='Закрыть расчетный месяц'>";
//}
?>
