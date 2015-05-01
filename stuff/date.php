<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
//include('checkauth.php');
$date = date('U',mktime(0,0,0,2,12,2014));
echo $date;
echo "<br/>";
$date2 = date('U',mktime(0,0,0,2,13,2014));
echo $date2;
echo $date2-$date;
echo "<br/>";
echo date('U');

echo "<a href='list.php'>Редактировать сотрудников</a><br/>";


$query= "SELECT s.id,s.id_name,s.descr,s.weight,s.date,n.name,`n`.`id` AS `real_id` FROM `name` AS `n` LEFT JOIN `stuff` AS `s` ON (`s`.`id_name`=`n`.`id`) ORDER BY weight";


$result = $mysqli->query($query);

echo "<a href='add.php'>Добавить</a>";
echo "<table border='1'>";
$i = 1;

echo "<tr><th>№</th><th>Ф.И.О.</th><th>Описание</th><th>Приоритет</th><th>Дата</th></tr>";
while ($row = $result->fetch_assoc()){
	if (!empty($row['id'])){
		$descr = nl2br($row['descr']);
		echo "<tr><td  valign='top'>$i</td><td valign='top' >".$row['name']."</td><td  valign='top'>".$descr."<br/><a href='edit.php?id=".$row['id']."&name=".$row['id_name']."'>Редактировать</a></td><td>".$row['weight']."</td><td>".$row['date']."</td>";
	}
	else{
		echo "<tr><td  valign='top'>$i</td><td valign='top' >".$row['name']."</td><td  valign='top'><br/><a href='add.php?name=".$row['real_id']."'>Добавить</a></td><td></td>".$row['weight']."<td>".$row['date']."</td>";
	}
	$i++;
}
echo "</table>";


?>