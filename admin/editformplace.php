<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');


setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){

	$id = $_POST['id'];
	$name = $_POST['name'];

	
	if(isset($_POST['del'])){
		$query = "DELETE FROM `place` WHERE `id`='$id'";
	}
	else{
		$query = "UPDATE place SET `name`='$name' WHERE `id` = '$id'";
	}
	$result = $mysqli->query($query);
	header("Location: http://$_SERVER[SERVER_ADDR]/admin/deplist.php");
}


if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];

}


$query = "SELECT name FROM place WHERE `id` = '$id'";
$result = $mysqli->query($query);
$result = $result->fetch_assoc();
$name = $result['name'];


?>
<a href="/admin/list.php">Вернуться </a>
<h2>Редактирование места проведения совещания</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    Место: <input type="text" name="name" value="<?php echo $name ?>"><br/>
   	<input type="hidden" name="id" value="<?php echo $id?>"><br/>
	<input type="submit" name="edit" value="Редактировать"><br/><br/><br/>
	<input type="submit" name="del" value="Удалить">
	</form>


<?php
$mysqli->close();
?>

