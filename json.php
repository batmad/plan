

<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('date.php');
include('const.php');
?>


<?php

$plan = array();
$myDate = new mDate();
$array_week = $myDate->createArrayWeek();
$monday = $myDate->reverseDate($array_week[0]);
$saturday = $myDate->reverseDate($array_week[5]);


$query="SELECT name,id FROM name WHERE `show_plan`='1' AND `del`<>1 ORDER BY `weight` ";
$res = $mysqli->query($query);
while ($ruk = $res->fetch_assoc()){
	$ruks[] = $ruk;
}
$plan = array('ruks' => array());

foreach ($ruks as $row){
	$arrayDates = array();
	foreach($array_week as $day){
		$descr = null;
		$dayBegin = $myDate->dateBegin($day);
		$dayEnd = $myDate->dateEnd($day);

		$query_todos = "SELECT descr, 
							   DATE_FORMAT(date, '%H:%i') AS hours,
							   place,
							   responsible,
							   id 
						FROM todo 
						WHERE id_name=".$row['id']." 
						AND `date` BETWEEN '$dayBegin' AND '$dayEnd' 
						ORDER BY `date`
					";
						
		$result2 = $mysqli->query($query_todos);

		$arrayDescr = array();
		while($row2 = $result2->fetch_assoc()){
			$arrayDescr = array('date' => $day,
								'id' => $row['id'],
								'event_id' => $row2['id'],
								'name' => $row['name'],
								'hours' => $row2['hours'], 
								'place' => $row2['place'],
								'responsible' => $row2['responsible'],
								'description' => $row2['descr']
								);

			/*$tempArrayDescr = array('date' => $day,
								'name' => $row['name'],
								'hours' => $row2['hours'], 
								'place' => $row2['place'],
								'responsible' => $row2['responsible'],
								'description' => $row2['descr']
								);

			array_push($arrayDescr, $tempArrayDescr);
			$tempArrayDescr = "";*/

			if($row2['hours'] == "00:00"){
				$row2['hours'] = null;
			}
			
			if($row2['responsible'] == ""){
				$row2['responsible'] = null;
			}

		}
		if ($arrayDescr != null){
			array_push($plan['ruks'], $arrayDescr);
		}
		//$plan2[$row['name']][$day]=$arrayDescr; 
		//array_push($arrayDates, $arrayDescr);
		$arrayDescr = "";
		//array_push($arrayDates,"day" $day)
		//$plan2[$row['id']]['day'] = $day;
		//$plan2[$row['id']]['date'] = $arrayDescr;
	}
	//$plan2[$row['id']]['data'] = $arrayDates;
	
}
//array_push($plan['ruks'], $plan2);


$json = json_encode($plan,JSON_UNESCAPED_UNICODE);

echo $json;
?>
