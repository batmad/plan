<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');

echo "<table><tr>";
if (isset($_GET['nextweek']) && !empty($_GET['nextweek']) && $_GET['nextweek']=="yes"){
	include('date_nw.php');
	$nextweek='yes';
	echo "<a href='/admin/index.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";
	echo "<td><a href='/admin/list.php'><img src='/img/edit_person.png' title='Редактировать сотрудников'></a></td>";
	echo "<td><a href='/admin/plist.php'><img src='/img/edit_factory.png' title='Редактировать подведомственные предприятия'></a></td>";
	echo "<td><a href='/admin/week.php'><img src='/img/old_calend.png' title='Редактировать старый план работы'></a></td>";
	echo "<td><a href='/admin/word.php?nextweek=yes'><img src='/img/word.png' title='Скачать план работы'></a></td>";
}
else{
	include('date.php');
	$nextweek='no';
	echo "<td><a href='/admin/index.php?nextweek=yes'><img src='/img/calend.png' title='Составить план работы на следующую неделю'></a></td>";
	echo "<td><a href='/admin/list.php'><img src='/img/edit_person.png' title='Редактировать сотрудников'></a></td>";
	echo "<td><a href='/admin/plist.php'><img src='/img/edit_factory.png' title='Редактировать подведомственные предприятия'></a></td>";
	echo "<td><a href='/admin/week.php'><img src='/img/old_calend.png' title='Редактировать старый план работы'></a></td>";
	echo "<td><a href='/admin/word.php'><img src='/img/word.png' title='Скачать план работы'></a></td>";
}

setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');




echo "</tr></table>";

$plan = array();



$query_names = "SELECT name,id FROM name WHERE `show_plan`='1' ORDER BY weight";

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

$current_date = date('d-m-Y');


echo "<table border=2 bordercolor='#876'><tr><th width='250'>Ф.И.О.</th><th width='320'>Понедельник<br/>$array_week[0]</th><th width='320'>Вторник<br/>$array_week[1]</th><th width='320'>Среда<br/>$array_week[2]</th><th width='320'>Четверг<br/>$array_week[3]</th><th width='320'>Пятница<br/>$array_week[4]</th><th width='320'>Суббота<br/>$array_week[5]</th></tr>";
foreach ($ruks as $ruk){
	echo "<tr><td valign='top'>$ruk</td>";
	foreach ($plan[$ruk] as $key => $todo){
		if (!empty($todo['id'])){
			if ($current_date == $key){
				echo "<td width='320' valign='top' bgcolor='#CBFCED' >".$todo['descr']."<a href=\"editform.php?id=".$todo['id']."&nextweek=".$nextweek."\"><img src='/img/edit.png' title='Редактировать'></a></td>";
			}
			else {
				echo "<td width='320' valign='top'>".$todo['descr']."<a href=\"editform.php?id=".$todo['id']."&nextweek=".$nextweek."\"><img src='/img/edit.png' title='Редактировать'></a></td>";
			}
		}
		else {
			if ($current_date == $key){
				echo "<td width='320' valign='top' bgcolor='#CBFCED'><a href=\"/admin/addform.php?id=".$todo['id_name']."&date=".$todo['date']."&nextweek=".$nextweek."\"><img src='/img/add.png' title='Добавить'></a></td>";
			}
			else {
				echo "<td width='320' valign='top'><a href=\"/admin/addform.php?id=".$todo['id_name']."&date=".$todo['date']."&nextweek=".$nextweek."\"><img src='/img/add.png' title='Добавить'></a></td>";
			}
		}
	}
}

	


$mysqli->close();
?>

