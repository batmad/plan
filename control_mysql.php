

<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
echo "<a href='/'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";
echo "<a href='/control'><img src='/img/cabinet.png' title='Личный кабинет'></a><br/><br/>";


$query = "SELECT name,id FROM department";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()){
	$deps[] = $row;
}

$query= "SELECT `c`.`dep_id`,`c`.`ctrl`,`d`.`name` AS `dep_name`,COUNT(*) AS count  FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) GROUP BY `c`.`ctrl`,`c`.`dep_id`";
$result = $mysqli->query($query);





while ($row = $result->fetch_assoc()){
	foreach ($deps as $dep){
		//Если Департамент равен департаменту искомому
		if ($dep['id'] == $row['dep_id']){
			$dep_name = $dep['name'];
			$dep_id = $dep['id'];
			//Если контроль равен 1, т.е. исполнено, то приравнием количество поручений
			//иначе записываем количество не исполненных
			if ($row['ctrl']){
				$ctrl["$dep_id"]['ctrl'] = $row['count'];
			}
			else{
				$ctrl["$dep_id"]['unctrl'] = $row['count'];
			}
			$ctrl["$dep_id"]['name'] = $dep_name;
		}
	}
}

echo "<table border='1'>";
echo "<tr><th>Департамент</th><th>На исполнении</th><th>Исполнено</th></tr>";

foreach ($ctrl as $c){
	echo "<tr>";
	echo "<td>".$c['name']."</td>";
	
	if(isset($c['unctrl'])){
		echo "<td>".$c['unctrl']."</td>";
	}
	else{
		echo "<td>0</td>";
	}
	
	if(isset($c['ctrl'])){
		echo "<td>".$c['ctrl']."</td>";
	}
	else{
		echo "<td>0</td>";
	}
	echo "</tr>";
}
echo "</table>";
?>