<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){

	$id = $_POST['id'];
	$name = $_POST['name'];
	$username = $_POST['username'];

	
	if(isset($_POST['del'])){
		$query = "DELETE FROM `admins` WHERE `id`='$id'";
	}
	else{
		$query = "UPDATE admins SET `name`='$name',`username`='$username' WHERE `id` = '$id'";
	}
	$result = $mysqli->query($query);
	header("Location: http://$_SERVER[SERVER_ADDR]/admin/admlist.php");
}


if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];

}


$query = "SELECT name, username FROM admins WHERE `id` = '$id'";
$result = $mysqli->query($query);
$result = $result->fetch_assoc();
$name = $result['name'];
$username = $result['username'];

?>
<a href="/admin/list.php">Вернуться </a>
<h2>Редактирование сотрудника</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    ФИО: <input type="text" name="name" value="<?php echo $name ?>"><br/>
	Login: <input type="text" name="username" value="<?php echo $username ?>"><br/>
   	<input type="hidden" name="id" value="<?php echo $id?>"><br/>
	<input type="submit" name="edit" value="Редактировать"><br/><br/><br/>
	<input type="submit" name="del" value="Удалить">
	</form>
	<a href="changepwdadm.php?id=<?php echo $id?>">Изменить пароль</a>

<?php
$mysqli->close();
?>

