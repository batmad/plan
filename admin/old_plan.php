<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include($_SERVER['DOCUMENT_ROOT'].'/date.php');
//include('date.php');


$myDate = new mDate();
$array_week = array();
if (isset($_GET) && !empty($_GET)){
	echo "<a href='/admin/week.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";
	echo "<a href='/admin/word.php?old=yes&";
	$i = 0;
	foreach($_GET['old_plan'] as $old_plan){
		array_push($array_week,$old_plan);
		echo "old_plan[$i]=";
		echo $old_plan;
		echo "&";
		$i++;
	}
	echo "'><img src='/img/word.png' title='Скачать план работы'></a>";
}



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
		$descr = null;
		$dayBegin = $myDate->dateBegin($day);
		$dayEnd = $myDate->dateEnd($day);

		$query_todos = "SELECT descr,id,DATE_FORMAT(date, '%H:%i') AS hours,place,responsible FROM todo WHERE id_name=".$row['id']." AND `date` BETWEEN '$dayBegin' AND '$dayEnd' ORDER BY `date`";
		$result2 = $mysqli->query($query_todos);
		while($row2 = $result2->fetch_assoc()){
			if($row2['hours'] == "00:00"){
				$row2['hours'] = null;
			}
			if ($row2['place'] != ""){
				$row2['place'] = "<br/><b>".$row2['place']."</b>";
			}
			if($row2['responsible'] == ""){
				$row2['responsible'] = null;
			}
			else{
				$row2['responsible'] = "<br/><i>Отв.".$row2['responsible']."</i>";
			}

			if($descr != null){
				$descr = $descr." <hr/> <b>".$row2['hours']."</b> ".nl2br($row2['descr'])."$row2[place] $row2[responsible]<br/><a href=\"editform.php?id=".$row2['id']."\"><img src='/img/edit.png' title='Редактировать'></a>";
			}
			else{
				$descr = $descr."<b valign=top>".$row2['hours']."</b> ".nl2br($row2['descr'])."$row2[place] $row2[responsible]<br/><a href=\"editform.php?id=".$row2['id']."\"><img src='/img/edit.png' title='Редактировать'></a>";
			}
		}
		$plan[$row['name']][$day]['descr']=$descr;
		$plan[$row['name']][$day]['id_name'] = $row['id'];
		$plan[$row['name']][$day]['date'] = $day;
	}
}
echo "<br/>";
echo "<br/>";
echo "<br/>";

$current_date = date('d-m-Y');



echo "<table border=2 bordercolor='#876'>
		<tr>
			<th width='250'>Ф.И.О.</th>
			<th width='320'>Понедельник<br/>$array_week[0]</th>
			<th width='320'>Вторник<br/>$array_week[1]</th>
			<th width='320'>Среда<br/>$array_week[2]</th>
			<th width='320'>Четверг<br/>$array_week[3]</th>
			<th width='320'>Пятница<br/>$array_week[4]</th>
			<th width='320'>Суббота<br/>$array_week[5]</th>
		</tr>
	";

foreach ($ruks as $ruk){
	echo "<tr><td valign='top'>$ruk</td>";
	foreach ($plan[$ruk] as $key => $todo){
		if (!empty($todo['descr'])){
			if ($current_date == $key){
				echo "<td width='320' valign='top' bgcolor='#CBFCED' >".$todo['descr']."<hr/><a href=\"/admin/addform.php?id=".$todo['id_name']."&date=".$todo['date']."\"><img src='/img/add.png' title='Добавить'></a></td>";
			}
			else {
				echo "<td width='320' valign='top'>".$todo['descr']."<hr/><a href=\"/admin/addform.php?id=".$todo['id_name']."&date=".$todo['date']."\"><img src='/img/add.png' title='Добавить'></a></td>";
			}
		}
		else {
			if ($current_date == $key){
				echo "<td width='320' valign='top' bgcolor='#CBFCED'><a href=\"/admin/addform.php?id=".$todo['id_name']."&date=".$todo['date']."\"><img src='/img/add.png' title='Добавить'></a></td>";
			}
			else {
				echo "<td width='320' valign='top'><a href=\"/admin/addform.php?id=".$todo['id_name']."&date=".$todo['date']."\"><img src='/img/add.png' title='Добавить'></a></td>";
			}
		}
	}
}

	


$mysqli->close();
?>

