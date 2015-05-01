<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
//include('checkauth.php');

echo "<a href='index.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";

if (isset($_POST) && !empty($_POST)){
	$id = $_POST['id'];
	$descr = $_POST['descr'];
	$id_name = $_POST['id_name'];
	$start_post = $_POST['start'];
	$end_post = $_POST['end'];
	
	$array_start = explode("-",$start_post);
	$array_end = explode("-",$end_post);
	$start = date('U',mktime(0,0,0,$array_start[1],$array_start[0],$array_start[2]));
	$end = date('U',mktime(23,59,59,$array_end[1],$array_end[0],$array_end[2]));

	$query = "UPDATE `stuff` SET `descr` = '$descr', `start` = '$start', `id_name` = '$id_name', `end`='$end' WHERE `id` = '$id'";
	$result = $mysqli->query($query);

	header("Location: http://$_SERVER[SERVER_ADDR]/stuff/admin/");
}

if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];
	$name = $_GET['name'];
	
	$query= "SELECT descr,start,end FROM `stuff` WHERE id = $id";
	$result = $mysqli->query($query);
	$stuff = $result->fetch_assoc();
	
	$query= "SELECT name,id FROM `name` ";
	$result = $mysqli->query($query);
	while ($row = $result->fetch_assoc() ){
		$ruks[] = $row;
	}
	
	$stuff['start'] = date('d-m-Y',$stuff['start']);
	$stuff['end'] = date('d-m-Y',$stuff['end']);
}

?>

<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    ФИО: <select name="id_name">
	
	<?php
	foreach($ruks as $ruk){
		if ($ruk['id'] == $name){
			echo "<option selected value=".$ruk['id'].">".$ruk['name']."</option>";	
		}
		else {
			echo "<option value=".$ruk['id'].">".$ruk['name']."</option>";	
		}
	}
	echo "</select>";
	?>
	<br/>
	Начало:<input type="text" name="start" value="<?php echo $stuff['start']?>"><br/>
	Конец:<input type="text" name="end" value="<?php echo $stuff['end']?>"><br/>
	<textarea name="descr" cols="50" rows="20"><?php echo $stuff['descr'] ?></textarea><br/>
	<input type="hidden" name="id" value="<?php echo $id?>">
	<input type="submit">
</form>