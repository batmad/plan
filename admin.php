<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('date.php');







$plan = array();



$query_names = "SELECT name,id FROM name";

$result = $mysqli->query($query_names);

while ($row = $result->fetch_assoc()){
	$rows[] = $row;
}

$plan = array();
$ruks = array();
foreach ($rows as $row){
	array_push($ruks,$row['name']);
	foreach($array_week as $day){
		$query_todos = "SELECT descr FROM todo WHERE id_name=".$row['id']." AND `date`='$day'";
		$result2 = $mysqli->query($query_todos) or trigger_error($mysqli->error."[$query_todos]");
		$row2 = $result2->fetch_assoc();
		$plan[$row['name']][$day]=$row2['descr'];
	}
}
echo "<br/>";
echo "<br/>";
echo "<br/>";



echo "<table border=1><tr><td><b>Ф.И.О.</b></td><td><b>Понедельник<br/>$array_week[0]</b></td><td><b>Вторник<br/>$array_week[1]</b></td><td><b>Среда<br/>$array_week[2]</b></td><td><b>Четверг<br/>$array_week[3]</b></td><td><b>Пятница<br/>$array_week[4]</b></td><td><b>Суббота<br/>$array_week[5]</b></td><td><b>Воскресенье<br/>$array_week[6]</b></td></tr>";
foreach ($ruks as $ruk){
	echo "<tr><td>$ruk</td>";
	foreach ($plan[$ruk] as $todo){
		echo "<td width='320'>$todo</td>";
	}
}

	


$mysqli->close();
?>

