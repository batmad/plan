<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
echo "<a href='/'>Назад</a><br/>";


if (isset($_POST) && !empty($_POST)){
	if (isset($_POST['button1'])){
		$id = $_POST['id'];
		$spec_id = $_POST['spec_id'];
		$query = "SELECT `descr` FROM `control` ";
		$result = $mysqli->query($query);
	}
}
	
$query= "SELECT `c`.`dep_id`,`c`.`ctrl`,`d`.`name` AS `dep_name`,COUNT(*) AS count  FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) GROUP BY `c`.`ctrl`,`c`.`dep_id`";
$result = $mysqli->query($query);



?>