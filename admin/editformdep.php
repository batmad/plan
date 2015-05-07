<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){

	$id = $_POST['id'];
	$name = $_POST['name'];
	$short = $_POST['short'];

	
	if(isset($_POST['del'])){
		$query = "DELETE FROM `department` WHERE `id`='$id'";
	}
	else{
		$query = "UPDATE department SET `name`='$name',`short`='$short' WHERE `id` = '$id'";
	}
	$result = $mysqli->query($query);
	header("Location: http://$_SERVER[SERVER_ADDR]/admin/deplist.php");
}


if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];

}


$query = "SELECT name, short FROM department WHERE `id` = '$id'";
$result = $mysqli->query($query);
$result = $result->fetch_assoc();
$name = $result['name'];
$short = $result['short'];

?>
<a href="/admin/list.php">Вернуться </a>
<h2>Редактирование структурного подразделения</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    ФИО: <input type="text" name="name" value="<?php echo $name ?>"><br/>
	Login: <input type="text" name="short" value="<?php echo $short ?>"><br/>
   	<input type="hidden" name="id" value="<?php echo $id?>"><br/>
	<input type="submit" name="edit" value="Редактировать"><br/><br/><br/>
	<input type="submit" name="del" value="Удалить">
	</form>
	<a href="changepwdadm.php?id=<?php echo $id?>">Изменить пароль</a>

<?php
$mysqli->close();
?>

