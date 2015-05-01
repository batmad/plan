<html>
<head>
<title>Справочник IP-телефонов Министерства ЖКХ и энергетики РС(Я)</title>
</head>
<body>
<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
echo "<br/><a href='/index.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";


$query = "SELECT `n`.`name`,
				 `n`.`id`,
				 `n`.`iptel`,
				 `n`.`id_dep`,
				 `n`.`weight`,
				 `d`.`name` AS `dep_name` 
		  FROM `name` AS `n` 
		  LEFT JOIN `department` AS `d` 
		  ON (`n`.`id_dep`=`d`.`id`) 
		  ORDER BY `n`.`id_dep`, `n`.`weight`";

$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()){
	$workers[] = $row;
}


$mysqli->close();
?>

<div align=center>



<?php

$department = null;
foreach ($workers as $worker){
	if ($department != $worker['id_dep']){
		echo "</table>";
		echo "<p><b>$worker[dep_name]</b></p>";
		echo "<table border=1>";
		$department = $worker['id_dep'];
	}
	echo "<tr><td align=center>$worker[name]</td><td>$worker[iptel]</td></tr>";
}
?>

</div>

