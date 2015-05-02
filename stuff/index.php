<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include($_SERVER['DOCUMENT_ROOT'].'/const.php');

?>

<html>
<head>
<title><?php echo PERSONAL ?></title>
</head>
<body>


<?php
echo "<br/><a href='/'><img src='/img/previous.png' title='Вернуться обратно'></a>";
echo "<br/>";
echo "<br/>";


//функция которая возвращает массив из семи дней недели, начиная с понедельника
//по воскресенье в запрошенном формате $reval. Перечень возможных запрашиваемых 
//форматов можно посмотреть в мануале PHP функция date(). 
function date_week($reval){
	$date = date('N');
	$array_week = array();
	if ($date == 1){ 
		for ($i=0;$i<=6;$i++){
			//echo date('N',strtotime("+".$i." day"));
			array_push($array_week,date($reval,strtotime("+".$i." day")));
		}
		return $array_week;
	}
	elseif ($date == 7){
		for ($i=6;$i>=0;$i--){
			array_push($array_week,date($reval,strtotime("-$i day")));
		}
		return $array_week;
	}
	else {
		$i = 1;
		while(date('N',strtotime("-$i day"))!= 1){
			$i++;
		}
		$j = 6 - $i;
	
		while ($i!= 0){
			array_push($array_week,date($reval,strtotime("-$i day")));
		$i--;
		}
		for($i=0;$i<=$j;$i++){
			array_push($array_week,date($reval,strtotime("+$i day")));
		}
		return $array_week;
	}
}
$week_unix = date_week('U');
$week = date_week('d-m-Y');
$current_day = date('d-m-Y');
$ruks = array();
$stuff = array();

//Посылаем запрос в БД лежит ли (первый день недели между началом и концом отпуска) или 
//(последний день недели между началом и концом отпуска) или (начало отпуска между первым 
//и последним днем недели) или (конец отпуска между первым и последним днем недели)
//Первые два запросы нужны для того, чтобы выбрать те записи, которые начались или кончились
//еще до текущей недели. А два последних запроса нужны потому что, если промежуток отпуска
//меньше, чем начало и конец недели.
  
$query= "SELECT s.id,s.id_name,s.descr,s.start,s.end, n.name AS name FROM `stuff` AS `s` LEFT JOIN `name` AS `n` ON (`s`.`id_name`=`n`.`id`) WHERE '$week_unix[0]' BETWEEN `s`.`start` AND `s`.`end` OR '$week_unix[6]' BETWEEN `s`.`start` AND `s`.`end` OR `s`.`start` BETWEEN '$week_unix[0]' AND '$week_unix[6]' OR `s`.`end` BETWEEN '$week_unix[0]' AND '$week_unix[6]' ORDER BY n.weight ";
$result = $mysqli->query($query);


while ($row = $result->fetch_assoc()){
	if (!in_array($row['name'],$ruks)){
		$ruks[] = $row['name'];
	}
	for ($i=0;$i<=6;$i++){

		if ($week_unix[0] > $row['start'] ){
			$row['start'] = $week_unix[0];
		}
		
		$day = $week[$i];
		$dmy_start = date('d-m-Y',$row['start']);
		$dmy_end = date('d-m-Y',$row['end']);
		
		if (($day == $dmy_start && $row['start'] < $row['end']) ){
			$row['start'] = $row['start'] + 86400;
			$descr = nl2br($row['descr']);
			if(empty($stuff[$row['name']][$day])){
				$stuff[$row['name']][$day] = $descr;
			}
			else{
				$stuff[$row['name']][$day] = $stuff[$row['name']][$day]."<br/><br/>".$descr;
			}
		}
		else {
				if(!isset($stuff[$row['name']][$day])){
					$stuff[$row['name']][$day] = '';
				}
		}
	}
}




echo "<table border='1'>";
echo "<table border=1><tr><th width='250'>Ф.И.О.</th><th width='320'>Понедельник<br/>$week[0]</th><th width='320'>Вторник<br/>$week[1]</th><th width='320'>Среда<br/>$week[2]</th><th width='320'>Четверг<br/>$week[3]</th><th width='320'>Пятница<br/>$week[4]</th><th width='320'>Суббота<br/>$week[5]</th><th width='320'>Воскресенье<br/>$week[6]</th></tr>";
foreach ($ruks as $ruk){
	echo "<tr><td>".$ruk."</td>";
	foreach ($stuff[$ruk] as $key =>$value){
	
		if ($key == $current_day){
			echo "<td bgcolor='#CBFCED'>".$value."</td>";
		}
		else{
			echo "<td>".$value."<br/></td>";
		}
	}
	echo "</tr>";
}
echo "</table>";


?>