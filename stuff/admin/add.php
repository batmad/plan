<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
//include('checkauth.php');

echo "<a href='index.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";
if (isset($_POST) && !empty($_POST)){
	$descr = $_POST['descr'];
	$id_name = $_POST['id_name'];
	$start_post = $_POST['start'];
	$end_post = $_POST['end'];
	
	$array_start = explode("-",$start_post);
	$array_end = explode("-",$end_post);
	$start = date('U',mktime(0,0,0,$array_start[1],$array_start[0],$array_start[2]));
	$end = date('U',mktime(23,59,59,$array_end[1],$array_end[0],$array_end[2]));
	
	$query = "INSERT INTO `stuff`(`descr`,`id_name`,`start`,`end`) VALUES('$descr','$id_name','$start','$end')";
	$result = $mysqli->query($query);
	header("Location: http://10.50.10.100/stuff/admin/");
}

if (isset($_GET) && !empty($_GET)){
	$name = $_GET['name'];
}

$query= "SELECT name,id FROM `name` ";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc() ){
	$ruks[] = $row;
}


?>

<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    ФИО: <select name="id_name">
	
	<?php 
	foreach($ruks as $ruk){
		if($name == $ruk['id']){
			echo "<option selected value=".$ruk['id'].">".$ruk['name']."</option>";	
		}
		else{
			echo "<option value=".$ruk['id'].">".$ruk['name']."</option>";	
		}
	}
	echo "</select>";
	?>
	<br/>
	Начало:<input type="text" name="start"><br/>
	Конец:<input type="text" name="end"><br/>
	<textarea name="descr" cols="50" rows="20"></textarea><br/>
	<input type="submit">
</form>