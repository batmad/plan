<?php
header('Content-type: text/html; charset=utf-8');
include($_SERVER['DOCUMENT_ROOT'].'/bd.php');
include('checkauth.php');

setlocale(LC_TIME,"ru_RU");

echo "<a href='/admin/list.php'><img src='/img/previous.png' title='Вернуться обратно'></a>";
$result = setlocale(LC_ALL, 'ru_RU.UTF-8');

if (isset($_POST) && !empty($_POST)){
	$name = $_POST['name'];
	$username = $_POST['username'];
	$password = password_hash($_POST['password'],PASSWORD_DEFAULT);
	$query_names = "INSERT INTO admins(`name`,`username`,`password`) VALUES ('$name','$username','$password')";
	$result = $mysqli->query($query_names);
	header("Location: http://$_SERVER[SERVER_ADDR]/admin/admlist.php");
}


?>
<h2>Добавление администратора</h2>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    ФИО:            <input type="text" name="name"><br/>
    Логин:          <input type="text" name="username"><br/>
    Пароль:         <input type="password" name="password"><br/>
	
	<input type="submit">
    </form>

<?php
$mysqli->close();
?>

