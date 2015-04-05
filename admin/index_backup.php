<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
include('checkauth.php');

if (isset($_GET) && !empty($_GET)){
	include('date_nw.php');
	$nextweek='yes';
	echo "<a href='/admin/index.php'>Вернуться обратно</a>";
}
else{
	include('date.php');
	$nextweek='no';
	echo "<a href='/admin/index.php?plan=nextweek'>Составить план работы на следующую неделю</a>";
}

setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');



echo "<br/><a href='/admin/list.php'>Редактировать сотрудников</a>";
$plan = array();



$query_names = "SELECT name,id FROM name WHERE `show`='1' ORDER BY weight";

$result = $mysqli->query($query_names);


while ($row = $result->fetch_assoc()){
	$rows[] = $row;
}

$plan = array();
$ruks = array();
foreach ($rows as $row){
	array_push($ruks,$row['name']);
	foreach($array_week as $day){
		$query_todos = "SELECT descr,id FROM todo WHERE id_name=".$row['id']." AND `date`='$day'";
		$result2 = $mysqli->query($query_todos) or trigger_error($mysqli->error."[$query_todos]");
		$row2 = $result2->fetch_assoc();
		$plan[$row['name']][$day]['descr']=nl2br($row2['descr']);
		$plan[$row['name']][$day]['id'] = $row2['id']; 
		$plan[$row['name']][$day]['id_name'] = $row['id'];
		$plan[$row['name']][$day]['date'] = $day;
	}
}
echo "<br/>";
echo "<br/>";
echo "<br/>";




echo "<table border=1><tr><th width='250'>Ф.И.О.</th><th width='320'>Понедельник<br/>$array_week[0]</th><th width='320'>Вторник<br/>$array_week[1]</th><th width='320'>Среда<br/>$array_week[2]</th><th width='320'>Четверг<br/>$array_week[3]</th><th width='320'>Пятница<br/>$array_week[4]</th><th width='320'>Суббота<br/>$array_week[5]</th></tr>";
foreach ($ruks as $ruk){
	echo "<tr><td valign='top'>$ruk</td>";
	foreach ($plan[$ruk] as $todo){
		if (!empty($todo['id'])){
			echo "<td width='320' valign='top'>".$todo['descr']."<br><br><a href=\"editform.php?id=".$todo['id']."&nextweek=".$nextweek."\">Редактировать</a></td>";
		}
		else {
			echo "<td width='320' valign='top'><a href=\"/admin/addform.php?id=".$todo['id_name']."&date=".$todo['date']."&nextweek=".$nextweek."\">Добавить</a></td>";
		}
	}
}

	


$mysqli->close();
?>

