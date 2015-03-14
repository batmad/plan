<?php

header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('checkauth.php');

echo "<a href='list.php'>Редактировать сотрудников</a><br/>";


$query= "SELECT s.`id`,s.id_name,s.descr,s.start,s.end,n.name,`n`.`id` AS `real_id` FROM `stuff` AS `s` LEFT JOIN `name` AS `n` ON (`s`.`id_name`=`n`.`id`) ORDER BY n.id_dep,n.weight";


$result = $mysqli->query($query);

echo "<a href='add.php'>Добавить</a>";
echo "<table border='1'>";
$i = 1;

echo "<tr><th>№</th><th>Ф.И.О.</th><th>Описание</th><th>Начало</th><th>Конец</th></tr>";
while ($row = $result->fetch_assoc()){
	if (!empty($row['id'])){
		$row['start'] = date('d-m-Y',$row['start']);
		$row['end'] = date('d-m-Y',$row['end']);
		$descr = nl2br($row['descr']);
		echo "<tr><td  valign='top'>$i</td><td valign='top' >".$row['name']."</td><td  valign='top'>".$descr."<br/><a href='edit.php?id=".$row['id']."&name=".$row['id_name']."'>Редактировать</a></td><td>".$row['start']."</td><td>".$row['end']."</td>";
	}
	else{
		echo "<tr><td  valign='top'>$i</td><td valign='top' >".$row['name']."</td><td  valign='top'><br/><a href='add.php?name=".$row['real_id']."'>Добавить</a></td><td></td>".$row['start']."<td>".$row['end']."</td>";
	}
	$i++;
}
echo "</table>";



?>