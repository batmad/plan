

<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('date.php');
include('const.php');
?>

<html>
<head>
<title><?php echo PLAN ?></title>
</head>
<body>

<?php
echo "<table><tr>";
echo "<td/><a href='/week.php'><img src='/img/old_calend.png' title='Старый план работы'></a></td>";
echo "<td/><a href='/stuff/'><img src='/img/person.png' title='Персонал'></a></td>";
echo "<td/><a href='/phone.php'><img src='/img/phone.png' title='Справочник IP-телефонов'></a></td>";
echo "<td><a href='/plist.php'><img src='/img/factory.png' title='Подведомственные предприятия'></a></td>";
echo "<td/><a href='/admin/word.php'><img src='/img/word.png' title='Скачать план работы'></a></td>";
echo "<td/><a href='/control.php'><img src='/img/ctrl.png' title='Контроль'></a></td>";
echo "<td/><a href='/vote/index.php'><img src='/img/vote.jpg' title='Голосование'></a></td>";
echo "<td><img src='/img/logo.png' width=45 title='Логотип МинЖКХ'></td>";
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
		$query_todos = "SELECT descr FROM todo WHERE id_name=".$row['id']." AND `date`='$day'";
		$result2 = $mysqli->query($query_todos) or trigger_error($mysqli->error."[$query_todos]");
		$row2 = $result2->fetch_assoc();
		$plan[$row['name']][$day]=nl2br($row2['descr']);
	}
}
echo "<br/>";
echo "<br/>";
echo "<br/>";


$current_date = date('d-m-Y');

echo "<table border=1><tr><th width='250'>Ф.И.О.</th><th width='320'>Понедельник<br/>$array_week[0]</th><th width='320'>Вторник<br/>$array_week[1]</th><th width='320'>Среда<br/>$array_week[2]</th><th width='320'>Четверг<br/>$array_week[3]</th><th width='320'>Пятница<br/>$array_week[4]</th><th width='320'>Суббота<br/>$array_week[5]</th></tr>";
foreach ($ruks as $ruk){
	echo "<tr><td valign='top'>$ruk</td>";
	foreach ($plan[$ruk] as $key => $todo){
		if ($current_date == $key){
			echo "<td width='320' valign='top' bgcolor='#CBFCED'>$todo <br/></td>";
		}
		else{
			echo "<td width='320' valign='top'>$todo <br/></td>";
		}
	}
}
echo "</table>";
	


$mysqli->close();


include("footer.php");
?>

</body>
</html>