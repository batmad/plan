<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
include('checkauth.php');

$current_date = date('d-m-Y');
$day = date('d');
$month = date('m');
$year = date('Y');

echo "<a href='/control/admin'><img src='/img/previous.png' title='Вернуться обратно'></a><br/><br/>";


		
function RGBToHex($r, $g, $b) {
	//String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
	$hex = "#";
	$hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
	$hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
	$hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
 
	return $hex;
}



$query= "SELECT `m`.`id`,
				`m`.`id_ctrl`,
				`m`.`id_spec`,
				`m`.`date`,
				`n`.`name`,
				`c`.`descr`,
				`ci`.`descr` AS `item`
		FROM `message` AS `m`
		LEFT JOIN `name` AS `n`
		ON (`n`.`id`=`m`.`id_spec`)
		LEFT JOIN `control` AS `c`
		ON (`c`.`id`=`m`.`id_ctrl`)
		LEFT JOIN `control_item` AS `ci`
		ON (`c`.`control_id`=`ci`.`id`)
		WHERE (`m`.`read`=0 )";
		
$result = $mysqli->query($query);




echo "<table border='1'>";
echo "<tr><th>№</th>";
echo "<th>Дата</th>";
echo "<th width='200px'>Поручение </th>";
echo "<th>Сообщение</th>";
echo "<th valign='top'>Специалист</th>";



$i=1;
mb_internal_encoding("UTF-8");

while ($row = $result->fetch_assoc()){
	echo "<tr>";
	echo "<td>".$i."</td>"; 
	echo "<td>".$row['date']."</td>"; 
	echo "<td width='300px'>";
	if (!empty($row['item'])){
		echo $row['item']."<br/>";
	}
	echo $row['descr']."</td>"; 
	echo "<td><a href='message.php?id=".$row['id']."&ctrl=".$row['id_ctrl']."'>Прочитать</a></td>";
	echo "<td>".$row['name']."</td>";
	$i++;
}
echo "</table>";


?>
</div>