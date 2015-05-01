<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');

if (isset($_POST) && !empty($_POST)){
	if (isset($_POST['button1'])){
		$id = $_POST['id'];
		$descr = $_POST['descr'];
		$query = "UPDATE `control_item` SET `descr`='$descr' WHERE `id`='$id'";
		$result = $mysqli->query($query);
		$sid = $_POST['id'];
		header("Location:http://$_SERVER[SERVER_ADDR]/control/admin/add.php?id=$sid");
	}
	if (isset($_POST['button2'])){
		$id = $_POST['id'];
		$query = "DELETE FROM control_item WHERE `id`='$id'";
		$result = $mysqli->query($query);
		header("Location:http://$_SERVER[SERVER_ADDR]/control/admin/");
	}
}

if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	$query= "SELECT `id`,`descr` FROM `control_item` WHERE `id`='$id'";
	$result = $mysqli->query($query);
}

$row = $result->fetch_assoc();


?>

<a href="/control/admin/index.php">Вернуться </a>
<h2>Редактирование поручения</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">

	<b>Поручение:</b><br/>
	<textarea name="descr" cols=100 rows=10 ><?php echo $row['descr']?></textarea><br/>
   	<input type="hidden" name="id" value="<?php echo $id?>">
	<input type="submit" name='button1'>
	</form>

	<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<input type="hidden" name="id" value="<?php echo $id?>">
	<input type="submit" name='button2' value="Удалить">
	</form>
	
	
	
	
	
	
	
	
	
	
	
	
	