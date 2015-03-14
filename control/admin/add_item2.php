<?php
mb_internal_encoding("UTF-8");
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('checkauth.php');

if (isset($_POST) && !empty($_POST)){
	$descr = $_POST['descr'];
	$dep_id = $_POST['dep_id'];
	$date = $_POST['date'];
	$comment = $_POST['comment'];
	$id = $_POST['ctrl'];
	$query = "INSERT INTO `control_item` (`control_id`,`descr`,`dep_id`,`date`,`comment`) VALUES('$id','$descr','$dep_id','$date','$comment')";
	$result = $mysqli->query($query);
	header("Location:http://10.50.10.100/control/admin/add_item2.php");
}

if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`d`.`name` AS `dep_name`, `n`.`name` AS `spec_name` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) WHERE `c`.`id`='$id'";
	$result = $mysqli->query($query);
}


$query = "SELECT name,id FROM department";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()){
	$deps[] = $row;
}

$query = "SELECT id,descr FROM control WHERE ctrl = 0";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()){
	$ctrls[] = $row;
}

?>

<a href="add.php">Вернуться </a>
<h2>Новый пункт</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<b>Поручение:  </b></br>
	<select name="ctrl">
	<?php
	foreach($ctrls as $ctrl){
		$descr = mb_substr($ctrl['descr'],0,20);
		echo "<option value=".$ctrl['id'].">".$descr."</option>";
	}
	?>
	</select>
	<br/>
	
	<b>Срок:  </b></br>
	<input type="text" name="date"><br/>
	
	<b>Департамент:  </b></br>
	<select name="dep_id">
	<?php
	foreach ($deps as $dep){
		echo "<option value=".$dep['id'].">".$dep['name']."</option>";
	}
	?>
	</select><br/>
	<b>Поручение:</b><br/>
	<textarea name="descr" cols=100 rows=20 ></textarea><br/>
	<b>Комментарий:</b><br/>
	<textarea name="comment" cols=100 rows=7 ></textarea><br/>
	<input type="submit">
</form>


	
	
	
	
	
	
	
	
	
	
	
	
	