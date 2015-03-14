<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('checkauth.php');

if (isset($_POST) && !empty($_POST)){
	$comment = $_POST['comment'];
	$id = $_POST['id'];
	$query = "UPDATE `control` SET `comment` = '$comment' WHERE `id`='$id'";
	$result = $mysqli->query($query);
	header("Location:http://10.50.10.100/control/admin/");
}

if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	$query= "SELECT `c`.`descr`,`c`.`answer`,`c`.`dep_id`,`c`.`date`,`c`.`ctrl`,`d`.`name` AS `dep_name` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) WHERE `c`.`id`='$id'";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
}


?>

<a href="/control/admin/index.php">Вернуться </a>
<h2>Новое поручение</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<b>Срок:  </b></br>
	<?php echo $row['date']; ?><br/>
	<b>Департамент:  </b></br>
	<?php echo $row['dep_name'];?>	<br/>
	<b>Поручение:</b><br/>
	<?php echo $row['descr']; ?><br/>
	<b>Ответ на поручение:</b><br/>
	<?php echo nl2br($row['answer']); ?><br/>
	<b>Комментарий:</b><br/>
	<textarea name="comment" cols=100 rows=20 ></textarea><br/>
	<input type="hidden" name="id" value="<?php echo $id; ?>" >
	<input type="submit">
</form>

	
	

	
	
	
	
	
	
	
	
	
	
	