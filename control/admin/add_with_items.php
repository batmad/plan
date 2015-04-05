<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
include('checkauth.php');

$_SESSION['search'] = false;
$_SESSION['date'] = '';
$_SESSION['descr'] = '';

if (isset($_POST) && !empty($_POST)){
	$descr = $_POST['descr'];
	$comment = $_POST['comment'];
	$query = "INSERT INTO `control_item` (`descr`) VALUES('$descr')";
	$result = $mysqli->query($query);
	$_SESSION['sid'] = $mysqli->insert_id;
	$id = $_SESSION['sid'];
	header("Location:http://10.50.10.100/control/admin/add.php?id=$id");
}

if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`d`.`name` AS `dep_name`, `n`.`name` AS `spec_name` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) WHERE `c`.`id`='$id'";
	$result = $mysqli->query($query);
}

?>

<a href="/control/admin/index.php">Вернуться </a>
<h2>Новое поручение</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<b>Поручение:</b><br/>
	<textarea name="descr" cols=100 rows=20 ></textarea><br/>

	<input type="submit">
</form>


	
	

	
	
	
	
	
	
	
	
	
	
	