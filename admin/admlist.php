<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");
$result = setlocale(LC_ALL, 'ru_RU.UTF-8');


echo "<a href='/admin/list.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";

include('admineditbar.php');

$query_names = "SELECT name,username,id FROM admins";
$result = $mysqli->query($query_names);
while ($row = $result->fetch_assoc()){
	$rows[] = $row;
}


echo "<br/>";
echo "<br/>";
echo "<br/>";




echo "<table border=1><th>Ф.И.О.</th><th>Редактировать</th>";
foreach ($rows as $row){
	echo "<tr><td valign='top'>".$row['name']."</td>";
	echo "<td valign='top'><a href='/admin/editformadm.php?id=".$row['id']."'><img src='/img/edit.png' title='Редактировать'></a></td></tr>";
}

	


$mysqli->close();
?>

