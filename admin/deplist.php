<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");
$result = setlocale(LC_ALL, 'ru_RU.UTF-8');


echo "<a href='/admin/index.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";

echo "<br/><br/><a href='/admin/addformcli.php'><img src='/img/add_person.png' title='Добавить сотрудника'></a>&nbsp;&nbsp;";
echo "<a href='/admin/addformclitech.php'><img src='/img/add_person_tech.png' title='Добавить администратора'></a>&nbsp;&nbsp;";
echo "<a href='/admin/admlist.php'><img src='/img/edit_person_tech.png' title='Редактировать администраторов'></a>&nbsp;&nbsp;";
echo "<a href='/admin/addformdep.php'><img src='/img/add_department.png' title='Добавить департамент'></a>&nbsp;&nbsp;";
echo "<a href='/admin/deplist.php'><img src='/img/edit_department.png' title='Редактировать департаменты'></a>&nbsp;&nbsp;";

$query_names = "SELECT name,short,id FROM department";
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
	echo "<td valign='top'><a href='/admin/editformdep.php?id=".$row['id']."'><img src='/img/edit.png' title='Редактировать'></a></td></tr>";
}

	


$mysqli->close();
?>

