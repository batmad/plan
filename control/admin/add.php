<script type="text/javascript" src="script.js"></script>
<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');
include('function.php');

$flag_get = false;
$_SESSION['search'] = false;
$_SESSION['date'] = '';
$_SESSION['descr'] = '';


if (isset($_POST) && !empty($_POST)){
	if (isset($_POST['button1'])){
		$descr = $_POST['descr'];
		$dep_id = $_POST['dep_id'];
		$date = correct_date($_POST['date']);
		$comment = $_POST['comment'];
		$id = $_POST['id'];
		$query = "INSERT INTO `control` (`descr`,`dep_id`,`date`,`comment`,`control_id`) VALUES('$descr','$dep_id','$date','$comment','$id')";
		$result = $mysqli->query($query);
		
		if($_SESSION['is_entering_item']){
			header("Location:http://$_SERVER[SERVER_ADDR]/control/admin/add.php?id=$id");
			exit();
		}
		else{
			header("Location:http://$_SERVER[SERVER_ADDR]/control/admin/index.php");
			exit();
		}
	}
	else if (isset($_POST['button2'])){
		$_SESSION['is_entering_item'] = false;
		header("Location:http://$_SERVER[SERVER_ADDR]/control/admin/index.php");
		exit();
	}
	else {
        echo 'ошибка';
    }
}

if (isset($_GET) && !empty($_GET)){
	//$id = $_GET['id'];
	//$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`answer`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`d`.`name` AS `dep_name`, `n`.`name` AS `spec_name` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) WHERE `c`.`id`='$id'";
	//$result = $mysqli->query($query);
	$_SESSION['is_entering_item'] = true;
	$id = $_GET['id'];
	$query= "SELECT `id`,`descr` FROM `control_item` WHERE `id`='$id'";
	$result = $mysqli->query($query);
	$item = $result->fetch_assoc();
	$flag_get = true;
}


$query = "SELECT name,id FROM department";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()){
	$deps[] = $row;
}
?>

<a href="/control/admin/index.php">Вернуться </a>
<h2>Новое поручение</h2>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" onsubmit="return postin();">
<?php
	if (isset($_GET) && !empty($_GET)){
		echo "<b>Поручение:</b><br/>";
		echo $item['descr'];
		echo "<a href='edit_item.php?id=".$item['id']."'>Редактировать</a><br/>";
	}
?>	
	<div id="dateSpan">
	<b>Срок:  </b></br>
	<input type="text" name="date" id="date"></div><br/>
	
	<div id="department">
	<b>Департамент:  </b></br>
	<select name="dep_id" id="dep_id">
	<option value=""></option>
	<?php
	foreach ($deps as $dep){
		echo "<option value=".$dep['id'].">".$dep['name']."</option>";
	}
	?> 
	</select></div><br/>
<div id="descrSpan" ">
<?php
	if (isset($_GET) && !empty($_GET)){
		echo "<b>Пункт поручения:</b><br/>";
	}
	else{
		echo "<b>Поручение:</b><br/>";
	}
?>	
	
	<textarea name="descr" id="descr" cols=100 rows=20 ></textarea></div><br/>
	
	
	<b>Комментарий:</b><br/>
	<textarea name="comment" cols=100 rows=7 ></textarea><br/>
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<input type="submit" name='button1' value="Отправить">
</form>

<div id="report" style="color:red; border:solid 1px red; visibility:hidden;"></div>

<?php 
if ($flag_get){
	echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
	echo "<input type='hidden' name='id' value='$id'>";
	echo "<input type='submit' name='button2' value='Завершить'>";
	echo "</form>";

}

?>


<?php

if ($flag_get && !empty($_SESSION['is_entering_item']) && $_SESSION['is_entering_item']){	
	echo "<b>Пункты:</b>";
	echo "<table border=1>";
	echo "<tr><th>Срок</th><th>Описание</th><th>Департамент</th><th>Редактировать</th></tr>";
	$query = "SELECT `id`,`descr`,`dep_id`,`date` FROM `control` WHERE `control_id`='$id'";
	$result = $mysqli->query($query);
	while ($row = $result->fetch_assoc()){
		foreach ($deps as $dep){
			if ($dep['id'] == $row['dep_id']){
				$dep_name = $dep['name'];
			}
		}
		echo "<tr><td>".$row['date']."</td><td>".$row['descr']."</td><td>".$dep_name."</td><td><a href=\"edit.php?id=".$row['id']."&sid=".$id."\">Редактировать</a></td></tr>";
	}
	echo "</table><br/>";
}


?>

	
	

	
	
	
	
	
	
	
	
	
	
	