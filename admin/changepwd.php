<?php
header('Content-type: text/html; charset=utf-8');
include('bd.php');
include('date.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");



if (isset($_POST) && !empty($_POST)){
	$id = $_POST['id'];
	$login = $_POST['login'];
	$password = password_hash($_POST['password'],PASSWORD_DEFAULT);

	$query = "SELECT login FROM name WHERE `login`='$login' AND `id`<>'$id'";
	$result = $mysqli->query($query);
	if($obj=$result->fetch_object()){
		echo "<h1>Введенный логин уже сущесвует!</h1>";
	}
	else{
		$query_names = "UPDATE name SET `login`='$login', `password`='$password' WHERE `id` = '$id'";
		$result = $mysqli->query($query_names);

		header("Location: http://10.50.10.100/admin/list.php");
	}
}


if (isset($_GET) && !empty($_GET)){
	$id = $_GET['id'];

}

$query = "SELECT login,name FROM name WHERE `id` = '$id'";
$result = $mysqli->query($query);
$result = $result->fetch_assoc();
$login = $result['login'];
$name = $result['name'];

?>

<a href="/admin/list.php">Вернуться </a>
<h2>Редактирование сотрудника <?php echo $name?></h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
	Логин: <input type="text" name="login" value="<?php echo $login?>"><br/>
   	Пароль: <input type="password" name="password"><br/>
	<input type="hidden" name="id" value="<?php echo $id?>">
	<input type="submit" >
	</form>
	<a href="editformcli.php?id=<?php echo $id?>">Назад</a>

<?php
$mysqli->close();
?>

