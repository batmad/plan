<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');
include('function.php');

if (isset($_POST) && !empty($_POST)){
	if (isset($_POST['button1'])){
		$id = $_POST['id'];
		$sid = $_POST['sid'];
		$descr = $_POST['descr'];
		$date = correct_date($_POST['date']);
		$ctrl = $_POST['ctrl'];
		$dep_id = $_POST['dep_id'];
		$answer = $_POST['answer'];
		$comment = $_POST['comment'];
		$day_performed = correct_date($_POST['day_performed']);
		$performed = $_POST['performed'];
		//Если поменялся Департамент, то обнуляем id специалиста
		$query = "SELECT dep_id FROM control WHERE id='$id'";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		if ($row['dep_id']==$dep_id){
			$query = "UPDATE `control` 
					  SET `descr`='$descr',
						  `date`='$date',
						  `ctrl`='$ctrl',
						  `dep_id`='$dep_id', 
						  `comment`='$comment',
						  `answer`='$answer',
						  `performed`='$performed',
						  `day_performed`='$day_performed'  
				      WHERE `id`='$id'";
		}
		else{
			$query = "UPDATE `control` 
					  SET `descr`='$descr',
						  `date`='$date',
						  `ctrl`='$ctrl',
						  `dep_id`='$dep_id', 
						  `spec_id`='', 
						  `comment`='$comment',
						  `answer`='$answer',
						  `performed`='$performed',
						  `day_performed`='$day_performed'  
					  WHERE `id`='$id'";
		}
		$result = $mysqli->query($query);
		if (isset($_SESSION['is_entering_item']) && !empty($_SESSION['is_entering_item'])){
			header("Location:http://$_SERVER[SERVER_ADDR]/control/admin/add.php?id=$sid");
		}
		else{
			header("Location:http://$_SERVER[SERVER_ADDR]/control/admin/");
		}
	}
	else if (isset($_POST['button2'])){
		$id = $_POST['id'];
		$cid = $_POST['cid'];
		//удаляем общее поручение так же
		//if ($cid){
			//$query = "DELETE FROM control_item WHERE `id`='$cid'";
			//$result = $mysqli->query($query);
		//}
		$query = "DELETE FROM control WHERE `id`='$id'";
		$result = $mysqli->query($query);
		
		if ($_SESSION['is_entering_item']){
			header("Location:http://$_SERVER[SERVER_ADDR]/control/admin/add.php?id=$sid");
		}
		else{
			header("Location:http://$_SERVER[SERVER_ADDR]/control/admin/");
		}
	}
	else if(isset($_POST['button3'])){
		$_SESSION['is_entering_item'] = false;
		header("Location:http://$_SERVER[SERVER_ADDR]/control/admin/");
	}
	else {
		echo "Ошибка";
	}
}

if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	if (isset($_GET['sid'])){
		$sid = $_GET['sid'];
	}
	$query= "SELECT `c`.`id`,`c`.`descr`,`c`.`control_id`,`c`.`answer`,`c`.`comment`,`c`.`performed`,`c`.`day_performed`,`c`.`dep_id`,`c`.`spec_id`,`c`.`date`,`c`.`ctrl`,`d`.`name` AS `dep_name`, `n`.`name` AS `spec_name` FROM `control` AS `c` LEFT JOIN `department` AS `d` ON (`c`.`dep_id`=`d`.`id`) LEFT JOIN `name` AS `n` ON (`c`.`spec_id`=`n`.`id`) WHERE `c`.`id`='$id'";
	$result = $mysqli->query($query);
	
}

$row = $result->fetch_assoc();
$ctrl_id = $row['control_id'];
$dates = explode('-',$row['date']);
$rdate = $dates[2]."-".$dates[1]."-".$dates[0];
$dates = explode('-',$row['day_performed']);
$rdateperf = $dates[2]."-".$dates[1]."-".$dates[0];

$query = "SELECT name,id FROM department";
$result = $mysqli->query($query);
while ($dep = $result->fetch_assoc()){
	$deps[] = $dep;
}

$query= "SELECT `id`,`descr` FROM `control` WHERE `control_id`='$ctrl_id' AND `id`<>'$id' AND `control_id`<>'' ";
$result = $mysqli->query($query);
while ($sim_ctrl = $result->fetch_assoc()){
	$sims[] = $sim_ctrl;
}

$query = "SELECT `descr` FROM `control_item` WHERE `id`='$ctrl_id'";
$result = $mysqli->query($query);
$item = $result->fetch_assoc();



?>
<form action=<?php echo $_SERVER['PHP_SELF'] ?> method='post'>
<input type='hidden' name='id' value='$id'>
<input type='submit' name='button3' value='Вернуться назад'>
</form>

<h2>Редактирование поручения</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">

	<b>Срок:  </b>
	<input type="text" value ="<?php echo $rdate?>" name="date"></br>
	<b>День исполнения:  </b>
	<input type="text" value ="<?php echo $rdateperf?>" name="day_performed"></br>
	<b>Количество просроченных дней:  </b>
	<input type="text" value ="<?php echo $row['performed']?>" name="performed"></br>
	<b>Снято с контроля:  </b>
	<select name='ctrl'>
	<?php
	if($row['ctrl']){
		echo "<option selected value=1>Да</option><option  value=0>Нет</option>";
	}
	else
	{
		echo "<option value=1>Да</option><option selected value=0>Нет</option>";
	}
	?>
	</select><br/>
	
	<b>Департамент:  </b></br>
	<select name="dep_id">
	<?php
	foreach ($deps as $dep){
		if ($dep['id'] == $row['dep_id']){
			echo "<option selected value=".$dep['id'].">".$dep['name']."</option>";
		}
		else {
			echo "<option value=".$dep['id'].">".$dep['name']."</option>";
		}
	}
	?>
	</select><br/>
	<b>Поручение:</b><br/>
	<textarea name="descr" cols=100 rows=10 ><?php echo $row['descr']?></textarea><br/>
	<b>Ответ:</b><br/>
	<textarea name="answer" cols=100 rows=10 ><?php echo $row['answer']?></textarea><br/>
	<b>Комментарий:</b><br/>
	<textarea name="comment" cols=100 rows=10 ><?php echo $row['comment']?></textarea><br/>
   	<input type="hidden" name="id" value="<?php echo $id?>">
   	<input type="hidden" name="sid" value="<?php echo $sid?>">
	<input type="submit" name='button1'>
	</form>

	<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	<input type="hidden" name="id" value="<?php echo $id?>">
	<input type="hidden" name="sid" value="<?php echo $sid?>">
	<input type="hidden" name="cid" value="<?php echo $row['control_id']?>">
	<input type="submit" name='button2' value="Удалить">
	</form>
	
	
<?php
if ($item){
	echo "<br><b>Поручение:</b></br>";
	echo $item['descr'] ;
	echo "<a href='edit_item.php?id=$ctrl_id'>Редактировать</a>";
	echo "<br/><br/>";
	}

if (!empty($sims)){
	echo "<b>Другие пункты поручения:</b>";
	echo "<table border=1><tr><th>№ п\п</th><th>Наименование</th><th>Редактировать</th></tr>";

	$i=1;
	foreach($sims as $sim){
		echo "<tr><td>$i</td><td>".$sim['descr']."</td><td><a href=\"edit.php?id=".$sim['id']."&sid=$ctrl_id\">Редактировать</td></tr>";
		$i++;
	}

	echo "</table>";
}
?>
	
	
	
	
	
	
	
	
	
	
	