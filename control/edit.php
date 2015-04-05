<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'bd.php');
include('checkauth.php');
$current_date = date('Y-m-d');

if (isset($_POST) && !empty($_POST)){
	$id = $_POST['id'];
	$answer = $_POST['answer'];
	$date = $_POST['date'];
	$spec_id = $_POST['spec_id'];
	$timestamp = date('Y-m-d H:i:s');
	
	$query = "INSERT INTO `message` (`id_ctrl`,`id_spec`,`date`) VALUES ('$id','$spec_id','$timestamp') ";
	$result = $mysqli->query($query);
	
	$query = "UPDATE `control` SET `answer`='$answer',`day_answer`='$date' WHERE `id`='$id'";
	$result = $mysqli->query($query);
	header("Location:http://10.50.10.100/control/");
}

if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`d`.`name` AS `dep_name`, `n`.`name` AS `spec_name` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) WHERE `c`.`id`='$id'";
	$result = $mysqli->query($query);
}

$row = $result->fetch_assoc();



?>

<a href="/control/index.php">Вернуться </a>
<h2>Редактирование поручения</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<b>Поручение:</b><br/>
	<p><?php echo nl2br($row['descr'])?><br/></p>
	<b>Срок:  </b><?php echo $row['date']?></br></br>
	<b>Ответ:</b> <br/><textarea name="answer" cols=100 rows=50 ><?php echo $row['answer']?></textarea><br/>
   	<input type="hidden" name="id" value="<?php echo $id?>">
	<input type='hidden' name='date' value="<?php echo $current_date;?>">
	<input type='hidden' name='spec_id' value="<?php echo $row['spec_id'];?>">
	<input type="submit" >
	</form>
	
	
	
	
	
	
	
	
	
	
	
	
	
	