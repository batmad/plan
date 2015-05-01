<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");
$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

echo "<a href='index.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";

echo "<br/><br/><a href='/stuff/admin/addcli.php'><img src='/img/add_person.png' title='Добавить сотрудника'></a>";

$query_names = "SELECT name,id,id_dep,weight FROM name ORDER BY weight";

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
	echo "<td valign='top'><a href='/stuff/admin/editcli.php?id=".$row['id']."&name=".$row['name']."&id_dep=".$row['id_dep']."&weight=".$row['weight']."'>Редактировать</a></td></tr>";
}

	


$mysqli->close();
?>

