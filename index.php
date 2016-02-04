

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
echo "<td/><a href='/rest/plan.apk'><img src='/img/android.png' title='Приложение Android'></a></td>";
echo "<td><img src='/img/logo.png' width=45 title='Логотип МинЖКХ'></td>";
echo "</tr></table>";

$plan = array();
$myDate = new mDate();
$array_week = $myDate->createArrayWeek();
$monday = $myDate->reverseDate($array_week[0]);
$saturday = $myDate->reverseDate($array_week[5]);


$query="SELECT name,id FROM name WHERE `show_plan`='1' AND `del`<>1 ORDER BY `weight`";
$res = $mysqli->query($query);
while ($ruk = $res->fetch_assoc()){
	$ruks[] = $ruk;
}



foreach ($ruks as $row){
	foreach($array_week as $day){
		$descr = null;
		$dayBegin = $myDate->dateBegin($day);
		$dayEnd = $myDate->dateEnd($day);

		$query_todos = "SELECT descr, 
							   DATE_FORMAT(date, '%H:%i') AS hours,
							   place,
							   responsible 
						FROM todo 
						WHERE id_name=".$row['id']." 
						AND `date` BETWEEN '$dayBegin' AND '$dayEnd' 
						ORDER BY `date`
					";
						
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
echo "<table border=1>
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
	echo "<tr><td valign='top'>$ruk[name]</td>";
	foreach ($plan[$ruk['name']] as $key => $todo){
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