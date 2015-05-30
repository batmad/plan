<?php



header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
echo "<a href='/control/admin/premium.php'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";

if(isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	$month = $_GET['month'];
}
$year = date('Y');
$day = date('t',mktime(0,0,0,$month,1,$year));
$dayBegin = $year."-".$month."-01";
$dayEnd = $year."-".$month."-".$day;

$query= "SELECT `c`.`id`,
				`c`.`spec_id`,
				`c`.`date`,
				`c`.`ctrl`,
				`c`.`descr`,
				`i`.`descr` AS `descr_item`,
				`c`.`performed`,
				`c`.`day_performed`,
				`n`.`name`
		FROM `control` AS `c` 
		LEFT JOIN `name` AS `n` 
		ON (`c`.`spec_id`=`n`.`id`)
		LEFT JOIN `control_item` AS `i` 
		ON (`c`.`control_id`=`i`.`id`) 
		WHERE `c`.`spec_id` = '$id' 
		AND ((`c`.`day_performed` BETWEEN '$dayBegin' AND '$dayEnd') 
		OR (`c`.`date` BETWEEN '$dayBegin' AND '$dayEnd') 
		OR ((`c`.`date` BETWEEN '0000-00-00' AND '$dayEnd') AND `c`.`ctrl`='0'))
		";
$result = $mysqli->query($query);

?>

<table border=1>
<tr>
<th>Поручение</th>
<th>Специалист</th>
<th>Срок</th>
<th>Исполнено</th>
<th>Дни просрочки</th>
</tr>

<?php
while ($row = $result->fetch_assoc()){
	echo "<tr>";
	echo "<td width=500>";
	if (!empty($row['descr_item'])){
		echo $row['descr_item']."<br/>";
	}
	echo $row['descr']."</td>";
	echo "<td>".$row['name']."</td>";
	echo "<td>".$row['date']."</td>";
	echo "<td>".$row['day_performed']."</td>";
	echo "<td>".$row['performed']."</td>";
	echo "</tr>";
}
?>
</table>