<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('date.php');

$myDate = new mDate();
$array_week = array();
if (isset($_GET) && !empty($_GET)){
	echo "<a href='/week.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";
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



$query_names = "SELECT `name`,`id` FROM `name` WHERE `show_plan`='1' ORDER BY weight";
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
		$query_todos = "SELECT descr, DATE_FORMAT(date, '%H:%i') AS hours,place,responsible FROM todo WHERE id_name=".$row['id']." AND `date` BETWEEN '$dayBegin' AND '$dayEnd' ORDER BY `date`";
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
				$descr = $descr." <hr/> <b>".$row2['hours']." </b>".nl2br($row2['descr'])."$row2[place] $row2[responsible]<br/>";
			}
			else{
				$descr = $descr." <b>".$row2['hours']." </b>".nl2br($row2['descr'])."$row2[place] $row2[responsible]<br/>";
			}
		}
		$plan[$row['name']][$day]=$descr; 
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

	


$mysqli->close();
?>

