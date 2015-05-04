

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

$plan = array();
$myDate = new mDate();
$array_week = $myDate->createArrayWeek();
$monday = $myDate->reverseDate($array_week[0]);
$saturday = $myDate->reverseDate($array_week[5]);


$query="SELECT name,id FROM name WHERE `show_plan`='1' ORDER BY `weight` ";
$res = $mysqli->query($query);
while ($ruk = $res->fetch_assoc()){
	$ruks[] = $ruk;
}



foreach ($ruks as $row){
	foreach($array_week as $day){
		$descr = null;
		$dayBegin = $myDate->dateBegin($day);
		$dayEnd = $myDate->dateEnd($day);

		$query_todos = "SELECT descr, DATE_FORMAT(date, '%H:%i') AS hours,place,responsible FROM todo WHERE id_name=".$row['id']." AND `date` BETWEEN '$dayBegin' AND '$dayEnd'";
		$result2 = $mysqli->query($query_todos);
		while($row2 = $result2->fetch_assoc()){
			
			if($row2['hours'] == "00:00"){
				$row2['hours'] = null;
			}

			if($row2['responsible'] == ""){
				$row2['responsible'] = null;
			}
			else{
				$row2['responsible'] = "<br/><i>Отв.".$row2['responsible']."</i>";
			}



			if($descr != null){
				$descr = $descr." <hr/> <b>".$row2['hours']." </b>".nl2br($row2['descr'])."<br/><b>$row2[place]</b>$row2[responsible]<br/>";
			}
			else{
				$descr = $descr." <b>".$row2['hours']." </b>".nl2br($row2['descr'])."<br/><b>$row2[place]</b>$row2[responsible]<br/>";
			}
		}
		$plan[$row['name']][$day]=$descr; 
	}
}
echo "<br/>";
echo "<br/>";
echo "<br/>";

$json = json_encode($ruks,JSON_UNESCAPED_UNICODE);

echo $json;
?>
</body>
</html>